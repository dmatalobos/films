<?php
namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Film;

class SearchIndexer
{

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Film" entity
        if (!$entity instanceof Film) {
            return;
        }

        $entityManager = $args->getEntityManager();

        $data = $entityManager->getUnitOfWork()->getEntityChangeSet($args->getEntity());


        $film = $entityManager->getRepository(Film::class)->findOneBy(
            ['title' => $data['title'][1]],
            ['id' => 'DESC']
        );

        $film->setTitle($data['title'][1].' (updated with Listener)');

        $entityManager->flush();

    }
}