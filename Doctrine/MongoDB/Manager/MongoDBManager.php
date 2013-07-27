<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Doctrine\MongoDB\Manager;

use Avro\ExtraBundle\Doctrine\Common\Manager\BaseManager;

/**
 * Base Managing class for document managers
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class MongoDBManager extends BaseManager
{
    public function __construct($om, $dispatcher, $class)
    {
        parent::__construct($om, $dispatcher, $class);
    }

    /**
     * getQueryBuilder
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->om->createQueryBuilder($this->class);
    }

    /**
     * Find by as array
     *
     * @param  array  $criterias
     * @param  array  $fields
     * @return object Document
     */
    public function findByAsArray($criterias, array $fields = array())
    {
        $qb = $this->getQueryBuilder();

        foreach ($criterias as $k => $v) {
            $qb->field($k)->equals($v);
        }

        foreach ($fields as $field) {
            $qb->select($field);
        }

        $objects = $qb->hydrate(false)
            ->getQuery()
            ->execute();

        return array_values($objects->toArray());
    }
}
