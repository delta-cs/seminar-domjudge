<?php declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DataFixtures\Test\AddMoreDemoUsersFixture;
use App\DataFixtures\Test\SampleSubmissionsMultipleTriesFixture;
use App\Entity\Contest;
use App\Entity\Problem;
use App\Entity\ScoreboardType;
use App\Entity\Team;
use App\Service\ScoreboardService;
use App\Tests\Unit\BaseTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Generator;

/**
 * refreshCache() writes the cells and rank rows of teams without submissions in bulk.
 * It must still end up exactly where computing every cell one by one does.
 */
class ScoreboardRefreshCacheTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([AddMoreDemoUsersFixture::class, SampleSubmissionsMultipleTriesFixture::class]);
    }

    /**
     * @dataProvider scoreboardTypeProvider
     */
    public function testRefreshMatchesCellByCellComputation(ScoreboardType $type): void
    {
        $contest = $this->demoContest($type);
        $service = self::getContainer()->get(ScoreboardService::class);

        // The reference: every cell and every rank row computed individually.
        foreach ($this->teams($contest) as $team) {
            foreach ($this->problems($contest) as $problem) {
                $service->calculateScoreRow($contest, $team, $problem, false);
            }
            $service->updateRankCache($contest, $team);
        }
        $expected = $this->cacheRows($contest);
        static::assertNotEmpty($expected['score'], 'the reference should have cells');

        // Damage the cache: wrong values, missing rows.
        $cid = $contest->getCid();
        $db = $this->db();
        $db->executeStatement('UPDATE scorecache SET submissions_public = 7, points_public = 99, is_correct_public = 1 WHERE cid = ?', [$cid]);
        $db->executeStatement('DELETE FROM scorecache WHERE cid = ? AND MOD(teamid + probid, 2) = 0', [$cid]);
        $db->executeStatement("UPDATE rankcache SET points_public = 42, sort_key_public = 'x', sort_key_restricted = 'x' WHERE cid = ?", [$cid]);
        $db->executeStatement('DELETE FROM rankcache WHERE cid = ? AND MOD(teamid, 2) = 0', [$cid]);

        $service->refreshCache($contest);

        static::assertEquals($expected, $this->cacheRows($contest));
    }

    public function scoreboardTypeProvider(): Generator
    {
        yield 'pass-fail' => [ScoreboardType::PASS_FAIL];
        yield 'scoring' => [ScoreboardType::SCORING];
    }

    private function demoContest(ScoreboardType $type): Contest
    {
        $em = self::getContainer()->get(EntityManagerInterface::class);
        /** @var Contest $contest */
        $contest = $em->getRepository(Contest::class)->findOneBy(['shortname' => 'demo']);
        // Open to all teams, like the contests where most cells are empty.
        $contest->setOpenToAllTeams(true)->setScoreboardType($type);
        $em->flush();
        return $contest;
    }

    /**
     * @return Team[]
     */
    private function teams(Contest $contest): array
    {
        return self::getContainer()->get(EntityManagerInterface::class)
            ->getRepository(Team::class)->findBy([], ['teamid' => 'ASC']);
    }

    /**
     * @return Problem[]
     */
    private function problems(Contest $contest): array
    {
        $problems = [];
        foreach ($contest->getProblems() as $contestProblem) {
            $problems[] = $contestProblem->getProblem();
        }
        return $problems;
    }

    /**
     * @return array{score: list<array<string, mixed>>, rank: list<array<string, mixed>>}
     */
    private function cacheRows(Contest $contest): array
    {
        $db = $this->db();
        return [
            'score' => $db->fetchAllAssociative('SELECT * FROM scorecache WHERE cid = ? ORDER BY teamid, probid', [$contest->getCid()]),
            'rank' => $db->fetchAllAssociative('SELECT * FROM rankcache WHERE cid = ? ORDER BY teamid', [$contest->getCid()]),
        ];
    }

    private function db(): Connection
    {
        return self::getContainer()->get(EntityManagerInterface::class)->getConnection();
    }
}
