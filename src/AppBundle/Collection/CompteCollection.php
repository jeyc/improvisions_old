<?php

namespace AppBundle\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\Compte;

/**
 * CompteCollection
 *
 */
class CompteCollection
{
    /**
     * @var Compte[]
     *
     */
    private $comptes;


	public function __construct()
	{
		$this->comptes = new ArrayCollection();
		
		return $this;
	}
	
	public function getComptes()
	{
		return $this->comptes;
	}
	
	public function addCompte(Compte $compte)
	{
		$this->comptes->add($compte);
	}


	public function removeCompte(Compte $compte)
	{
		$this->comptes->removeElement($compte);
	}
	
	/**
	 * @Assert\Callback
	 */
	public function validate(ExecutionContextInterface $context)
    {
		// On supprime tous les comptes non saisis
		// On invalide les doublons
		$codes = array();
		foreach ($this->getComptes() as $compte)
		{
			if (is_null($compte->getCode())
				&& is_null($compte->getLibelle())
			)
			{
				$this->removeCompte($compte);
			}
			if (!is_null($compte->getCode()))
			{
				if (in_array($compte->getCode(), $codes))
				{
					$context->addViolationAt(
						'comptes',
						'compte.collection.multiple_code',
						array('%code%' => $compte->getCode()),
						null
					);
				} else {
					$codes[] = $compte->getCode();
				}
			}
		}
		if (0 == count($this->getComptes()))
		{
			$context->addViolationAt(
				'comptes',
				'compte.collection.no_compte',
				array(),
				null
			);
			
		}
	}

}
