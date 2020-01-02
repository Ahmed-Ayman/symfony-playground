<?php


namespace App\EventListener;


use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Mailer\Mailer;
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

    public function __construct(LoggerInterface $logger, Mailer $mailer)
    {

        $this->mailer = $mailer;
        $this->logger = $logger;
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
        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }
}