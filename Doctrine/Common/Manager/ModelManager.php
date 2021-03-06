<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Doctrine\Common\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Base Managing class for model managers
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
abstract class ModelManager implements ModelManagerInterface
{
    /**
     * DocumentManager
     */
    protected $om;

    /**
     * EventDispatcher
     */
    protected $dispatcher;

    /**
     * The class namespace
     */
    protected $class;

    /**
     * Doctrine repository
     */
    protected $repository;

    /**
     * bundleAlias
     */
    protected $bundleAlias;

    /**
     * modelAlias
     */
    protected $modelAlias;

    /**
     * criteria
     */
    protected $criteria = array();

    public function __construct(ObjectManager $om, $dispatcher, $class, $eventClass = 'Avro\ExtraBundle\Event\ModelEvent')
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
        $this->class = $class;
        $this->eventClass = $eventClass;
        $this->repository = $om->getRepository($class);

        $paths = explode('\\', $class);

        $this->bundleAlias = sprintf('%s_%s', lcfirst($paths[0]), lcfirst(str_replace('Bundle', '', $paths[1])));
        $this->modelAlias = lcfirst($paths[3]);
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

    /**
     * dispatchEvent
     *
     * @param string $action
     * @param Object $model
     */
    public function dispatchEvent($action, $model)
    {
        $this->dispatcher->dispatch(sprintf('%s.%s.%s', $this->bundleAlias, $this->modelAlias, $action), new $this->eventClass($model));
    }

    /*
     * Flush the entity manager
     *
     * @param boolean $andClear Clears instances of this class from the entity manager
     */
    public function flush($andClear = false)
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

        $this->dispatchEvent('post_create', $model);

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

        $this->dispatchEvent('pre_persist', $model);

        $this->om->persist($model);

        if ($andFlush) {
            $this->flush($andClear);
        }

        $this->dispatchEvent('post_persist', $model);

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

        $this->dispatchEvent('pre_update', $model);

        $this->om->persist($model);

        if ($andFlush) {
            $this->flush($andClear);
        }

        $this->dispatchEvent('post_update', $model);

        return $model;
    }

    /**
     * Delete a Document
     *
     * @param object $model
     * @param boolean $andFlush Flush ObjectManager
     */
    public function delete($model, $andFlush = true)
    {
        $this->dispatchEvent('pre_delete', $model);

        $this->om->remove($model);

        if ($andFlush) {
            $this->om->flush();
        }

        $this->dispatchEvent('post_delete', $model);

        return true;
    }

    /**
     * Find one model by criteria
     *
     * @param  array  $criteria
     * @param  boolean $suppressException
     * @return object Document
     */
    public function findOneBy(array $criteria, $suppressException = false)
    {
        $criteria = array_merge($criteria, $this->getCriteria());

        $model = $this->repository->findOneBy($criteria);

        if ($suppressException !== true && !is_object($model)) {
            throw new NotFoundHttpException(sprintf('%s not found', $this->modelAlias));
        }

        return $model;
    }

    /**
     * Find one model by id
     *
     * @param  string $id
     * @param  boolean $suppressException
     * @return object Document
     */
    public function find($id, $suppressException = false)
    {
        if (!$id) {
            throw new \InvalidArgumentException('Id must be specified.');
        }

        return $this->findOneBy(array('id' => $id), $suppressException);
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * Find models by criteria
     *
     * @param  array $criteria
     * @param  array $orderBy
     * @param  mixed $limit
     * @param  mixed $offset
     * @return array Documents
     */
    public function findBy(array $criteria, array $orderBy = array(), $limit = null, $offset = null)
    {
        $criteria = array_merge($criteria, $this->getCriteria());

        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Find all models
     *
     * @return array Documents
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function getQueryBuilder()
    {
        return $this->om->createQueryBuilder($this->class);
    }

    public function detach($model)
    {
        $this->om->detach($model);
    }

    public function clear()
    {
        $this->om->clear($this->getClass());
    }

}
