<?php


namespace App\Service;


use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Swift_Message;

/**
 * Class Greeting
 * @package App\GreentingService
 *
 * This service is:
 * - returning and logging hi to a name string passed to i
 *
 * TODO: test this service.
 *
 * give it ahmed -> logs and returns hi to ahmed.
 */
class Greeting
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $message;

    public function __construct(LoggerInterface $logger, Swift_Mailer $mailer , string $message)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->message = $message;
    }

    public function greet($name): string
    {
        $message = (new Swift_Message("Hello $name"))
            ->setTo('admin@example.com')
            ->setFrom('me@example.com')
            ->addPart('Hello There, welcome to the site.');
        $this->mailer->send($message);

        $this->logger->info($this->message . " $name ");
        return "Hi $name";
    }
}