<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", requirements={"locale": "en|es|fr"}, name="blog_")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{_locale}", name="index", defaults={"_locale":"en"})
     */
    public function index(Request $request)
    {
        $route_name = $request->attributes->get('_route');

        return $this->render('blog.html.twig');
    }

    /**
     * @Route("/{_locale}/posts/{slug}", name="post")
     */
    public function show($slug)
    {
        return new Response('show');

    }
}
