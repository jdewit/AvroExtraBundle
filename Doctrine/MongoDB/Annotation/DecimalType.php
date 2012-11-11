<?php
namespace Avro\ExtraBundle\Doctrine\MongoDB\Annotation;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * @Annotation
 */
class DecimalType extends AbstractField
{
    public $type = 'decimal';
}
