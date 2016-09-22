<?php

namespace DATA\DataBundle\Form;

use DATA\ImageBundle\Form\ViewType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityViewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('views',     'collection',   array(  'type' => new ViewType(),
                                                        'allow_add'    => true,
                                                        'allow_delete' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\DataBundle\Entity\EntityViews'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_databundle_entityviews';
    }
}
