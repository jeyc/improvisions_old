<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use AppBunle\Entity;

/**
 * Ecriture
 *
 * @ORM\Table(name="improvisions.ecritures", uniqueConstraints={@ORM\UniqueConstraint(columns={"operation_id","compte_id"})})
 * @ORM\Entity
 */
class Ecriture
{
	
	const DEB = 1;
	const CRE = 2;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Operation
     *
     * @ORM\ManyToOne(targetEntity="Operation",inversedBy="ecritures")
	 * @ORM\JoinColumn(nullable=false,onDelete="cascade")
     */
    private $operation;

    /**
     * @var Compte
     * @ORM\ManyToOne(targetEntity="Compte")
	 * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="ecriture.compte.not_blank")
     */
    private $compte;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     * @Assert\NotBlank(message="ecriture.type.not_blank")
	 * @Assert\Choice(choices = {Ecriture::DEB, Ecriture::CRE},message="ecriture.type.deb_or_cre")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", scale=2)
	 * @Assert\NotBlank(message="ecriture.montant.not_blank")
	 * @Assert\NotEqualTo(value=0,message="ecriture.montant.not_zero")
     */
    private $montant;


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
     * Set type
     *
     * @param integer $type
     * @return Ecriture
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return Ecriture
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }


    /**
     * Set operation
     *
     * @param \AppBundle\Entity\Operation $operation
     * @return Ecriture
     */
    public function setOperation(\AppBundle\Entity\Operation $operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return \AppBundle\Entity\Operation 
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set compte
     *
     * @param \AppBundle\Entity\Compte $compte
     * @return Ecriture
     */
    public function setCompte(\AppBundle\Entity\Compte $compte)
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get compte
     *
     * @return \AppBundle\Entity\Compte 
     */
    public function getCompte()
    {
        return $this->compte;
    }
}
