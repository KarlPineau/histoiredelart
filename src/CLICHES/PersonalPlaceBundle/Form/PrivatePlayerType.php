<?php

namespace CLICHES\PersonalPlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PrivatePlayerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',                  'text',                 array('required' => true))
            ->add('simpleSession',          CheckboxType::class,    array('required' => false, 'label' => 'Partie examen'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cliches_personalplacebundle_privateplayer';
    }
}
