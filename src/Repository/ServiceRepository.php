<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * @return Service[]
     */
    public function findWarningOrDanger()
    {
        $all = $this->findAll();
        $toReturn = array();
        foreach ($all as $service)
        {
            if ($service->getStatus() != 'okay')
            {
                $toReturn[] = $service;
            }
        }
        return $toReturn;
    }

    /**
     * @param $service
     * @return string
     */
    public function alreadyExistsInDatabase($service)
    {
        return $this->_em->createQuery('
            SELECT s
            FROM App\Entity\Service s, App\Entity\Entreprise e
            WHERE 
            s.entreprise = e AND
            s.nom = :nom_service AND 
            e.nom = :nom_entreprise')
        ->setParameters([
            'nom_service' => $service->getNom(),
            'nom_entreprise' => $service->getEntreprise()->getNom(),
        ])->getResult();
    }

    // /**
    //  * @return Service[] Returns an array of Service objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Service
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
