<?php


namespace App\Controller;


use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GreetingController extends AbstractController
{

    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
    }

    /**
     * @Route("/", name="home_index")
     *
     * @param Request $request
     */
    public function greetName(Request $request)
    {
        return $this->render('base.html.twig', [
            'greeting_message' => $this->greeting->greet($request->get('name') ?? 'no name')
        ]);
    }
}