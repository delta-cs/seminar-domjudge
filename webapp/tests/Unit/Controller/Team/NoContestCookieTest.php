<?php declare(strict_types=1);

namespace App\Tests\Unit\Controller\Team;

use App\Tests\Unit\BaseTestCase;
use Symfony\Component\BrowserKit\Cookie;

/**
 * A jury member who picks "no contest" in the jury menu sets the shared contest cookie to -1.
 * Their team's pages must still show the team's contest (#74).
 */
class NoContestCookieTest extends BaseTestCase
{
    protected array $roles = ['admin', 'team'];

    public function testTeamScoreboardIgnoresJuryNoContestChoice(): void
    {
        $this->logIn();
        $this->client->followRedirects();
        $this->client->getCookieJar()->set(new Cookie('domjudge_cid', '-1'));

        $this->client->request('GET', '/team/scoreboard');

        static::assertResponseIsSuccessful();
        static::assertSelectorNotExists('p.nodata:contains("No active contest")');
        static::assertSelectorExists('.scoreboard');
        static::assertStringContainsString('cid=', $this->client->getRequest()->getUri());
    }

    public function testJuryStillHonoursNoContest(): void
    {
        $this->logIn();
        $this->client->getCookieJar()->set(new Cookie('domjudge_cid', '-1'));

        $this->client->request('GET', '/jury');

        static::assertResponseIsSuccessful();
        // The contest switcher marks no contest as selected.
        static::assertSelectorExists('.dropdown-menu[data-current-contest] a.dropdown-item');
        static::assertSelectorNotExists('.dropdown-menu[data-current-contest] a.dropdown-item.active');
    }
}
