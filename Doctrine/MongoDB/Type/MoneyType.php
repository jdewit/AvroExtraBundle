<?php
namespace Avro\ExtraBundle\Doctrine\MongoDB\Type;

use Doctrine\ODM\MongoDB\Mapping\Types\Type;

class MoneyType extends Type
{
    public function convertToDatabaseValue($value)
    {
        return $value !== null ? (integer) $value * 100 : null;
    }

    public function convertToPHPValue($value)
    {
        return $value !== null ? (integer) $value / 100 : null;
    }

    public function closureToMongo()
    {
        return '$return = (int) $value * 100;';
    }

    public function closureToPHP()
    {
        return '$return = (int) $value / 100;';
    }

}
