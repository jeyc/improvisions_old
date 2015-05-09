<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


use AppBundle\Entity\Mouvement;


/**
 * FreqOpMouvement
 *
 * @ORM\Table("improvisions.freqopmouvements",uniqueConstraints={@ORM\UniqueConstraint(columns={"compte_id", "freqop_id"})})
 * @ORM\Entity
 */
class FreqOpMouvement
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
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
	 * @Assert\Choice(choices = {Mouvement::DEB, Mouvement::CRE}, message = "Le type de l'opération ne peut être que 'débit' ou 'crédit'")
     */
    private $type;

	/**
     * @var \AppBundle\Entity\Compte
     * @ORM\ManyToOne(targetEntity="Compte")
	 * @ORM\JoinColumn(nullable=false)
     */
    private $compte;
	
	
    /**
     * @var \AppBundle\Entity\FreqOp
     * @ORM\ManyToOne(targetEntity="FreqOp",inversedBy="mouvements")
	 * @ORM\JoinColumn(nullable=false,onDelete="cascade")
     */
    private $freqop;


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
     * @return FreqOpMouvement
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
     * Set compte
     *
     * @param \AppBundle\Entity\Compte $compte
     * @return FreqOpMouvement
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
     * Set freqop
     *
     * @param \AppBundle\Entity\FreqOp $freqop
     * @return FreqOpMouvement
     */
    public function setFreqop(\AppBundle\Entity\FreqOp $freqop)
    {
        $this->freqop = $freqop;
		$freqop->addedMouvement($this);

        return $this;
    }

    /**
     * Get freqop
     *
     * @return \AppBundle\Entity\FreqOp 
     */
    public function getFreqop()
    {
        return $this->freqop;
    }
	
}
