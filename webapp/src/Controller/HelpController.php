<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/help')]
class HelpController extends BaseController
{
    #[Route('', name: 'help')]
    public function helpAction(): Response
    {
        return $this->render('help/page.html.twig');
    }
}
