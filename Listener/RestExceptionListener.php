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
    //        $message = sprintf(
    //        'My Error says: %s with code: %s',
    //        $exception->getMessage(),
    //        $exception->getCode()
    //        );
    //
    //        // Customize your response object to display the exception details
    //        $response = new Response();
    //        $response->setContent($message);
    //
    //        // HttpExceptionInterface is a special type of exception that
    //        // holds status code and header details
    //        if ($exception instanceof HttpExceptionInterface) {
    //        $response->setStatusCode($exception->getStatusCode());
    //        $response->headers->replace($exception->getHeaders());
    //        } else {
    //        $response->setStatusCode(500);
    //        }
    //
    //        // Send the modified response object to the event
            $event->setResponse($response);
        }
    }
}