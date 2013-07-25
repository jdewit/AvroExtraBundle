<?php

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

namespace Avro\ExtraBundle\Document\Traits;

trait Address
{
    /**
     * @ODM\String
     */
    protected $address;

    /**
     * @ODM\String
     */
    protected $city;

    /**
     * @ODM\String
     */
    protected $state;

    /**
     * @ODM\String
     */
    protected $zipCode;

    /**
     * @ODM\String
     */
    protected $country;

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getFullAddress()
    {
        $address = '';

        if ($this->address) {
            $address .= $this->address;

            if ($this->city) {
                $address .= '<br />'.$this->city;

                if ($this->state) {
                    $address .= ', '.$this->state;
                }
            }

            if ($this->zipCode) {
                $address .= ', '.$this->zipCode;
            }

            if ($this->country) {
                $address .= '<br />'.$this->country;
            }
        }

        return $address;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
}