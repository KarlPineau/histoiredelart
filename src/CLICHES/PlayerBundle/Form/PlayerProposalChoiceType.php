<?php

namespace CLICHES\PlayerBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerProposalChoiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('playerProposalChoiceValues',   ChoiceType::class,   array(
                'choices' => array(),
                'multiple' => false,
                'expanded' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CLICHES\PlayerBundle\Entity\PlayerProposalChoice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cliches_player_playerproposalchoice_type';
    }
}
