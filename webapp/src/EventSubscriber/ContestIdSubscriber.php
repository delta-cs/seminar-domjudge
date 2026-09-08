<?php declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\DOMJudgeService;
use GuzzleHttp\Psr7\Uri;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ContestIdSubscriber implements EventSubscriberInterface
{
    private DOMJudgeService $dj;

    /** @var string[] */
    private array $contestIdURLsPrefixes;

    /**
     * @param string[] $contestIdURLsPrefixes
     */
    public function __construct(DOMJudgeService $dj, array $contestIdURLsPrefixes)
    {
        $this->dj = $dj;
        $this->contestIdURLsPrefixes = $contestIdURLsPrefixes;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $requestUri = new Uri($request->getRequestUri());

        // if the request path does not start with any of the contestIdURLsPrefixes return null
        if (!array_reduce(
            $this->contestIdURLsPrefixes,
            fn(bool $carry, string $prefix): bool => $carry || str_starts_with($requestUri->getPath(), $prefix),
            false
        )) {
            return;
        }

        $teamId = $this->dj->getUser()?->getTeamId();
        $currentContest = $this->dj->getCurrentContest($teamId);

        if ($currentContest === null) {
            // Nothing to pin the URL to; let the controller deal with it.
            return;
        }

        if (!$cid = (int)$request->query->get('cid')) {
            // "No cid found. Setting cid to current contest."
            $response = new RedirectResponse(
                $this->addQueryParameter(
                    $request->getUri(),
                    'cid',
                    (string)$currentContest->getCid()
                )
            );
            $response->headers->setCookie(
                new Cookie('domjudge_cid', (string)$currentContest->getCid())
            );
            $event->setResponse($response);
            return;
        }

        if ($cid === $currentContest->getCid()) {
            // "Contest equal to current contest."
            return;
        }

        $currentContests = $this->dj->getCurrentContests($teamId);
        if (!$this->dj->getContest($cid) || !in_array($cid, array_keys($currentContests))) {
            // "Contest not found. Setting cid to current contest."
            $response = new RedirectResponse(
                $this->addQueryParameter(
                    $request->getUri(),
                    'cid',
                    (string)$currentContest->getCid()
                )
            );
            $response->headers->setCookie(
                new Cookie('domjudge_cid', (string)$currentContest->getCid())
            );

            $event->setResponse($response);
            return;
        }

        // "Contest not equal to current contest. Setting cid to required cid."
        $response = new RedirectResponse(
            $this->addQueryParameter(
                $request->getUri(),
                'cid',
                (string)$cid
            )
        );
        $response->headers->setCookie(
            new Cookie('domjudge_cid', (string)$cid)
        );

        $event->setResponse($response);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        // if not instance of RedirectResponse return
        if (!$response instanceof RedirectResponse) {
            return;
        }

        $responseTargetUri = new Uri($response->getTargetUrl());

        // if the response uri does not start with any of the contestIdURLsPrefixes return
        if (!array_reduce(
            $this->contestIdURLsPrefixes,
            fn(bool $carry, string $prefix): bool => $carry || str_starts_with($responseTargetUri->getPath(), $prefix),
            false
        )) {
            return;
        }

        // if the uri already has a cid query parameter return
        if (strpos($response->getTargetUrl(), 'cid=') !== false) {
            return;
        }

        $cid = 0;
        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === 'domjudge_cid') {
                $cid = (int)$cookie->getValue();
                break;
            }
        }

        // if no cid cookie is found this was not a redirect from the contest switcher
        if (!$cid) {
            return;
        }

        $response->setTargetUrl(
            $this->addQueryParameter(
                $response->getTargetUrl(),
                'cid',
                (string)$cid
            )
        );

        $event->setResponse($response);
    }

    private function addQueryParameter(string $uri, string $param, string $value): string
    {
        return Uri::withQueryValue(new Uri($uri), $param, $value)->__toString();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
