<?php

namespace CLICHES\PlayerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerProposalLoadFieldType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('playerProposalFields',   'collection',   array(  'type' => new PlayerProposalFieldType(),
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
        return 'cliches_player_playerproposal_loadfield_type';
    }
}
