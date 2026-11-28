<?php declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Entity\User;
use App\EventSubscriber\LocaleSubscriber;
use App\Tests\Unit\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * The team and public pages speak Czech or English, the jury pages stay English (#149).
 */
class LanguageSwitchTest extends BaseTestCase
{
    protected array $roles = ['team'];

    public function testSwitchSetsCookieAndGoesBack(): void
    {
        $this->client->request('GET', '/public/change-language/cs', [], [],
            ['HTTP_REFERER' => 'http://localhost/public?cid=1']);

        static::assertResponseRedirects('http://localhost/public?cid=1');
        $cookie = $this->client->getCookieJar()->get(LocaleSubscriber::COOKIE);
        static::assertNotNull($cookie);
        static::assertSame('cs', $cookie->getValue());
    }

    public function testUnknownLanguageIsRejected(): void
    {
        $this->client->request('GET', '/public/change-language/de');
        static::assertResponseStatusCodeSame(404);
    }

    public function testCookiePicksTheLanguage(): void
    {
        $this->client->followRedirects();
        foreach (['cs', 'en'] as $locale) {
            $this->client->getCookieJar()->set(new Cookie(LocaleSubscriber::COOKIE, $locale));
            $this->client->request('GET', '/public');
            static::assertResponseIsSuccessful();
            static::assertSame($locale, $this->htmlLang());
        }
    }

    public function testSwitchIsSavedForLoggedInUser(): void
    {
        $this->logIn();
        $this->client->request('GET', '/public/change-language/cs');

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->clear();
        static::assertSame('cs', $em->getRepository(User::class)->findOneBy(['username' => 'demo'])->getLocale());

        // The saved choice wins over a cookie, e.g. on another device.
        $this->client->getCookieJar()->set(new Cookie(LocaleSubscriber::COOKIE, 'en'));
        $this->client->followRedirects();
        $this->client->request('GET', '/team');
        static::assertSame('cs', $this->htmlLang());
    }

    public function testJuryPagesStayEnglish(): void
    {
        $this->roles = ['admin'];
        $this->logIn();
        $this->client->getCookieJar()->set(new Cookie(LocaleSubscriber::COOKIE, 'cs'));
        $this->client->followRedirects();

        $this->client->request('GET', '/jury');

        static::assertResponseIsSuccessful();
        static::assertSame('en', $this->htmlLang());
    }

    /**
     * Every string marked for translation in the templates and code has a Czech translation.
     */
    public function testNoMissingCzechTranslations(): void
    {
        $application = new Application(self::$kernel);
        $tester = new CommandTester($application->find('debug:translation'));
        $tester->execute(['locale' => 'cs', '--only-missing' => true, '--domain' => 'messages']);

        static::assertStringNotContainsString(' missing ', $tester->getDisplay(), $tester->getDisplay());
    }

    private function htmlLang(): string
    {
        return (string)$this->client->getCrawler()->filter('html')->attr('lang');
    }
}
