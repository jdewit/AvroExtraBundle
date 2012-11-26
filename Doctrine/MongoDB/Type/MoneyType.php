<?php
namespace Avro\ExtraBundle\Doctrine\MongoDB\Type;

use Doctrine\ODM\MongoDB\Mapping\Types\Type;

class MoneyType extends Type
{
    public function convertToDatabaseValue($value)
    {
        return $value !== null ? (integer) $value * 100 : 0;
    }

    public function convertToPHPValue($value)
    {
        return $value !== null ? number_format((integer) $value / 100, 2, '.','') : 0;
    }

    public function closureToMongo()
    {
        return '$return = (int) $value * 100;';
    }

    public function closureToPHP()
    {
        return '$return = number_format((int) $value / 100, 2, ".", "");';
    }
}
