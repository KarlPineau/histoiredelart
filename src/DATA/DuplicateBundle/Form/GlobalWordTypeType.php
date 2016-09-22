<?php

namespace DATA\DuplicateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GlobalWordTypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordTypes',     'collection',   array(       'type' => new WordTypeType(),
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
            'data_class' => 'DATA\DuplicateBundle\Entity\GlobalWordType'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_duplicatebundle_globalwordtype';
    }
}
