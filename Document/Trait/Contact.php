<?php

namespace Avro\ExtraBundle\Document\Trait;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait Contact
{
    /**
     * @ODM\String
     */
    protected $homePhone;

    /**
     * @ODM\String
     */
    protected $workPhone;

    /**
     * @ODM\String
     */
    protected $cellPhone;

    /**
     * @ODM\String
     */
    protected $fax;

    /**
     * @ODM\String
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $preferredContactMethod;


    public function getHomePhone()
    {
        return $this->homePhone;
    }

    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;
        return $this;
    }

    public function getWorkPhone()
    {
        return $this->workPhone;
    }

    public function setWorkPhone($workPhone)
    {
        $this->workPhone = $workPhone;
        return $this;
    }

    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;
        return $this;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    public function getPreferredContactMethod()
    {
        return $this->preferredContactMethod;
    }

    public function setPreferredContactMethod($preferredContactMethod)
    {
        $this->preferredContactMethod = $preferredContactMethod;
    }

}
