<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Doctrine\ORM\Manager;

use Avro\ExtraBundle\Doctrine\Common\Manager\BaseManager;

/**
 * Base ORM Managing class
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
abstract class BaseORMManager extends BaseManager
{
    /**
     * Find by as array
     *
     * @param  array  $criterias
     * @param  array  $fields
     * @return object Document
     */
    public function findByAsArray($criterias, $fields = 'e')
    {
        $qb = $this->om->createQueryBuilder();
        $qb->from($this->getClass(), 'e');

        $qb->select($fields);

        foreach ($criterias as $k => $v) {
            $qb->where(sprintf('e.%s = :%s', $k, $k));
            $qb->setParameter($k, $v);
        }

        $query = $qb->getQuery();

        return $query->getArrayResult();
    }
}
