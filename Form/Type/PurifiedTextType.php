<?php

namespace Avro\AssetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\DataTransformerInterface;

class PurifiedTextType extends AbstractType
{
    private $purifierTransformer;

    public function __construct(DataTransformerInterface $purifierTransformer)
    {
        $this->purifierTransformer = $purifierTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->appendClientTransformer($this->purifierTransformer);
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'purified_text';
    }
}
