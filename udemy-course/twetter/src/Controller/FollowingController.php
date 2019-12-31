<?php


namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

// TODO: make this with an ajax.

/**
 * Class FollowingController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     *
     */
    public function follow(User $userToFollow)
    {

        /* @var $currentUser User */
        $currentUser = $this->getUser();
        if (!$currentUser->isEqualTo($userToFollow)) {
            $currentUser->follow($userToFollow);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('micro_post_by_user',
            ['username' => $userToFollow->getUsername()]);
    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(User $userToUnfollow)
    {
        /* @var $currentUser User */
        $currentUser = $this->getUser();
        $currentUser->getFollowing()->removeElement($userToUnfollow);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('micro_post_by_user',
            ['username' => $userToUnfollow->getUsername()]);
    }
}