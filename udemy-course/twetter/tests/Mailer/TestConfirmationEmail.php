<?php


namespace App\Tests\Mailer;


use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use function foo\func;

class TestConfirmationEmail extends TestCase
{
    public function testMailConfirmation()
    {
        $user = new User();
        $user->setEmail('john@doe.com');
        // mocks to add dependencies.
        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $swiftMailer->expects($this->once())->method('send')
            ->with($this->callback(function ($subject) {
                $messageStr = (string)$subject;
//                dump($messageStr); this is a message body
                return strpos($messageStr, 'From: mail@me.com') !== false;
            }));

        $twig = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twig->expects($this->once())->method('render')
            ->with('mail/registration.html.twig', [
                'user' => $user
            ])
            ->willReturn('this is a message body');
        $mailer = new Mailer($swiftMailer, $twig, 'mail@me.com');
        $mailer->sendConfirmationEmail($user);
    }

}