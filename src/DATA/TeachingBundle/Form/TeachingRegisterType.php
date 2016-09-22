<?php

namespace DATA\TeachingBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeachingRegisterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',              'text',              array('required' => true))
            ->add('year',              'integer',           array('required' => true))
            ->add('onLine',            CheckboxType::class, array('required' => false))
            ->add('university',        EntityType::class,   array(  'class' => 'DATATeachingBundle:University',
                                                                    'choice_label' => 'name',
                                                                    'required' => true,
                                                                    'empty_value' => 'Université'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\TeachingBundle\Entity\Teaching'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_teaching_teaching_register';
    }
}
