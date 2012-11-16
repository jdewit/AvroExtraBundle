<?php
namespace Avro\ExtraBundle\Doctrine\MongoDB\Annotation;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * @Annotation
 */
class MoneyType extends AbstractField
{
    public $type = 'money';
}
