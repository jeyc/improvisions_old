<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EcritureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('compte', 'entity', array(
				'class' => 'AppBundle\Entity\Compte',
				'query_builder' => function(EntityRepository $er) {
					return $er->createQueryBuilder('c')
						->where('c.mouvementable = TRUE')
						->orderBy('c.code', 'ASC');
				},
				'required' => false,
			))
            ->add('type', new EcritureTypeType(), array('required' => false, 'placeholder' => false))
            ->add('montant', 'money', array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ecriture'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_ecriture';
    }
}
