<?php

namespace DATA\DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArtworkRegisterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet',              'text',     array('required' => true))
            ->add('sujetIcono',         'text',     array('required' => false))
            ->add('auteur',             'text',     array('required' => false))
            ->add('commanditaire',      'text',     array('required' => false))
            ->add('provenance',         'text',     array('required' => false))
            ->add('datation',           'text',     array('required' => false))
            ->add('mattech',            'text',     array('required' => false))
            ->add('dimensions',         'text',     array('required' => false))
            ->add('style',              'text',     array('required' => false))
            ->add('lieuDeConservation', 'text',     array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\DataBundle\Entity\Artwork'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_databundle_artwork_register';
    }
}
