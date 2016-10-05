<?php

namespace DATA\PersonalPlaceBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserSessionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('privatePlayers',      EntityType::class,   array(
                                                                'class' => 'CLICHESPersonalPlaceBundle:PrivatePlayer',
                                                                'choice_label' => 'title',
                                                                'query_builder' => function (EntityRepository $er) use($options) {
                                                                    return $er->createQueryBuilder('p')
                                                                        ->where('p.createUser = :user')
                                                                        ->setParameters(array('user' => $options['attr']['user']));
                                                                },
                                                                'required' => true,
                                                                'empty_value' => 'Ajouter à ma partie',
                                                                'mapped' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\PersonalPlaceBundle\Entity\UserSessions'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_personalplacebundle_usersessions';
    }
}
