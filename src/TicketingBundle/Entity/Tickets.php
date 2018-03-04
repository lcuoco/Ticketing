<?php

namespace TicketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tickets
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="TicketingBundle\Repository\TicketsRepository")
 */
class Tickets
{
    /**

     * @ORM\ManyToOne(targetEntity="TicketingUserBundle\Entity\Utilisateurs")

     * @ORM\JoinColumn(nullable=true)

     */
    private $utilisateur;

    /**

     * @ORM\OneToOne(targetEntity="TicketingBundle\Entity\Demandes", cascade={"persist"})

     */
    private $demande;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="delai", type="integer")
     */
    private $delai;

    /**
     * @var bool
     *
     * @ORM\Column(name="urgence", type="boolean")
     */
    private $urgence;

    /**
     * @var string|null
     *
     * @ORM\Column(name="compterendu", type="string", length=255, nullable=true)
     */
    private $compterendu;


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
     * Set delai.
     *
     * @param int $delai
     *
     * @return Tickets
     */
    public function setDelai($delai)
    {
        $this->delai = $delai;

        return $this;
    }

    /**
     * Get delai.
     *
     * @return int
     */
    public function getDelai()
    {
        return $this->delai;
    }

    /**
     * Set urgence.
     *
     * @param bool $urgence
     *
     * @return Tickets
     */
    public function setUrgence($urgence)
    {
        $this->urgence = $urgence;

        return $this;
    }

    /**
     * Get urgence.
     *
     * @return bool
     */
    public function getUrgence()
    {
        return $this->urgence;
    }

    /**
     * Set compterendu.
     *
     * @param string|null $compterendu
     *
     * @return Tickets
     */
    public function setCompterendu($compterendu = null)
    {
        $this->compterendu = $compterendu;

        return $this;
    }

    /**
     * Get compterendu.
     *
     * @return string|null
     */
    public function getCompterendu()
    {
        return $this->compterendu;
    }

    /**
     * Set utilisateur.
     *
     * @param \TicketingUserBundle\Entity\Utilisateurs|null $utilisateur
     *
     * @return Tickets
     */
    public function setUtilisateur(\TicketingUserBundle\Entity\Utilisateurs $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur.
     *
     * @return \TicketingUserBundle\Entity\Utilisateurs|null
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set demande.
     *
     * @param \TicketingBundle\Entity\Demandes|null $demande
     *
     * @return Tickets
     */
    public function setDemande(\TicketingBundle\Entity\Demandes $demande = null)
    {
        $this->demande = $demande;

        return $this;
    }

    /**
     * Get demande.
     *
     * @return \TicketingBundle\Entity\Demandes|null
     */
    public function getDemande()
    {
        return $this->demande;
    }
}
