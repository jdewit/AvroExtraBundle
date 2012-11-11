<?php
namespace Avro\ExtraBundle\Doctrine\MongoDB\Type;

use Doctrine\ODM\MongoDB\Mapping\Types\Type;

class DecimalType extends Type
{
    public function convertToDatabaseValue($value)
    {
        return $value * 100;
    }

    public function convertToPHPValue($value)
    {
        return $value / 100;
    }

    public function closureToPHP()
    {
        return '$return = (int) $value / 100;';
    }
}
