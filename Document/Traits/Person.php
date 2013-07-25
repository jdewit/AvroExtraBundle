<?php

namespace Avro\ExtraBundle\Document\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait Person
{
    /**
     * @ODM\String
     */
    protected $firstName;

    /**
     * @ODM\String
     */
    protected $lastName;

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return \User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return \User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

	public function getFullName()
	{
		$name = sprintf('%s %s', $this->getFirstName(), $this->getLastName());
		if (!$this->getFirstName() && !$this->getLastName()) {
			$name = $this->getEmail();
		}

		return $name;
	}
}
