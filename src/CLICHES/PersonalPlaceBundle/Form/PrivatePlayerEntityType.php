<?php

namespace CLICHES\PersonalPlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PrivatePlayerEntityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entity',         EntityType::class,      array(
                'class' => 'DATADataBundle:Entity',
                'choice_label' => 'id',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerEntity'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cliches_personalplacebundle_privateplayerentity';
    }
}
