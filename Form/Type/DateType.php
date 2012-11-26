<?php
namespace Avro\ExtraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateType extends AbstractType
{
    public function getParent()
    {
        return 'date';
    }

    public function getName()
    {
        return 'avro_date';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'label' => 'Date',
            //'input' => 'string',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr' => array(
                'title' => 'Select a date',
            )
        );
    }
}
