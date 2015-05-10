<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Compte
 *
 * @ORM\Table("improvisions.comptes",uniqueConstraints={@ORM\UniqueConstraint(columns={"numero"})})
 * @ORM\Entity()
 * @UniqueEntity(fields={"numero"},message="Le compte existe déjà")
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
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
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
