<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use AppBundle\Entity\Ecriture;

/**
 * Operation
 *
 * @ORM\Table(name="improvisions.operations")
 * @ORM\Entity
 */
class Operation
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
	 * @Assert\NotBlank(message="operation.date.not_blank")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
	 * @Assert\NotBlank(message="operation.libelle.not_blank")
     */
    private $libelle;

	/**
     * @var Ecriture[]
     * @ORM\OneToMany(targetEntity="Ecriture",mappedBy="operation",cascade={"persist"})
     */
    private $ecritures;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Operation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Operation
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
     * Constructor
     */
    public function __construct()
    {
        $this->ecritures = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ecritures
     *
     * @param \AppBundle\Entity\Ecriture $ecritures
     * @return Operation
     */
    public function addEcriture(\AppBundle\Entity\Ecriture $ecritures)
    {
        $this->ecritures[] = $ecritures;
		$ecritures->setOperation($this);
		
        return $this;
    }

    /**
     * Remove ecritures
     *
     * @param \AppBundle\Entity\Ecriture $ecritures
     */
    public function removeEcriture(\AppBundle\Entity\Ecriture $ecritures)
    {
        $this->ecritures->removeElement($ecritures);
    }

    /**
     * Get ecritures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEcritures()
    {
        return $this->ecritures;
    }
	
	 /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
		$ecritures = $this->getEcritures();
		$has_debit = false;
		$has_credit = false;
		$balance = 0;
		foreach ($ecritures as $ecriture)
		{
			if (is_null($ecriture->getCompte())
				&& is_null($ecriture->getMontant())
			)
			{
				$this->removeEcriture($ecriture);
			}
			if (!is_null($ecriture->getType())
				&& !is_null($ecriture->getMontant()))
			{
				if ($ecriture->getType() === Ecriture::DEB)
				{
					$has_debit = true;
					$balance -= $ecriture->getMontant();
				}
				if ($ecriture->getType() === Ecriture::CRE)
				{
					$has_credit = true;
					$balance += $ecriture->getMontant();
				}
			}
		}
		if (!$has_debit)
			{
				$context->buildViolation('operation.no_debit')
                ->atPath('ecritures')
                ->addViolation();
			}
			if (!$has_credit)
			{
				$context->buildViolation('operation.no_credit')
                ->atPath('ecritures')
                ->addViolation();
			}
			if (0 != $balance)
			{
				$context->buildViolation('operation.balance_not_nul')
                ->atPath('ecritures')
                ->addViolation();
			}
		
    }
}
