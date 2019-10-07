<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController
{
    // this one is used by a yaml route in confg/routes.yaml
    // it's not such a good idea since you will need to keep going between the functions and the
    // route file to double check out.
    public function list()
    {
        return new Response('list of blog posts');
    }

    public function __invoke()
    {
        return new Response('hello world!');
    }
}