<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController
{

    /**
     * @Route("/blog/{page<\d+>}", name="blog_pageid_show")
     *
     * @param string $page
     * @return Response
     */
    public function showPage(int $page = 0)
    {
        return new Response("<h1> Page: " . str_replace("-", " ", $page) . "</h1>");
    }

    /**
     * @Route("/blog/{slug}", name="blog_show")
     *
     * @param string $slug
     * @return Response
     */
    public function show(string $slug)
    {
        return new Response("<h1> Title: " . str_replace("-", " ", $slug) . "</h1>");
    }

}