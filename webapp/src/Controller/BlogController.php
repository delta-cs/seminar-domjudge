<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/public/blog')]
class BlogController extends BaseController
{
    #[Route(path: '', name: 'public_blog_list')]
    public function blogListAction(): Response {
        return $this->render('public/blog_list.html.twig');
    }

    #[Route(path: '/{id}', name: 'public_blog_post')]
    public function blogPostAction(int $id): Response {
        return $this->render('public/blog_post.html.twig');
    }
}
