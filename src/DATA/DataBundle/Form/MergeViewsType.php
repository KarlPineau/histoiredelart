<?php

namespace DATA\DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class MergeViewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('view',      EntityType::class,   array(
                                                        'class' => 'DATAImageBundle:View',
                                                        'choice_label' => 'orderView',
                                                        'query_builder' => function (EntityRepository $er) use($options) {
                                                            return $er->createQueryBuilder('v')
                                                                    ->where('v.entity = :entity AND v.id != :id')
                                                                    ->setParameters(array('entity' => $options['attr']['entity'],
                                                                                          'id' => $options['attr']['currentView_id']));
                                                            
                                                        },
                                                        'required' => true,
                                                        'empty_value' => 'Sélectionner une vue ...',
                                                        'mapped' => false))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_data_entityviews_mergeviews';
    }
}
