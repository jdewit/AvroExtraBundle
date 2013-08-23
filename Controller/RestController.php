<?php

namespace Avro\ExtraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Form;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * A base rest controller
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class RestController extends CommonController
{
    public function unauthorizedResponse()
    {
        return new JsonResponse(array(
            'code' => 'unauthorized',
            'message' => 'You are not authorized to perform this action'
        ), 403);
    }

    public function invalidDataResponse($errors)
    {
        return new JsonResponse(array(
            'code' => 'invalid_data',
            'errors' => $errors
        ), 400) ;
    }

    public function notFoundResponse($name)
    {
        return new JsonResponse(array(
            'code' => sprintf('%s_not_found', $name),
            'message' => 'Office not found.'
        ), 400);
    }
}
