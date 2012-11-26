<?php
namespace Avro\ExtraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'Image',
                'attr' => array(
                    'title' => 'Enter the path for the image'
                )
            ))
            ->add('isUpdated', 'hidden', array(
                'empty_data' => true
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Avro\ExtraBundle\Entity\Image'
        ));
    }

    public function getName()
    {
        return 'avro_asset_image';
    }
}
