<?php

namespace CLICHES\PlayerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerProposalLoadChoiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('playerProposalChoices',   'collection',   array( 'type' => new PlayerProposalChoiceType(),
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
            'data_class' => 'CLICHES\PlayerBundle\Entity\PlayerProposal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cliches_player_playerproposal_loadchoice_type';
    }
}
