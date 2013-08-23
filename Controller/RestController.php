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
 * REQUIREMENTS
 * ------------
 *
 * You must set the following variables in the child controller
 *
 * $modelAlias
 * $bundleAlias
 *
 *
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class RestController extends CommonController
{

    public function unauthorizedResponse()
    {
        return new JsonResponse(array(
            'message' => 'You are not authorized to perform this action'
        ), 403);
    }

    public function handleDeleteRequest($id)
    {
        $modelManager = $this->getModelManager();

        $model = $modelManager->find($id);

        if (empty($model)) {
            return new JsonResponse(array(
                'message' => sprintf('%s not found', ucfirst($this->modelName))
            ), 400);
        }

        $modelManager->delete($model);

        return new JsonResponse(array(
            'message' => sprintf('%s deleted.', ucfirst($this->modelName))
        ));
    }

}