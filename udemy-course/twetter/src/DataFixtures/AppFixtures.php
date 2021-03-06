<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreferences;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'super_admin',
            'email' => 'marry_gold@gold.com',
            'password' => 'admin12345',
            'fullName' => 'Super Admin',
            'roles' => [User::ROLE_ADMIN]
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];
    private const LANGUAGES = [
        'en',
        'fr'
    ];
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(self::POST_TEXT[rand(0, 8)]);
            $date = new DateTime('2018-10-10');
            $date->modify('-' . rand(0, 10) . 'days');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(self::USERS[rand(0, 2)]['username']));
            $manager->persist($microPost);
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
            $user->setFullname($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);
            $preference = new UserPreferences();
            $preference->setLocale(self::LANGUAGES[rand(0, 1)]);
            $user->addPreference($preference);
            $this->addReference($userData['username'], $user);
            $manager->persist($user);
//            $manager->persist($preference);

            $manager->flush();
        }

    }

}
