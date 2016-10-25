<?php

namespace TB\ModelBundle\Form;

use DATA\ImageBundle\Form\ImageWithoutViewRegisterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestedItemEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('itemLabel',      'text',     array('required' => true, 'label' => 'Étiquette'))
            ->add('itemOrder',      'integer',  array('required' => true, 'label' => 'Ordre d\'affichage'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TB\ModelBundle\Entity\TestedItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tb_modelbundle_testeditem';
    }
}
