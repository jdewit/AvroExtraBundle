<?php

namespace Avro\ExtraBundle\Doctrine\Common\Manager;

/**
 * BaseManager Interface
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 * @license For the full copyright and license information, please view the LICENSE
 */
interface ModelManagerInterface
{
    /**
     * @return className
     */
    public function getClass();

    /**
     * Get Doctrine Repository
     *
     * @return Repository
     */
    public function getRepository();

    public function dispatchEvent($action, $model);

    /**
     * Creates an Entity/Document
     *
     * @return object Document
     */
    public function create();

    /**
     * Persist a Document
     *
     * @param object  $document
     * @param boolean $andFlush Flush om if true
     * @param boolean $andClear Clear om if true
     */
    public function persist($document, $andFlush = true, $andClear = false);

    /**
     * Update a Document
     *
     * @param object  $document
     * @param boolean $andFlush Flush om if true
     * @param boolean $andClear Clear om if true
     */
    public function update($document, $andFlush = true, $andClear = false);

    /**
     * Delete a Document
     *
     * @param object $document
     */
    public function delete($document);

    /**
     * Find one document by criteria
     *
     * @param  array  $criteria
     * @return object Document
     */
    public function findOneBy(array $criteria);

    /**
     * Find one document by id
     *
     * @param  string $id
     * @return object Document
     */
    public function find($id);

    /**
     * Find documents by criteria
     *
     * @param  array $criteria
     * @param  array $orderBy
     * @param  mixed $limit
     * @param  mixed $offset
     * @return array Documents
     */
    public function findBy(array $criteria, array $orderBy = array('name' => 'asc'), $limit = null, $offset = null);

    /**
     * Find all documents
     *
     * @return array Documents
     */
    public function findAll();

    /**
     * Get Doctrine QueryBuilder
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder();
}
