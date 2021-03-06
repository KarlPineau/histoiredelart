<?php

namespace TB\ModelBundle\Form;

use DATA\ImageBundle\Form\FileImageType;
use DATA\ImageBundle\Form\ImageRegisterType;
use DATA\ImageBundle\Form\ImageType;
use DATA\ImageBundle\Form\ImageWithoutViewRegisterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestedGameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          'text',                 array('required' => true))
            ->add('isRandomized',   CheckboxType::class,    array('label'    => 'Ordre aléatoire autorisé (les images seront mélangées)',
                                                                  'required' => false))
            ->add('isPrivate',      CheckboxType::class,    array('label'    => 'Partie privée (non référencée sur le site)',
                                                                  'required' => false))
            ->add('testedItems',    CollectionType::class,  array('entry_type' => TestedItemType::class,
                                                                  'allow_add' => true,
                                                                  'allow_delete' => true))
            ->add('icon',           ImageWithoutViewRegisterType::class,  array('required' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TB\ModelBundle\Entity\TestedGame'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tb_modelbundle_testedgame';
    }
}
