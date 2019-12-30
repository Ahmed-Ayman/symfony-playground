<?php


namespace App\Controller;


use App\Repository\NotificationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotificationController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @Route("/unread-count" , name="notification_unread" )
     */
    public function unreadCount()
    {
        return new JsonResponse([
                'count' => $this->notificationRepository->findUnseenByUser($this->getUser())
            ]
        );
    }

}