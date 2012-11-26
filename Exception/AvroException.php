<?php

namespace Avro\ExtraBundle\Exception;

/**
 * Custom exception class
 */
class AvroException extends \Exception
{
    protected $type;

    public function __construct($type, $message, $code = 0) {
        $this->type = $type;

        parent::__construct($message, $code = 0, null);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getType()
    {
        return $this->type;
    }
}
