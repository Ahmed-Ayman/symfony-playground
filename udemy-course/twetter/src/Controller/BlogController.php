<?php


namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var array $posts
     */
    private $posts;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(SessionInterface $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->posts = $this->session->get('posts');
        $this->router = $router;
    }

    /**
     * @Route("/", name="blog_index")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', ['posts' => $this->posts]);
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {
        $this->posts[uniqid()] = [
            'title' => 'Random Title' . rand(1, 10000),
            'body' => rand(1, 1000) . ' - Lorem ipst Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
            sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.'
        ];
        $this->session->set('posts', $this->posts);

        $this->addFlash('success', 'added successfully');
        return new RedirectResponse($this->router->generate('blog_index'));

    }

    /**
     * @Route("/show/{id}", name="blog_post")
     */
    public function show($id)
    {
        if (!isset($this->posts[$id]))
            throw  new NotFoundHttpException('not found');

        return $this->render('blog/post.html.twig', ['post' => $this->posts[$id]]);

    }
}