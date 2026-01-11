<?php

namespace App\Repository;

use App\Entity\Voyage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voyage>
 */
class VoyageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyage::class);
    }

    /**
     * Cette méthode récupère les 2 derniers voyages ajoutés en base de données
     * @return Voyage[]
     */
    public function findLastTwo(): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.id', 'DESC') // Trie par ID du plus grand au plus petit
            ->setMaxResults(2)        // Limite le résultat à 2
            ->getQuery()
            ->getResult();
    }
}