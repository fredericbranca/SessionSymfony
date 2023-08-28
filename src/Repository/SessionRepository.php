<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    // Requête pour récupérer les stagiaires non inscrits la session choisit
    public function stagiaireNonInscrits($session_id) {
        $em = $this->getEntityManager();
    
        return $em->createQueryBuilder()
            ->select('s')
            ->from('App\Entity\Stagiaire', 's')
            ->leftJoin('s.stagiaire_session', 'ss', 'WITH', 's.id = ss.id AND ss.id = :session')
            ->where('ss.id IS NULL')
            ->setParameter('session', $session_id)
            ->orderBy('s.nom')
            ->getQuery()
            ->getResult();
    }
    

     /** Afficher les stagiaires non inscrits */
     public function findNonInscrits($session_id)
     {
         $em = $this->getEntityManager();
         $sub = $em->createQueryBuilder();
 
         $qb = $sub;
         // sélectionner tous les stagiaires d'une session dont l'id est passé en paramètre
         $qb->select('s')
             ->from('App\Entity\Stagiaire', 's')
             ->leftJoin('s.stagiaire_session', 'se')
             ->where('se.id = :id');
         
         $sub = $em->createQueryBuilder();
         // sélectionner tous les stagiaires qui ne SONT PAS (NOT IN) dans le résultat précédent
         // on obtient donc les stagiaires non inscrits pour une session définie
         $sub->select('st')
             ->from('App\Entity\Stagiaire', 'st')
             ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
             // requête paramétrée
             ->setParameter('id', $session_id)
             // trier la liste des stagiaires sur le nom de famille
             ->orderBy('st.nom');
         
         // renvoyer le résultat
         $query = $sub->getQuery();
         return $query->getResult();
     }

     public function findNonProgrammee($session_id)
     {
        $em = $this->getEntityManager();
      
         // sélectionner tous les modules d'une session dont l'id est passé en paramètre
         // https://www.doctrine-project.org/projects/doctrine-orm/en/2.16/reference/dql-doctrine-query-language.html
         // IDENTITY(single_association_path_expression [, fieldMapping]) - Retrieve the foreign key column of association of the owning side
         // The IDENTITY() DQL function also works for composite primary keys
         $subQuery = $em->createQueryBuilder();
         $subQuery->select('IDENTITY(p.module)')
             ->from('App\Entity\Programme', 'p')
             ->join('p.session', 's')
             ->where('s.id = :session_id')
             ->setParameter('session_id', $session_id);
      
             $sub = $em->createQueryBuilder();
             // sélectionner tous les modules qui ne SONT PAS (NOT IN) dans le résultat précédent
             // on obtient donc les modules non programmés pour une session définie
             $qb = $em->createQueryBuilder();
             $qb->select('m')
                 ->from('App\Entity\Module', 'm')
                 ->where($qb->expr()->notIn('m.id', $subQuery->getDQL()))
                 ->setParameter('session_id', $session_id)
                 ->orderBy('m.nom');
             
             // renvoyer le résultat
             return $qb->getQuery()->getResult();
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
