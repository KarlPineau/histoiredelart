<?php

namespace DATA\ImportBundle\Form;

use DATA\DataBundle\Form\SourceType;
use DATA\ImageBundle\Form\ImageRegisterType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityImportType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type',  HiddenType::class,   array(  'mapped' => false))
            ->add('fields',     'collection',   array(  'type' => new EntityFieldType(),
                                                        'allow_add'    => true,
                                                        'allow_delete' => true,
                                                        'mapped' => false))
            ->add('sources',    'collection',   array(  'type' => new SourceType(),
                                                        'allow_add'    => true,
                                                        'allow_delete' => true,
                                                        'mapped' => false))
            ->add('teachings',      'entity',   array(  'class' => 'DATATeachingBundle:Teaching',
                                                        'property' => 'name',
                                                        'required' => true,
                                                        'multiple' => true,
                                                        'choice_label' => function($teaching) {
                                                            return $teaching->getName();
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
                                                        'empty_value' => 'Choisissez une matière ...',
                                                        'mapped' => false))
            ->add('image',    ImageRegisterType::class,   array(  'mapped' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\DataBundle\Entity\Entity'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_import_entityimport';
    }
}
