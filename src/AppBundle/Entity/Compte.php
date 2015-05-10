<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Compte
 *
 * @ORM\Table("improvisions.comptes",uniqueConstraints={@ORM\UniqueConstraint(columns={"numero"})})
 * @ORM\Entity()
 * @UniqueEntity(fields={"numero"},message="Ce compte existe déjà")
 */
class Compte
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=6)
	 * @Assert\NotBlank(message="Veuillez saisir un numéro de compte")
	 * @Assert\Type(type="digit",message="Le numéro de compte doit être composé de chiffres exclusivement")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
	 * @Assert\NotBlank(message="Veuillez saisir un libellé")
     */
    private $libelle;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Compte
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Compte
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
	
	/**
     * To String
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->getNumero() . ' - ' . $this->getLibelle();
    }
	
}
