<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;



/**
 * Operation
 *
 * @ORM\Table("improvisions.operations")
 * @ORM\Entity()
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
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

	
	/**
     * @var \AppBundle\Entity\Mouvement[]
     * @ORM\OneToMany(targetEntity="Mouvement",mappedBy="operation",cascade={"persist"})
     */
    private $mouvements;


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
        $this->mouvements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add mouvements
     *
     * @param \AppBundle\Entity\Mouvement $mouvements
     * @return Operation
     */
    public function addedMouvement(\AppBundle\Entity\Mouvement $mouvements)
    {
        $this->mouvements[] = $mouvements;

        return $this;
    }

    /**
     * Remove mouvements
     *
     * @param \AppBundle\Entity\Mouvement $mouvements
     */
    public function removeMouvement(\AppBundle\Entity\Mouvement $mouvements)
    {
        $this->mouvements->removeElement($mouvements);
    }

    /**
     * Get mouvements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMouvements()
    {
        return $this->mouvements;
    }
	
	/**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
	
		/**
		 * On nettoie les occurrences de mouvement vides et on compte les non vides.
		 */	
		$count = 0;
        foreach ($this->getMouvements() as $mouvement)
		{
			if (is_null($mouvement->getCompte())
				&& is_null($mouvement->getMontant()))
			{
				$this->removeMouvement($mouvement);
			} else {
				$count++;
			}
		}
		
		if ($count < 2)
		{
			$context->addViolationAt(
				'operation',
				'Il faut saisir au moins deux mouvements : l\'un au débit, l\'autre au crédit.',
				array(),
				null
			);
		}

		/* On vérifie la balance */
		$balance = 0;
		foreach ($this->getMouvements() as $mouvement)
		{
			if (!is_null($mouvement->getMontant()))
			{
				$balance += $mouvement->getType() == Mouvement::DEB ? ( - $mouvement->getMontant()) : $mouvement->getMontant();
			}
		}
		
		if ($balance != 0)
		{
				$context->addViolationAt(
					'mouvement',
					'La balance n\'est pas nulle.',
					array(),
					null
				);
		}
		
		/* On s'arrête là, les autres contraintes peuvent être vérifiées au niveau de chaque mouvement */
		
    }
}
