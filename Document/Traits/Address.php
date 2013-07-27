<?php

namespace Avro\ExtraBundle\Document\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

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
    protected $code;

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

            if ($this->code) {
                $address .= ', '.$this->code;
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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
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
