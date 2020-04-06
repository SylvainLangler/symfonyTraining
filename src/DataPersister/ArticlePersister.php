<?php

namespace App\DataPersister;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class ArticlePersister implements DataPersisterInterface{

    public function __construct(EntityManagerInterface $manager){
        $this->em = $manager;
    }

    public function supports($data): bool
    {
        return $data instanceof Article;
    }
    
    public function persist($data)
    {
        // 1. Mettre une date de création sur l'article
        $data->setCreatedAt(new \DateTime());

        // 2. Demander à Doctrine de persister
        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data)
    {
        // 1. Demander à doctrine de supprimer l'article
        $this->em->remove($data);
        $this->em->flush();
    }
}