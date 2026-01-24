<?php

namespace App\Repository;

use App\Entity\Voyage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VoyageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyage::class);
    }

    /**
     * @return Voyage[]
     */
    public function findByEnvironnement(string $environnementName): array
    {
        return $this->createQueryBuilder('v')
            ->join('v.environnement', 'e')
            ->where('LOWER(e.nom) LIKE LOWER(:val)')
            ->setParameter('val', '%' . $environnementName . '%')
            ->orderBy('v.datecreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
