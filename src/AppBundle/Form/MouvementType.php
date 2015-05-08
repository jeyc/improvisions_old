<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use AppBundle\Entity\Mouvement;

class MouvementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
				'choices' => array(Mouvement::DEB => 'Débit', Mouvement::CRE => 'Crédit'),
			))
            ->add('montant', null, array('required' => false))
            ->add('compte', null, array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Mouvement'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_mouvement';
    }
}
