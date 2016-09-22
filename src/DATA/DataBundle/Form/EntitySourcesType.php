<?php

namespace DATA\DataBundle\Form;

use DATA\DataBundle\Form\SourceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntitySourcesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sources',     'collection',   array(  'type' => new SourceType(),
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
            'data_class' => 'DATA\DataBundle\Entity\EntitySources'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_databundle_entitysources';
    }
}
