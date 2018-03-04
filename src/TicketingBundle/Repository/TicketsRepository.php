<?php

namespace TicketingBundle\Repository;

/**
 * TicketsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TicketsRepository extends \Doctrine\ORM\EntityRepository
{
    public function myFindOnea($id)

    {

        $qb = $this->createQueryBuilder('t');


        $qb
            ->join('t.utilisateur', 'u')->addSelect('u')
            ->join('t.demande', 'd')->addSelect('d')
            ->where("d.etat = 'attribue'")

            ->andWhere('t.utilisateur = :id')



            ->setParameter('id', $id)

        ;


        return $qb

            ->getQuery()

            ->getResult()

            ;

    }
    public function myFindOnetick($id)

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

    public function myFindOneticka($id)

    {

        $qb = $this->createQueryBuilder('t');


        $qb
            ->join('t.utilisateur', 'u')->addSelect('u')
            ->join('t.demande', 'd')->addSelect('d')
            ->where('d.id = :id')

            ->setParameter('id', $id)

        ;


        return $qb

            ->getQuery()

            ->getResult()

            ;

    }
}
