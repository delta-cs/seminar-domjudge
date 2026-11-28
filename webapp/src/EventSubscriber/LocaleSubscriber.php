<?php declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Translation\LocaleSwitcher;

/**
 * Picks the interface language (#149): the user's saved choice, then the language cookie,
 * then the default locale (Czech; English in the test environment). The jury pages and the
 * API are not translated and always stay English.
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    final public const COOKIE = 'domjudge_locale';
    final public const LOCALES = ['cs', 'en'];
    private const ENGLISH_ONLY_PREFIXES = ['/jury', '/api', '/_'];

    public function __construct(
        private readonly Security $security,
        private readonly LocaleSwitcher $localeSwitcher,
        #[Autowire('%kernel.default_locale%')]
        private readonly string $defaultLocale,
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $request = $event->getRequest();
        $locale = $this->localeFor(
            $request->getPathInfo(),
            $this->security->getUser(),
            $request->cookies->get(self::COOKIE)
        );
        // The switcher updates the translator and other locale-aware services, not the request.
        $request->setLocale($locale);
        $this->localeSwitcher->setLocale($locale);
    }

    public function localeFor(string $path, mixed $user, ?string $cookie): string
    {
        foreach (self::ENGLISH_ONLY_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return 'en';
            }
        }
        if ($user instanceof User && in_array($user->getLocale(), self::LOCALES, true)) {
            return $user->getLocale();
        }
        if (in_array($cookie, self::LOCALES, true)) {
            return $cookie;
        }
        return $this->defaultLocale;
    }

    public static function getSubscribedEvents(): array
    {
        // After the firewall (priority 8), so the logged-in user is known.
        return [KernelEvents::REQUEST => ['onKernelRequest', 7]];
    }
}
