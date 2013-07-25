<?php

namespace Avro\ExtraBundle\Document\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait TimeStamps
{
    /**
     * @ODM\date
     */
    protected $createdAt;

    /**
     * @ODM\date
     */
    protected $updatedAt;

    /**
     * @ODM\Boolean
     */
    protected $isDeleted;

    /**
     * @ODM\date
     */
    protected $deletedAt;

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    public function setIsDeleted($isDeleted)
    {
        if ($isDeleted) {
            $this->deletedAt(new \DateTime('now'));
        }

        $this->isDeleted = $isDeleted;
        return $this;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }
}
