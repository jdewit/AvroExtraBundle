<?php

namespace Avro\ExtraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class BaseController extends Controller
{

    protected function setFlash($action, $value)
    {
        $this->get('session')->setFlash($action, $value);
    }
}

