<?php

namespace Avro\ExtraBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;

/*
 * Bundle
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class AvroExtraBundle extends Bundle
{
    public function __construct()
    {
        Type::registerType('decimal', 'Avro\ExtraBundle\Doctrine\MongoDB\Type\DecimalType');
    }

//    public function boot()
//    {
//        if ($this->container->getParameter('avro_extra.db_driver') == 'mongodb') {
//        }
//    }

}
