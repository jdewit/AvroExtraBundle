<?php

namespace Avro\ExtraBundle\Document\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait Slug
{
	/**
     * @Gedmo\Slug(fields={"name"}, unique=false)
     * @ODM\String
     */
    protected $slug;

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
}
