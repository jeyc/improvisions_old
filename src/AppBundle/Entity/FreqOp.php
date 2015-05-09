<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * FreqOp
 *
 * @ORM\Table("improvisions.freqops")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FreqOpRepository")
 */
class FreqOp
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

	
    /**
     * @var integer
     *
     * @ORM\Column(name="nbutils", type="integer", options={"default"=0})
     */
    private $nbutils;

	/**
     * @var \AppBundle\Entity\FreqOpMouvement[]
     * @ORM\OneToMany(targetEntity="FreqOpMouvement",mappedBy="freqop",cascade={"persist"})
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
     * Set libelle
     *
     * @param string $libelle
     * @return FreqOp
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
		$this->nbutils = 0;
    }

    /**
     * Add mouvements
     *
     * @param \AppBundle\Entity\FreqOpMouvement $mouvements
     * @return FreqOp
     */
    public function addedMouvement(\AppBundle\Entity\FreqOpMouvement $mouvements)
    {
        $this->mouvements[] = $mouvements;

        return $this;
    }

    /**
     * Remove mouvements
     *
     * @param \AppBundle\Entity\FreqOpMouvement $mouvements
     */
    public function removeMouvement(\AppBundle\Entity\FreqOpMouvement $mouvements)
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
		 * On nettoie les occurrences vides, 
		 * on fait la liste des comptes, 
		 * et on vérifie qu'il y ait au moins un compte au débit et un au crédit.
		 */	
		$comptes = array();
		$has_debit = false;
		$has_credit = false;
		
		foreach ($this->getMouvements() as $mouvement)
		{
			if (is_null($mouvement->getCompte()))
			{
				$this->removeMouvement($mouvement);
			} else {
				$compte = $mouvement->getCompte();
				if (!isset($comptes[$compte->__toString()]))
				{
					$comptes[$compte->__toString()] = 1;
				} else {
					$comptes[$compte->__toString()]++;
				}
				if ($mouvement->getType() == Mouvement::DEB)
				{
					$has_debit = true;
				} elseif ($mouvement->getType() == Mouvement::CRE) {
					$has_credit = true;
				}
			}
		}
		
		if (!$has_debit)
		{
			$context->addViolationAt(
				'freqop',
				'Il faut saisir au moins un compte au débit.',
				array(),
				null
			);
		
		}
		
		if (!$has_credit)
		{
			$context->addViolationAt(
				'freqop',
				'Il faut saisir au moins un compte au crédit.',
				array(),
				null
			);
		
		}
		
		foreach ($comptes as $compteString => $nb)
		{
			if ($nb > 1)
			{
				$context->addViolationAt(
					'freqop',
					'Le compte ' . $compteString . ' apparaît ' . $nb . ' fois.',
				array(),
				null
			);
			}
		}
		
		
		/* On s'arrête là, les autres contraintes peuvent être vérifiées au niveau de chaque mouvement */
		
    }

    /**
     * Set nbutils
     *
     * @param integer $nbutils
     * @return FreqOp
     */
    public function setNbutils($nbutils)
    {
        $this->nbutils = $nbutils;

        return $this;
    }

	/**
     * Incr nbutils
     *
     * @return FreqOp
     */
    public function incrNbutils()
    {
        $this->setNbutils($this->getNbutils() + 1);

        return $this;
    }

	
	
	
    /**
     * Get nbutils
     *
     * @return integer 
     */
    public function getNbutils()
    {
        return $this->nbutils;
    }

}
