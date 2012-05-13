<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Avro\ExtraBundle\Form\DataTransformer\OneEntityToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\FormException;

class DynamicEntityType extends AbstractType
{
    protected $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->prependClientTransformer(new OneEntityToIdTransformer(
            $this->registry->getEntityManager($options['em']),
            $options['class'], 
            $options['property'],
            $options['query_builder']
        ));
    }

    public function getDefaultOptions()
    {
        return array(
            'required'          => true,
            'em'                => null,
            'class'             => null,
            'query_builder'     => null,
            'property'          => null,
            'hidden' => true
        );
    }

    public function getParent(array $options)
    {
        return 'choice';
    }

    public function getName()
    {
        return 'dynamic_entity';
    }
}

