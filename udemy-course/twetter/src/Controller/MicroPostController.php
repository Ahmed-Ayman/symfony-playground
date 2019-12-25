<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/")
 */
class MicroPostController extends AbstractController
{
    /**
     * @var MicroPostRepository
     */
    private $repository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(MicroPostRepository $repository,
                                FormFactoryInterface $formFactory,
                                EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(MicroPost::class)->findBy([], ['time' => 'DESC']);
        return $this->render('micro_post/index--listing.html.twig', [
                'posts' => $posts,
            ]
        );
    }


    /**
     * @Route("/micro-post/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request, TokenStorageInterface $token)
    {
        $microPost = new MicroPost();
        $user = $token->getToken()->getUser();
        $microPost->setUser($user);
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->entityManager->persist($microPost);
                $this->entityManager->flush();
            } catch (ORMException $e) {
                $this->addFlash('error', 'something went wrong');
            }
            $url = $this->router->generate('micro_post_index');
            $this->addFlash('success', 'post was added successfully!');
            return new RedirectResponse($url);
        }
        return $this->render('micro_post/add-post.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/micro-post/edit/{id}", name="micro_post_edit")
     * @Security("is_granted('edit', post)")
     * @param MicroPost $post
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(MicroPost $post, Request $request)
    {
//        $this->denyAccessUnlessGranted('edit', $post);
        $editForm = $this->formFactory->create(
            MicroPostType::class,
            $post);
        // is valid and stuff
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try {
                $this->entityManager->flush();
            } catch (ORMException $e) {
                $this->addFlash('error', 'something went wrong');
            }
            $url = $this->router->generate('micro_post_index');
            $this->addFlash('success', 'post was added successfully!');
            return new RedirectResponse($url);
        }
        return $this->render('micro_post/add-post.html.twig', [
            'form' => $editForm->createView()
        ]);
    }

    /**
     * @Route("/micro-post/delete/{id}", name="micro_post_delete")
     * @Security("is_granted('delete', post)")
     * @param MicroPost $post
     * @return RedirectResponse
     */
    public function delete(MicroPost $post)
    {
        try {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
        } catch (ORMException $e) {
            $this->addFlash('error', 'something went wrong!');
        }
        $this->addFlash('success', 'Deleted Successfully');
        $url = $this->router->generate('micro_post_index');
        return new RedirectResponse($url);
    }


    /**
     * @Route("/micro-post/{id}", name="micro_post_show")
     * @param MicroPost $post
     * @return Response
     */
    public function post(MicroPost $post)
    {
        if (!isset($post))
            throw new NotFoundHttpException('Post not found');

        return $this->render('micro_post/post-page.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/micro-post/user/{username}",name="micro_post_by_user")
     * @param User $user
     */
    public function showUserPosts(User $user)
    {
//        $posts = $this->getDoctrine()->getRepository(MicroPost::class)
//            ->findBy(['user' => $user], ['time' => 'DESC']);
        $posts = $user->getPosts(); // lazy loading and using proxy classes, but you can't sort the posts like above.
        return $this->render('micro_post/index--listing.html.twig', [
                'posts' => $posts,
            ]
        );
    }

}
