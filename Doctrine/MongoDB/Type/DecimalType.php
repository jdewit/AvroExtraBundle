<?php
namespace Wardell\ExtraBundle\Doctrine\MongoDB\Type;

use Doctrine\ODM\MongoDB\Mapping\Types\Type;

class DecimalType extends Type
{
    public function convertToDatabaseValue($value)
    {
		$value = intval(round($value, 2) * 100);

        return $value !== null ? (integer) $value : 0;
    }

    public function convertToPHPValue($value)
    {
        return $value !== null ? number_format((integer) $value / 100, 2, '.','') : 0;
    }

    public function closureToMongo()
    {
        return '$return = (int) round($value, 2) * 100;';
    }

    public function closureToPHP()
    {
        return '$return = number_format((int) $value / 100, 2, ".", "");';
    }
}
