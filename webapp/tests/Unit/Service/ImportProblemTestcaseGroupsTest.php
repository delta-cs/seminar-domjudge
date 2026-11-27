<?php declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Problem;
use App\Entity\Testcase;
use App\Entity\TestcaseGroup;
use App\Service\ImportProblemService;
use App\Tests\Unit\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use ZipArchive;

/**
 * Testcase groups travel with a problem through data/testcase_groups.yaml (#200).
 */
class ImportProblemTestcaseGroupsTest extends BaseTestCase
{
    protected array $roles = ['admin'];

    private const MANIFEST = <<<YAML
groups:
  - name: sample
    points_percentage: 0.25
    testcases: [sample/1, secret/01]
  - name: hidden
    points_percentage: 0.75
    testcases: [secret/02, secret/03]
YAML;

    public function testImportAssignsGroupsFromManifest(): void
    {
        $messages = [];
        $problem = $this->import($this->buildZip(self::MANIFEST), null, $messages);

        static::assertEmpty($messages['danger']);
        static::assertSame([], $this->groupWarnings($messages));
        static::assertSame([
            'sample/1'  => ['sample', 0.25],
            'secret/01' => ['sample', 0.25],
            'secret/02' => ['hidden', 0.75],
            'secret/03' => ['hidden', 0.75],
        ], $this->groupsByPath($problem));
    }

    public function testReimportReplacesGroupsAndDropsTheOldOnes(): void
    {
        $messages = [];
        $problem = $this->import($this->buildZip(self::MANIFEST), null, $messages);
        $oldGroupIds = $this->groupIds($problem);

        $manifest = <<<YAML
groups:
  - name: everything
    points_percentage: 1
    testcases: [sample/1, secret/01, secret/02, secret/03]
YAML;
        $problem = $this->import($this->buildZip($manifest), $problem, $messages);

        static::assertSame([], $this->groupWarnings($messages));
        static::assertSame(['everything'], array_unique(array_column($this->groupsByPath($problem), 0)));
        foreach ($oldGroupIds as $id) {
            static::assertNull($this->em()->find(TestcaseGroup::class, $id), "group $id should be removed");
        }
    }

    public function testReimportWithoutManifestKeepsGroups(): void
    {
        $messages = [];
        $problem = $this->import($this->buildZip(self::MANIFEST), null, $messages);
        $before = $this->groupsByPath($problem);

        $problem = $this->import($this->buildZip(null), $problem, $messages);

        static::assertSame($before, $this->groupsByPath($problem));
    }

    public function testBrokenManifestWarnsAndChangesNothing(): void
    {
        $messages = [];
        $problem = $this->import($this->buildZip(self::MANIFEST), null, $messages);
        $before = $this->groupsByPath($problem);

        $broken = "groups:\n  - name: too much\n    points_percentage: 50\n    testcases: [secret/01]\n";
        $problem = $this->import($this->buildZip($broken), $problem, $messages);

        static::assertSame($before, $this->groupsByPath($problem));
        static::assertStringContainsString("points_percentage' between 0 and 1", implode(' | ', $messages['warning']));
    }

    public function testManifestProblemsAreReported(): void
    {
        $manifest = <<<YAML
groups:
  - name: a
    points_percentage: 0.5
    testcases: [sample/1, secret/01, secret/99]
  - name: b
    points_percentage: 0.25
    testcases: [secret/01, secret/02]
YAML;
        $messages = [];
        $problem = $this->import($this->buildZip($manifest), null, $messages);
        $warnings = implode(' | ', $messages['warning']);

        static::assertStringContainsString("lists 'secret/99'", $warnings);
        static::assertStringContainsString("'secret/01' is listed in group 'a' and in group 'b'", $warnings);
        static::assertStringContainsString('in no group, they earn no partial points: {secret/03}', $warnings);
        static::assertStringContainsString('add up to 75% of the problem', $warnings);
        static::assertSame('a', $this->groupsByPath($problem)['secret/01'][0]);
        static::assertNull($this->groupsByPath($problem)['secret/03'][0]);
    }

