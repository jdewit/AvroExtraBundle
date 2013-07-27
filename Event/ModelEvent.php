<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\ExtraBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ModelEvent extends Event
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @return model
     */
    public function getModel()
    {
        return $this->model;
    }


    /**
     * setRedirectRoute
     *
     * Allows for redirecting from inside a listener
     *
     * @param Request $request
     * @param string $redirectRoute
     */
    public function setRedirectRoute(Request $request, $redirectRoute)
    {
        $request->request->request->set('redirect_route', $redirectRoute);
    }
}
