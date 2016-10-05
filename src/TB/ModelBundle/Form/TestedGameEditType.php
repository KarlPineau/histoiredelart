<?php

namespace TB\ModelBundle\Form;

use DATA\ImageBundle\Form\ImageWithoutViewRegisterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestedGameEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          'text',                 array('required' => true))
            ->add('isRandomized',   CheckboxType::class,    array('label'    => 'Ordre aléatoire autorisé',
                                                                  'required' => false))
            ->add('isPrivate',      CheckboxType::class,    array('label'    => 'Partie privée (non référencée sur le site)',
                                                                  'required' => false))
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
