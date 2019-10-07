<?php


namespace App\Controller;


use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController
{

    /**
     * @Route("/blog/{slug?}", name="blog_show")
     * @ParamConverter("post", class="App:BlogPost")
     * @param BlogPost $post
     * @return Response
     */
    public function show(BlogPost $post = null)
    {
        return new Response("hello");
    }

}