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
     * @Route(
     *     "/blogs/{_locale}/search.{_format}",
     *     locale="en",
     *     format="html",
     *     requirements={
     *         "_locale": "en|fr",
     *         "_format": "html|xml",
     *     }
     * )
     */
    public function show()
    {
        return new Response("hello");
    }

}