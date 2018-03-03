<?php
// src/OC/UserBundle/DataFixtures/ORM/LoadUser.php

namespace TicketingBundle\TicketingUserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TicketingUserBundle\Entity\Utilisateurs;

class LoadUtilisateurs implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Les noms d'utilisateurs à créer


            $user = new Utilisateurs();

            $name = 'Alexandre';
            // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
            $user->setUsername($name);
            $user->setPassword($name);

            // On ne se sert pas du sel pour l'instant
            $user->setSalt('');
            // On définit uniquement le role ROLE_USER qui est le role de base
            $user->setRoles(array('ROLE_ADMIN'));

            // On le persiste
            $manager->persist($user);


        // On déclenche l'enregistrement
        $manager->flush();

        $user = new Utilisateurs();

        $name = 'Lucas';
        // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
        $user->setUsername($name);
        $user->setPassword($name);

        // On ne se sert pas du sel pour l'instant
        $user->setSalt('');
        // On définit uniquement le role ROLE_USER qui est le role de base
        $user->setRoles(array('ROLE_USER'));

        // On le persiste
        $manager->persist($user);


        // On déclenche l'enregistrement
        $manager->flush();

        // On déclenche l'enregistrement
        $manager->flush();

        $user = new Utilisateurs();

        $name = 'Marie';
        // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
        $user->setUsername($name);
        $user->setPassword($name);

        // On ne se sert pas du sel pour l'instant
        $user->setSalt('');
        // On définit uniquement le role ROLE_USER qui est le role de base
        $user->setRoles(array('ROLE_TECH'));

        // On le persiste
        $manager->persist($user);


        // On déclenche l'enregistrement
        $manager->flush();
    }
}