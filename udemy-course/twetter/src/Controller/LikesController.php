<?php


namespace App\Controller;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LikesController
 * @package App\Controller
 * @Route("/likes")
 */
class LikesController extends AbstractController
{
    /**
     * @param MicroPost $post
     * @Route("/like/{id}", name="like_post")
     * @return JsonResponse
     */
    public function like(MicroPost $post)
    {
        $currentUser = $this->getUser();
        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $post->likePost($currentUser);

        // DONT FORGET FLUSHING!
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([
            'count' => $post->getLikedBy()->count(),
        ]);
    }

    /**
     * @param MicroPost $post
     * @Route("/unlike/{id}",  name="unlike_post")
     * @return JsonResponse
     */
    public function unlike(MicroPost $post)
    {
        $currentUser = $this->getUser();
        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $post->getLikedBy()->removeElement($currentUser);
        // DONT FORGET FLUSHING!
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([
            'count' => $post->getLikedBy()->count(),
        ]);
    }


}