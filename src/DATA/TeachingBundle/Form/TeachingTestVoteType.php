<?php

namespace DATA\TeachingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeachingTestVoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vote', ChoiceType::class, array('choices'  => array(
                                                        true => "Oui",
                                                        false => "Non",
                                                    ),
                                                    'expanded' => true,
                                                    'multiple' => false,
                                                    'required' => false,
                                                    'empty_data' => null,
                                                    'empty_value' => null,))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\TeachingBundle\Entity\TeachingTestVote'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_teachingbundle_teachingtestvote';
    }
}
