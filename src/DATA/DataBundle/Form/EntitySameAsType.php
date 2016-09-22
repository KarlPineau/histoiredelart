<?php

namespace DATA\DataBundle\Form;

use DATA\DataBundle\Form\SameAsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntitySameAsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sameAs',     'collection',   array(  'type' => new SameAsType(),
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
            'data_class' => 'DATA\DataBundle\Entity\EntitySameAs'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_databundle_entitysameas';
    }
}
