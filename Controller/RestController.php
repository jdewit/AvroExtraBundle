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
    public function validateData($data, $expectedFields)
    {
        if (is_array($data)) {
            foreach($expectedFields as $field) {
                if (!array_key_exists($field, $data)) {
                    throw new \Exception(sprintf('Missing expected field - %s -', $field, 400));
                }
            }
        } else {
            foreach($expectedFields as $field) {
                if (!property_exists($data, $field)) {
                    throw new \Exception(sprintf('Missing expected field - %s -', $field, 400));
                }
            }
        }

        return true;
    }

    public function validateModel($model)
    {
        $validator = $this->container->get('validator');

        if (count($errors = $validator->validate($model)) > 0) {
            throw new \Exception('Invalid data', 400);
        }

        return true;
    }

    public function unauthorizedResponse()
    {
        throw new \Exception('Not authorized', 403);
    }

    public function invalidDataResponse($errors)
    {

    }

    public function notFoundResponse($name)
    {
        return new JsonResponse(array(
            'code' => sprintf('%s_not_found', $name),
            'message' => 'Office not found.'
        ), 400);
    }
}
