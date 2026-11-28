<?php declare(strict_types=1);

namespace App\Tests\Unit\Controller\Team;

use App\Entity\Contest;
use App\Tests\Unit\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Generator;

/**
 * Switching contest goes back to the page you came from, unless that page shows one
 * clarification, submission or problem of the contest you are leaving (#26).
 */
class ChangeContestTest extends BaseTestCase
{
    protected array $roles = ['team'];

    /**
     * @dataProvider refererProvider
     */
    public function testRedirectAfterSwitch(string $referer, string $expectedPath): void
    {
        $this->logIn();
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $cid = $em->getRepository(Contest::class)->findOneBy(['shortname' => 'demo'])->getCid();

        $this->client->request('GET', "/team/change-contest/$cid", [], [], ['HTTP_REFERER' => "http://localhost$referer"]);

        static::assertResponseRedirects();
        $target = parse_url($this->client->getResponse()->headers->get('Location'));
        static::assertSame($expectedPath, $target['path']);
    }

    public function refererProvider(): Generator
    {
        yield 'scoreboard stays' => ['/team/scoreboard?cid=99', '/team/scoreboard'];
        yield 'problem list stays' => ['/team/problems', '/team/problems'];
        yield 'clarification goes to the overview' => ['/team/clarifications/12345', '/team'];
        yield 'submission goes to the overview' => ['/team/submission/12345', '/team'];
        yield 'problem statement goes to the problems' => ['/team/problems/12345/statement', '/team/problems'];
        yield 'editor goes to the problems' => ['/team/editor/12345/c', '/team/problems'];
    }
}
