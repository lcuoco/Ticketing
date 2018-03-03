<?php

namespace TicketingBundle\Repository;

/**
 * DemandesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DemandesRepository extends \Doctrine\ORM\EntityRepository
{
    public function myFindOne($id)

    {

        $qb = $this->createQueryBuilder('d');


        $qb
            ->where("d.etat = 'soumis'")

            ->where('d.utilisateur = :id')



            ->setParameter('id', $id)

        ;


        return $qb

            ->getQuery()

            ->getResult()

            ;

    }

    public function myFindOnedem($id)

    {

        $qb = $this->createQueryBuilder('d');


        $qb

            ->where('d.id = :id')

            ->setParameter('id', $id)

        ;


        return $qb

            ->getQuery()

            ->getResult()

            ;

    }

}