    public function testExportWritesManifestThatImportsBack(): void
    {
        $messages = [];
        $problem = $this->import($this->buildZip(self::MANIFEST), null, $messages);
        $expected = $this->groupsByPath($problem);

        $this->client->request('GET', sprintf('/jury/problems/%d/export', $problem->getProbid()));
        static::assertResponseIsSuccessful();
        $files = $this->unzipString($this->client->getInternalResponse()->getContent());

        static::assertArrayHasKey(ImportProblemService::TESTCASE_GROUPS_FILE, $files);

        $path = tempnam(sys_get_temp_dir(), 'export-roundtrip-');
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::OVERWRITE);
        foreach ($files as $name => $content) {
            $zip->addFromString($name, $content);
        }
        $zip->close();
        $zip->open($path);
        $copy = $this->import($zip, null, $messages, 'groups-copy.zip');
        unlink($path);

        static::assertSame([], $this->groupWarnings($messages));
        static::assertSame($expected, $this->groupsByPath($copy));
    }

    public function testExportWithoutGroupsHasNoManifest(): void
    {
        $messages = [];
        $problem = $this->import($this->buildZip(null), null, $messages);

        $this->client->request('GET', sprintf('/jury/problems/%d/export', $problem->getProbid()));
        $files = $this->unzipString($this->client->getInternalResponse()->getContent());

        static::assertArrayNotHasKey(ImportProblemService::TESTCASE_GROUPS_FILE, $files);
    }

    private function buildZip(?string $manifest): ZipArchive
    {
        $path = tempnam(sys_get_temp_dir(), 'groups-');
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::OVERWRITE);
        $zip->addFromString('problem.yaml', "name: Groups test\n");
        $zip->addFromString('data/sample/1.in', "1\n");
        $zip->addFromString('data/sample/1.ans', "1\n");
        foreach (['01', '02', '03'] as $i) {
            $zip->addFromString("data/secret/$i.in", "$i\n");
            $zip->addFromString("data/secret/$i.ans", "$i\n");
        }
        if ($manifest !== null) {
            $zip->addFromString(ImportProblemService::TESTCASE_GROUPS_FILE, $manifest);
        }
        $zip->close();
        $zip->open($path);
        return $zip;
    }

    /**
     * @param array<string, string[]> $messages
     */
    private function import(ZipArchive $zip, ?Problem $problem, array &$messages, string $name = 'groups.zip'): Problem
    {
        /** @var ImportProblemService $service */
        $service = self::getContainer()->get(ImportProblemService::class);
        $result = [];
        $imported = $service->importZippedProblem($zip, $name, $problem, null, $result);
        $messages = $result ?? [];
        static::assertNotNull($imported, implode(' | ', $messages['danger'] ?? []));
        $this->em()->clear();
        return $this->em()->find(Problem::class, $imported->getProbid());
    }

    /**
     * @return array<string, array{0: ?string, 1: ?float}> path => [group name, points percentage]
     */
    private function groupsByPath(Problem $problem): array
    {
        $result = [];
        /** @var Testcase[] $testcases */
        $testcases = $this->em()->getRepository(Testcase::class)
            ->findBy(['problem' => $problem], ['ranknumber' => 'ASC']);
        foreach ($testcases as $testcase) {
            $path = ($testcase->getSample() ? 'sample/' : 'secret/') . $testcase->getOrigInputFilename();
            $group = $testcase->getTestcaseGroup();
            $result[$path] = [$group?->getName(), $group?->getPointsPercentage()];
        }
        ksort($result);
        return $result;
    }

    /**
     * @return int[]
     */
    private function groupIds(Problem $problem): array
    {
        $ids = [];
        foreach ($this->em()->getRepository(Testcase::class)->findBy(['problem' => $problem]) as $testcase) {
            if ($group = $testcase->getTestcaseGroup()) {
                $ids[$group->getTestcasegroupid()] = $group->getTestcasegroupid();
            }
        }
        return array_values($ids);
    }

    /**
     * Warnings about testcase groups; the importer also warns about unrelated things,
     * such as jury solutions when the problem is in no contest.
     *
     * @param array<string, string[]> $messages
     * @return string[]
     */
    private function groupWarnings(array $messages): array
    {
        return array_values(array_filter($messages['warning'],
            fn(string $w) => !str_starts_with($w, 'No jury solutions added')));
    }

    private function em(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }
}
