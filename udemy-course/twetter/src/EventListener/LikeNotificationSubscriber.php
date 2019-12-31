<?php


namespace App\EventListener;


use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;

class LikeNotificationSubscriber implements EventSubscriber
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        // each time user likes a post.
        return [
            Events::onFlush
        ];
    }

// has to be the same name.
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        /// keeps track of all the changes of the entities fetching presisting ..
        $uow = $em->getUnitOfWork();
        // new elements?
        /**
         * @var PersistentCollection $collectionUpdate
         */
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {
            if (!$collectionUpdate->getOwner() instanceof MicroPost) {
                continue;
            }
            if ('likedBy' !== $collectionUpdate->getMapping()['fieldName']) {
                continue;
            }
            $insertDiff = $collectionUpdate->getInsertDiff();
            if (!count($insertDiff)) {
                // no more data.
                return;
            }
            $microPost = $collectionUpdate->getOwner();
            $notification = new LikeNotification();
            // user to be notified the owner of the post.
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            $notification->setSeen(0);
            // get the first element of the insert diff array that has the user.
            $notification->setLikedBy(reset($insertDiff));
            $em->persist($notification);
            // continue flushing.
            $uow->computeChangeSet(
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );
        }
    }
}