<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Compte
 *
 * @ORM\Table(name="improvisions.comptes", uniqueConstraints={@ORM\UniqueConstraint(columns={"code"})})
 * @ORM\Entity
 * @UniqueEntity(fields="code", message="compte.code.unique")
 */
class Compte
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=6)
     * @Assert\NotBlank(message="compte.code.not_blank")
	 * @Assert\Regex(pattern="^[1-9][0-9]{5,5}^", message="compte.code.regex")
	*/
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
	 * @Assert\NotBlank(message="compte.libelle.not_blank")
     */
    private $libelle;

	/**
     * @var boolean
     *
     * @ORM\Column(name="mouvementable", type="boolean", options={"default"=false})
	 * @Assert\Type(type="bool")
     */
    private $mouvementable;


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
     * Set code
     *
     * @param string $code
     * @return Compte
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
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
     * Set mouvementable
     *
     * @param boolean $mouvementable
     * @return Compte
     */
    public function setMouvementable($mouvementable)
    {
        $this->mouvementable = $mouvementable;

        return $this;
    }

    /**
     * Get mouvementable
     *
     * @return boolean 
     */
    public function getMouvementable()
    {
        return $this->mouvementable;
    }
}
