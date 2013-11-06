<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Doctrine\MongoDB\Manager;

use Avro\ExtraBundle\Doctrine\Common\Manager\ModelManager;

/**
 * Base Managing class for document managers
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class MongoDBManager extends ModelManager
{
    public function __construct($om, $dispatcher, $class)
    {
        parent::__construct($om, $dispatcher, $class);
    }

    /**
     * Find by as array
     *
     * @param  array  $criterias
     * @param  array  $fields
     * @param  boolean $idAsKey Keep the id as the key of the array item
     * @param  function or boolean $callback Callback function for customizing array
     * items
     *
     * @return array
     */
    public function findByAsArray($criterias, array $fields = array(), $idAsKey = false, $callback = false)
    {
        $qb = $this->getQueryBuilder();

        foreach ($criterias as $k => $v) {
            $qb->field($k)->equals($v);
        }

        foreach ($fields as $field) {
            $qb->select($field);
        }

        $arr = $qb->hydrate(false)
                  ->getQuery()
                  ->execute()
                  ->toArray();

        foreach($arr as $k => $v) {
            unset($arr[$k]['_id']);
            $arr[$k]['id'] = $k;

            if ($callback) {
                $arr[$k] = $callback($arr[$k]);
            }
        }

        if ($idAsKey === false) {
            $arr = array_values($arr);
        }

        return $arr;
    }
}
