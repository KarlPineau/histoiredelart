<?php

namespace TB\ModelBundle\Form;

use DATA\ImageBundle\Form\ImageWithoutViewRegisterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestedGameIconEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
