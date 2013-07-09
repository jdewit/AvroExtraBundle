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
abstract class BaseMongoDBManager extends BaseManager
{
    /**
     * Find by as array
     *
     * @param  array  $criterias
     * @param  array  $fields
     * @return object Document
     */
    public function findByAsArray($criterias, array $fields = array())
    {
        $qb = $this->om->createQueryBuilder($this->class);

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
