<?php


namespace App\EventListener;


use App\Entity\User;
use App\Entity\UserPreferences;
use App\Event\UserRegisterEvent;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class UserRegisteredSubscriber implements EventSubscriberInterface
{


    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(LoggerInterface $logger, Mailer $mailer, EntityManagerInterface $entityManager, string $defaultLocale)
    {

        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        // you can subscribe to many events
        return [
            UserRegisterEvent::NAME => 'onUserRegister',
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $preferences = new UserPreferences();
        $preferences->setLocale($this->defaultLocale);
        $user = $event->getRegisteredUser();
        $user->addPreference($preferences);
        $this->entityManager->flush();
        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }
}