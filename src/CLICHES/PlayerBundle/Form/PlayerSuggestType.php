<?php

namespace CLICHES\PlayerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PlayerSuggestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('playerSuggestContent',     'text',     array(
                                                                    'required' => false
                                                                ))

        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cliches_playerbundle_playersuggest_simple';
    }
}
