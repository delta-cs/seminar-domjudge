<?php declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class AddContentSecurityPolicyListener implements EventSubscriberInterface
{
    protected ?Profiler $profiler;
    private array $cspConfig;

    public function __construct(?Profiler $profiler, array $cspConfig)
    {
        $this->profiler = $profiler;
        $this->cspConfig = $cspConfig;
    }

    public static function getSubscribedEvents(): array
    {
        return [ResponseEvent::class => 'onKernelResponse'];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $csp = implode('; ', [
            $this->getDefaultSrcCsp(),
            $this->getStyleSrcCsp(),
            $this->getScriptSrcCsp(),
            $this->getImageSrcCsp(),
            $this->getConnectSrcCsp(),
            $this->getFrameAncestorsCsp()
        ]);

        $response->headers->set('Content-Security-Policy', $csp);
    }

    private function getDefaultSrcCsp(): string
    {
        return "default-src " . $this->cspConfig['defaultSrc'];
    }

    private function getStyleSrcCsp(): string
    {
        return "style-src " . $this->cspConfig['styleSrc'];
    }

    private function getScriptSrcCsp(): string
    {
        // Set the correct CSP based on whether the profiler is enabled, since
        // the profiler requires 'unsafe-eval' for script-src 'self'.
        $unsafeEvalCsp = $this->profiler ? " 'unsafe-eval'" : "";
        return "script-src " . $this->cspConfig['scriptSrc'] . $unsafeEvalCsp;
    }

    private function getImageSrcCsp(): string
    {
        return "img-src " . $this->cspConfig['imgSrc'];
    }

    private function getConnectSrcCsp(): string
    {
        return "connect-src " . $this->cspConfig['connectSrc'];
    }

    private function getFrameAncestorsCsp(): string
    {
        return "frame-ancestors " . $this->cspConfig['frameAncestors'];
    }
}
