<?php

namespace Avro\ExtraBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class RestExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();

        if ($request->isXmlHttpRequest()) {
            $exception = $event->getException();

            $response = new JsonResponse(array(
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ), 400) ;

            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }
}