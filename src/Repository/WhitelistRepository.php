<?php

namespace App\Repository;

use App\Entity\Whitelist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class WhitelistRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Whitelist::class);
    }


    public function findByItem($value)
    {
       
        return $this->createQueryBuilder('w')
            ->where('w.item = :value')->setParameter('value', $value)
            ->orderBy('w.item', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
}
