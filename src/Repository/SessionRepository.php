<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    // Requete pour récupérer les sessions en cours
    public function sessionsEnCours() {
        return $this->createQueryBuilder('s')
            ->where('s.date_debut < :today AND s.date_fin > :today')
            ->orderBy('s.date_debut', 'ASC')
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    // Requete pour récupérer les sessions terminées
    public function sessionsTerminee() {
        return $this->createQueryBuilder('s')
            ->where('s.date_fin < :today')
            ->orderBy('s.date_debut', 'ASC')
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    // Requete pour récupérer les sessions à venir
    public function sessionsFuture() {
        return $this->createQueryBuilder('s')
            ->where('s.date_debut > :today')
            ->orderBy('s.date_debut', 'ASC')
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
