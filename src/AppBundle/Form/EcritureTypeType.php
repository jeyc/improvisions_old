<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Entity\Ecriture;

class EcritureTypeType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                Ecriture::DEB => 'ecriture.type.deb',
                Ecriture::CRE => 'ecriture.type.cre',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'appbundle_ecriture_type';
    }
}

?>
