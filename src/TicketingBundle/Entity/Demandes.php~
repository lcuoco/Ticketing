<?php

namespace TicketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TicketingUserBundle\Entity\Utilisateurs;

/**
 * Demandes
 *
 * @ORM\Table(name="demandes")
 * @ORM\Entity(repositoryClass="TicketingBundle\Repository\DemandesRepository")
 */
class Demandes
{
    /**

     * @ORM\ManyToOne(targetEntity="TicketingUserBundle\Entity\Utilisateurs")

     * @ORM\JoinColumn(nullable=false)

     */
    private $utilisateur;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="piecejointe", type="string", length=255 , nullable=false )
     */
    private $piecejointe;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;



    
    
    public function __construct()
    {
        $this->etat = "soumis";
        $this->date = new \Datetime();
    }



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set objet.
     *
     * @param string $objet
     *
     * @return Demandes
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet.
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Demandes
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set piecejointe.
     *
     * @param string $piecejointe
     *
     * @return Demandes
     */
    public function setPiecejointe($piecejointe)
    {
        $this->piecejointe = $piecejointe;

        return $this;
    }

    /**
     * Get piecejointe.
     *
     * @return string
     */
    public function getPiecejointe()
    {
        return $this->piecejointe;
    }

    /**
     * Set etat.
     *
     * @param string $etat
     *
     * @return Demandes
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat.
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Demandes
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }



    public function setUtilisateur(Utilisateurs $utilisateur)

    {

        $this->utilisateur = $utilisateur;


        return $this;

    }


    public function getUtilisateur()

    {

        return $this->utilisateur;

    }


}
