<?php

namespace Avro\ExtraBundle\Document\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait Contact
{
    /**
     * @ODM\String
     */
    protected $phone;

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
     * @ODM\String
     */
    protected $preferredContactMethod;

	public function getGravatarUrl()
	{
		return $gravUrl = 'http://www.gravatar.com/avatar/' . md5( strtolower( trim( $this->email ) ) ) . '?d=mm&s=16&r=PG';
	}

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
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

    public function getContactInfo()
    {
        $contactInfo = '';

        if ($this->phone) {
            $contactInfo .= $this->phone;
        };

        if ($this->cellPhone) {
            $contactInfo .= $this->cellPhone;
        }

        if ($this->workPhone) {
            $contactInfo .= $this->workPhone;
        }

        if ($this->fax) {
            $contactInfo .= $this->fax;
        }

        if ($this->website) {
            $contactInfo .= $this->website;
        }

        return $contactInfo;
    }
}
