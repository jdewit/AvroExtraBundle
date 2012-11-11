<?php
namespace Avro\AssetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimeType extends AbstractType
{
    public function getParent()
    {
        return 'purified_text';
    }

    public function getName()
    {
        return 'avro_time';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'label' => 'Time',
            'attr' => array(
                'title' => 'Select a time',
                'class' => 'timepicker'
            )
        );
    }

}
