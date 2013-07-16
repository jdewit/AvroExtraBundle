<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Doctrine\Common\Manager;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Base Managing class for model managers
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
abstract class BaseManager // implements BaseManagerInterface
{
    /**
     * DocumentManager
     *
     * @var mixed
     */
    protected $om;

    /**
     * The class namespace
     *
     * @var mixed
     */
    protected $class;

    /**
     * Document repository
     *
     * @var mixed
     */
    protected $repository;

    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->class = $class;
        $this->repository = $om->getRepository($class);
    }

    /**
     * @return fully qualified class name
     */
    public function getClass()
    {
        return $this->class;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    /*
     * Flush the entity manager
     *
     * @param boolean $andClear Clears instances of this class from the entity manager
     */
    private function flush($andClear)
    {
        $this->om->flush();

        if ($andClear) {
            $this->om->clear($this->getClass());
        }
    }

    /**
     * Creates a Document
     *
     * @return object Document
     */
    public function create()
    {
        $class = $this->getClass();

        $model = new $class();

        return $model;
    }

    /**
     * Persist a Document
     *
     * @param object  $model
     * @param boolean $andFlush Flush om if true
     * @param boolean $andClear Clear om if true
     */
    public function persist($model, $andFlush = true, $andClear = false)
    {
        if (!is_object($model)) {
            throw new \InvalidArgumentException('Cannot persist a non object');
        }

        $this->om->persist($model);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return $model;
    }

    /**
     * Update a Document
     *
     * @param object  $model
     * @param boolean $andFlush Flush om if true
     * @param boolean $andClear Clear om if true
     */
    public function update($model, $andFlush = true, $andClear = false)
    {
        if (!is_object($model)) {
            throw new \InvalidArgumentException('Cannot persist a non object');
        }

        $this->om->persist($model);

        if ($andFlush) {
            $this->flush($andClear);
        }

        return $model;
    }

    /**
     * Delete a Document
     *
     * @param object $model
     */
    public function delete($model)
    {
        $this->om->remove($model);

        $this->om->flush();

        return true;
    }

//    /**
//     * Find one model by criteria
//     *
//     * @param  array  $criteria
//     * @return object Document
//     */
//    public function findOneBy(array $criteria)
//    {
//        $criteria = $this->filterCriteria($criteria);
//
//        $model = $this->repository->findOneBy($criteria);
//
//        if (!is_object($model)) {
//            throw new FlashException('notice', sprintf('Error finding %s. Please try again.', $this->name));
//        }
//
//        return $model;
//    }
//
//    /**
//     * Find one model by id
//     *
//     * @param  string $id
//     * @return object Document
//     */
//    public function find($id)
//    {
//        if (!$id) {
//            throw new \InvalidArgumentException('Id must be specified.');
//        }
//        $criteria['id'] = $id;
//
//        return $this->findOneBy($criteria);
//    }
//
//    /**
//     * Find models by criteria
//     *
//     * @param  array $criteria
//     * @param  array $orderBy
//     * @param  mixed $limit
//     * @param  mixed $offset
//     * @return array Documents
//     */
//    public function findBy(array $criteria, array $orderBy = array(), $limit = null, $offset = null)
//    {
//        $criteria = $this->filterCriteria($criteria);
//
//        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
//    }
//
//    /**
//     * Find all models
//     *
//     * @return array Documents
//     */
//    public function findAll()
//    {
//        return $this->repository->findAll();
//    }
//
//    /**
//     * Find all as array
//     *
//     * @param  array  $fields
//     * @return object Document
//     */
//    public function findAllAsArray($fields)
//    {
//        if ($this->dbDriver == 'mongodb') {
//            $qb = $this->getQueryBuilder();
//            foreach ($fields as $field) {
//                $qb->select($field);
//            }
//            $objects = $qb->hydrate(false)
//                ->getQuery()
//                ->execute();
//
//        } else {
//            throw new \Exception('driver not implemented');
//        }
//
//        return array_values($objects->toArray());
//    }
//
//    public function getQueryBuilder()
//    {
//        return $this->om->createQueryBuilder($this->class);
//    }
}
