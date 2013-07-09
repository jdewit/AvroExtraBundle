<?php
namespace Wardell\ExtraBundle\Doctrine\MongoDB\Annotation;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * @Annotation
 */
class DecimalType extends AbstractField
{
    public $type = 'decimal';
    public $precision = 2;
 
    public function getPrecision()
    {
        return $this->precision;
    }	
}
