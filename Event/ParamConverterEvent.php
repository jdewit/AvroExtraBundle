<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ParamConverterEvent extends Event
{
    private $request;
    private $configuration;

    public function __construct(Request $request, $configuration)
    {
        $this->request = $request;
        $this->configuration = $configuration;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }
}

