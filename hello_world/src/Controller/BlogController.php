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
     * @Route("/blog/{page}", name="blog_index", requirements={"page"=".+"})
     */
    public function index( $page)
    {
        return new Response($page);
    }

}