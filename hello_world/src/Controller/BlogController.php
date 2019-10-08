<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", requirements={"locale": "en|es|fr"}, name="blog_")
 */
class BlogController
{
    /**
     * @Route("/{_locale}", name="index")
     */
    public function index()
    {
        return new Response('index');
    }

    /**
     * @Route("/{_locale}/posts/{slug}", name="post")
     */
    public function show($slug)
    {
        return new Response('show');

    }
}
