<?php

/**
 *
 *
 *
 *
 */
namespace Kazetenn\Core\Admin\EventListener;

use DateTime;
use Kazetenn\Articles\Entity\Article;
use Kazetenn\Pages\Entity\Page;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TimestampableListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Page and  !$entity instanceof Article) {
            return;
        }

        $date = new DateTime();
        $entity->setCreatedAt($date);
        $entity->setUpdatedAt($date);

//        $entityManager = $args->getObjectManager();
//        $entityManager->persist($entity);
    }
}