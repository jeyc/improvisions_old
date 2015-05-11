<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CompteCollection
 *
 */
class CompteCollection
{

	/**
     * @var ArrayCollection
     */
    private $comptes;

	public function __construct()
	{
		$this->comptes = new ArrayCollection();
	}
	
	public function addCompte(Compte $compte)
	{
		$this->comptes[] = $compte;
		
		return $this;
	}
	
	public function removeCompte(Compte $compte)
	{
		$this->comptes->removeElement($compte);
		
		return $this;
	}
	
	public function getComptes()
	{
			return $this->comptes;
	}
	
	/**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
	{
		$comptes = array();
		
		foreach ($this->getComptes() as $compte)
		{
			if (is_null($compte->getNumero()) && is_null($compte->getLibelle()))
			{
				$this->removeCompte($compte);
			}
			if (!is_null($compte->getNumero()))
			{
				if (in_array($compte->getNumero(), $comptes))
				{
					$context->addViolationAt(
						'comptes',
						'Vous avez entré le code ' . $compte->getNumero() . ' plusieurs fois.',
						array(),
						null
					);
				} else {
					$comptes[] = $compte->getNumero();
				}
			} 
		}
	}
}
