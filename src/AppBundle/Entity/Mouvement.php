<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mouvement
 *
 * @ORM\Table("improvisions.mouvements")
 * @ORM\Entity
 */
class Mouvement
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
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
	 * @Assert\NotBlank(message="Champ obligatoire")
     * @Assert\Choice(choices = {Mouvement::DEB, Mouvement::CRE}, message = "Le type de l'opération ne peut être que 'débit' ou 'crédit'")
	 */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", scale=2)
	 * @Assert\NotBlank(message="Champ obligatoire")
	 * @Assert\GreaterThan(
     *     value = 0
     * )*/
    private $montant;

	/**
     * @var \AppBundle\Entity\Compte
     * @ORM\ManyToOne(targetEntity="Compte")
	 * @ORM\JoinColumn(nullable=false)
	 * @Assert\NotBlank(message="Champ obligatoire")
     */
    private $compte;

	/**
     * @var \AppBundle\Entity\Operation
     * @ORM\ManyToOne(targetEntity="Operation",inversedBy="mouvements")
	 * @ORM\JoinColumn(nullable=false,onDelete="cascade")
     */
    private $operation;
	

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
     * @return Mouvement
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
     * @return Mouvement
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
     * Set compte
     *
     * @param \AppBundle\Entity\Compte $compte
     * @return Mouvement
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

    /**
     * Set operation
     *
     * @param \AppBundle\Entity\Operation $operation
     * @return Mouvement
     */
    public function setOperation(\AppBundle\Entity\Operation $operation)
    {
        $this->operation = $operation;
		$operation->addedMouvement($this);

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
}
