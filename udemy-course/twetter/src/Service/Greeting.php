<?php


namespace App\Service;


use Psr\Log\LoggerInterface;

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

    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    public function greet($name ): string
    {
        $this->logger->info("Hi $name ");
        return "Hi $name";
    }
}