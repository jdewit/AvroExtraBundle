<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Doctrine\Common\Manager;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Avro\ExtraBundle\Exception\FlashException;

/**
 * Base Managing class for document managers
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
abstract class BaseManager implements BaseManagerInterface
{
    /**
     * DocumentManager
     *
     * @var mixed
     */
    protected $om;

    /**
     * dispatcher
     *
     * @var mixed
     */
    protected $dispatcher;

    /**
     * context
     *
     * @var mixed
     */
    protected $context;

    /**
     * The class namespace
     *
     * @var mixed
     */
    protected $class;

    /**
     * The bundle alias
     *
     * @var mixed
     */
    protected $alias;

    /**
     * The document name
     *
     * @var mixed
     */
    protected $name;

    /**
     * The documents event class namespace
     *
     * @var mixed
     */
    protected $eventClass;

    /**
     * Document repository
     *
     * @var mixed
     */
    protected $repository;

    public function __construct(ObjectManager $om, EventDispatcherInterface $dispatcher, SecurityContextInterface $context, $class, $alias, $name, $eventClass)
    {
        $this->om = $om;
        $this->dispatcher = $dispatcher;
        $this->context = $context;
        $this->class = $class;
        $this->alias = $alias;
        $this->name = $name;
        $this->eventClass = $eventClass;
        $this->repository = $om->getRepository($class);
    }

    /**
     * @return fully qualified class name
     */
    public function getClass()
    {
        return $this->class;
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

        $document = new $class();

           $this->dispatcher->dispatch(sprintf('%s.%s.create', $this->alias, $this->name), new $this->eventClass($document));

        return $document;
    }

    /**
     * Persist a Document
     *
     * @param object  $document
     * @param boolean $andFlush Flush om if true
     * @param boolean $andClear Clear om if true
     */
    public function persist($document, $andFlush = true, $andClear = false)
    {
        if (!is_object($document)) {
            throw new \InvalidArgumentException('Cannot persist a non object');
        }

           $this->dispatcher->dispatch(sprintf('%s.%s.persist', $this->alias, $this->name), new $this->eventClass($document));

        $document = $this->customize($document);

        $this->om->persist($document);

        if ($andFlush) {
            $this->flush($andClear);
        }

           $this->dispatcher->dispatch(sprintf('%s.%s.persisted', $this->alias, $this->name), new $this->eventClass($document));

        return $document;
    }

    /**
     * Update a Document
     *
     * @param object  $document
     * @param boolean $andFlush Flush om if true
     * @param boolean $andClear Clear om if true
     */
    public function update($document, $andFlush = true, $andClear = false)
    {
        if (!is_object($document)) {
            throw new \InvalidArgumentException('Cannot persist a non object');
        }

        $this->dispatcher->dispatch(sprintf('%s.%s.update', $this->alias, $this->name), new $this->eventClass($document));

        $document = $this->customize($document);

        $this->om->persist($document);

        if ($andFlush) {
            $this->flush($andClear);
        }

        $this->dispatcher->dispatch(sprintf('%s.%s.updated', $this->alias, $this->name), new $this->eventClass($document));

        return $document;
    }

    /**
     * Delete a Document
     *
     * @param object $document
     */
    public function delete($document)
    {
        $this->dispatcher->dispatch(sprintf('%s.%s.delete', $this->alias, $this->name), new $this->eventClass($document));

        $this->om->remove($document);

        $this->om->flush();

        $this->dispatcher->dispatch(sprintf('%s.%s.deleted', $this->alias, $this->name), new $this->eventClass($document));

        return true;
    }

    /**
     * Customize the object before it is persists/updated
     *
     * @param object $document
     */
    public function customize($document)
    {
        return $document;
    }

    /**
     * Find one document by criteria
     *
     * @param  array  $criteria
     * @return object Document
     */
    public function findOneBy(array $criteria)
    {
        $criteria = $this->filterCriteria($criteria);

        $document = $this->repository->findOneBy($criteria);

        if (!is_object($document)) {
            throw new FlashException('notice', sprintf('Error finding %s. Please try again.', $this->name));
        }

        return $document;
    }

    /**
     * Find one document by id
     *
     * @param  string $id
     * @return object Document
     */
    public function find($id)
    {
        if (!$id) {
            throw new \InvalidArgumentException('Id must be specified.');
        }
        $criteria['id'] = $id;

        return $this->findOneBy($criteria);
    }

    /**
     * Find documents by criteria
     *
     * @param  array $criteria
     * @param  array $orderBy
     * @param  mixed $limit
     * @param  mixed $offset
     * @return array Documents
     */
    public function findBy(array $criteria, array $orderBy = array(), $limit = null, $offset = null)
    {
        $criteria = $this->filterCriteria($criteria);

        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Find all documents
     *
     * @return array Documents
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * Find all as array
     *
     * @param  array  $fields
     * @return object Document
     */
    public function findAllAsArray($fields)
    {
        if ($this->dbDriver == 'mongodb') {
            $qb = $this->getQueryBuilder();
            foreach ($fields as $field) {
                $qb->select($field);
            }
            $objects = $qb->hydrate(false)
                ->getQuery()
                ->execute();

        } else {
            throw new \Exception('driver not implemented');
        }

        return array_values($objects->toArray());
    }

    public function getQueryBuilder()
    {
        return $this->om->createQueryBuilder($this->class);
    }
}
