<?php

namespace DATA\DuplicateBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;

class WordTypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('word',       'text',             array('required' => true))
            ->add('context',    'text',             array('required' => true))
            ->add('type',       EntityType::class,  array(  'class' => 'DATADuplicateBundle:Type',
                                                            'choice_label' => 'name',
                                                            'required' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\DuplicateBundle\Entity\WordType'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_duplicatebundle_wordtype';
    }
}
