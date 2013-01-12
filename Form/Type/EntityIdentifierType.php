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

use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistent\ObjectManager;
use Avro\ExtraBundle\Form\DataTransformer\OneEntityToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * EntityIdentifierType
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class EntityIdentifierType extends AbstractType
{
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     *
     * @return void
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->prependClientTransformer(new OneEntityToIdTransformer(
            $this->objectManager->getEntityManager($options['em']),
            $options['class'],
            $options['property'],
            $options['query_builder']
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required'          => true,
            'em'                => null,
            'class'             => null,
            'query_builder'     => null,
            'property'          => null,
            'hidden' => true
        ));
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'entity_id';
    }
}

