<?php

namespace DATA\ImageBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ViewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isPlan',             CheckboxType::class,    array(  'label'=> 'Plan ?',
                                                                        'required' => false))
            ->add('vue',                TextType::class,        array(  'required' => false))
            ->add('title',              TextType::class,        array(  'required' => false))
            ->add('iconography',        TextType::class,        array(  'required' => false))
            ->add('location',           TextType::class,        array(  'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\ImageBundle\Entity\View'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_imagebundle_view';
    }
}
