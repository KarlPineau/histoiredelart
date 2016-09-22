<?php

namespace CLICHES\PlayerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerProposalChoiceValueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value',      'text',     array('required' => false))
            ->add('field',      'text',     array('required' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cliches_player_playerproposalchoicevalue_type';
    }
}
