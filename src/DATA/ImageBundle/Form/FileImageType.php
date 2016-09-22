<?php

namespace DATA\ImageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile',  'vich_image',   array(
                                                        'required'      => true,
                                                        'allow_delete'  => false, // not mandatory, default is true
                                                        'download_link' => true, // not mandatory, default is true
                                                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DATA\ImageBundle\Entity\FileImage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_imagebundle_fileimage';
    }
}
