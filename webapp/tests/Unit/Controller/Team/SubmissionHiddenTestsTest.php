<?php declare(strict_types=1);

namespace App\Tests\Unit\Controller\Team;

use App\Entity\Contest;
use App\Entity\ContestProblem;
use App\Entity\Judging;
use App\Entity\JudgingRun;
use App\Entity\Language;
use App\Entity\Submission;
use App\Entity\Testcase;
use App\Entity\User;
use App\Tests\Unit\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Generator;

/**
 * The team submission page tells teams how they did on the hidden tests (#186, #111).
 * The demo problem fltcmp has one sample test and three hidden ones.
 */
class SubmissionHiddenTestsTest extends BaseTestCase
{
    protected array $roles = ['team'];

    /**
     * @dataProvider summaryProvider
     *
     * @param array{0: ?string, 1: ?string, 2: ?string, 3: ?string} $runResults sample, then hidden 1-3; null = not run
     */
    public function testSummary(string $result, array $runResults, string $expected, bool $warning): void
    {
        $submitId = $this->judgedSubmission($result, $runResults);
        $this->logIn();

        $this->client->request('GET', "/team/submission/{$submitId}");

        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('#hiddenTestSummary', $expected);
        if ($warning) {
            static::assertSelectorExists('#hiddenTestSummary .alert-warning');
        } else {
            static::assertSelectorNotExists('#hiddenTestSummary .alert-warning');
        }
    }

    public function summaryProvider(): Generator
    {
        yield 'samples pass, hidden fail' => ['wrong-answer', ['correct', 'correct', 'wrong-answer', 'run-error'],
            'All sample tests passed, but your program failed on hidden tests: 2 of 3 failed.', true];
        yield 'lazy judging stopped on a hidden test' => ['wrong-answer', ['correct', 'correct', 'wrong-answer', null],
            '1 of 3 failed. 1 did not run, because judging stopped at the first failed test.', true];
        yield 'sample fails too' => ['wrong-answer', ['wrong-answer', 'correct', 'wrong-answer', 'correct'],
            'Hidden tests: 1 of 3 failed.', false];
        yield 'lazy judging stopped on the sample' => ['wrong-answer', ['wrong-answer', null, null, null],
            'Hidden tests: not run, because judging stopped at the first failed test.', false];
        yield 'everything passes' => ['correct', ['correct', 'correct', 'correct', 'correct'],
            'Hidden tests: all 3 passed.', false];
    }

    public function testNoSummaryForCompilerError(): void
    {
        $submitId = $this->judgedSubmission('compiler-error', [null, null, null, null]);
        $this->logIn();

        $this->client->request('GET', "/team/submission/{$submitId}");

        static::assertResponseIsSuccessful();
        static::assertSelectorNotExists('#hiddenTestSummary');
    }

    /**
     * @param array<?string> $runResults
     */
    private function judgedSubmission(string $result, array $runResults): int
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $contest = $em->getRepository(Contest::class)->findOneBy(['shortname' => 'demo']);
        /** @var ContestProblem $contestProblem */
        $contestProblem = $contest->getProblems()
            ->filter(fn(ContestProblem $cp) => $cp->getProblem()->getExternalid() === 'fltcmp')
            ->first();
        $team = $em->getRepository(User::class)->findOneBy(['username' => 'demo'])->getTeam();

        /** @var Testcase[] $testcases */
        $testcases = $em->getRepository(Testcase::class)
            ->findBy(['problem' => $contestProblem->getProblem()], ['ranknumber' => 'ASC']);
        static::assertSame([true, false, false, false], array_map(fn(Testcase $t) => $t->getSample(), $testcases));

        $time = $contest->getStarttime() + 60;
        $submission = (new Submission())
            ->setContest($contest)
            ->setTeam($team)
            ->setContestProblem($contestProblem)
            ->setProblem($contestProblem->getProblem())
            ->setLanguage($em->getRepository(Language::class)->find('c'))
            ->setSubmittime($time);
        $judging = (new Judging())
            ->setContest($contest)
            ->setSubmission($submission)
            ->setStarttime($time)
            ->setEndtime($time + 5)
            ->setResult($result);
        $submission->addJudging($judging);
        $em->persist($submission);
        $em->persist($judging);
        foreach ($testcases as $i => $testcase) {
            if ($runResults[$i] === null) {
                continue;
            }
            $run = (new JudgingRun())
                ->setJudging($judging)
                ->setTestcase($testcase)
                ->setRunresult($runResults[$i])
                ->setRuntime(0.1)
                ->setEndtime($time + 1);
            $em->persist($run);
        }
        $em->flush();

        return $submission->getSubmitid();
    }
}
