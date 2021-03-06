<?php


namespace App\Controller;


use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Form\UserType;
use App\Security\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Twig\Environment;

class RegistrationController
{

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EncoderInterface
     */
    private $encoder;
    /**
     * @var FlashBag
     */
    private $flashBag;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(LoggerInterface $logger, Environment $twig,
                                FormFactoryInterface $formFactory, EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $encoder, FlashBagInterface $flashBag,
                                RouterInterface $router, TokenGenerator $tokenGenerator)
    {
        $this->logger = $logger;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->flashBag = $flashBag;
        $this->router = $router;

        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @Route("/register", methods={"POST", "GET"}, name="register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request, EventDispatcherInterface $dispatcher)
    {
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $user->setConfirmationToken($this->tokenGenerator->getRandomSecureToken(30));
            $user->setRoles(['ROLE_USER']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $userRegisterEvent = new UserRegisterEvent($user);
            $dispatcher->dispatch($userRegisterEvent, UserRegisterEvent::NAME);
            $this->flashBag->add('success', 'an email was sent to you for confirmation.');

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        return new Response($this->twig->render('security/register.html.twig', [
            'form' => $form->createView()
        ]));
    }
}