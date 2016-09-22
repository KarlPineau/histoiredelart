<?php

namespace CLICHES\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class HomeLoadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('teaching',               EntityType::class,   array(
                                                        'class' => 'DATATeachingBundle:Teaching',
                                                        'choice_label' => function($teaching) use($options) {
                                                            return $teaching->getName().' ('.count($options['attr']['entity_service']->getByTeaching($teaching, 'restrict')).' oeuvres)';
                                                        },
                                                        'group_by' => function($teaching) {
                                                            return $teaching->getYear().'e année';
                                                        },
                                                        'query_builder' => function (EntityRepository $er) {
                                                            return $er->createQueryBuilder('t')
                                                                      ->where('t.onLine = :true')
                                                                      ->setParameters(array('true' => true))
                                                                      ->orderBy('t.year', 'ASC')
                                                                      ->orderBy('t.name', 'ASC');

                                                        },
                                                        'property' => 'name',
                                                        'required' => true,
                                                        'empty_value' => 'Sélectionnez une discipline ...',
                                                        'mapped' => false))
            ->add('mode',             ChoiceType::class, array(
                                                'choices'  =>  array('modeChoice' => 'Normale', 'modeField' => 'Élevée', 'modeTest' => 'Examen'),
                                                'preferred_choices' => array('choice'),
                                                'required' => true,
                                                'expanded' => true,
                                                'multiple' => false
                                            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cliches_homebundle_home_load';
    }
}
