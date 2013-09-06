<?php

namespace Avro\ExtraBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Form;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * A base controller class that makes prototyping controllers easy
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
class BaseController extends CommonController
{
    /**
     * The default number of items to show in the list view
     *
     * @var int
     */
    protected $listCount = 10;



    /**
     * formTypeClass
     */
    protected $formTypeClass = false;

    /**
     * redirectRoute
     */
    protected $redirectRoute = false;

    /**
     * template
     */
    protected $template = false;

    /**
     * useSessions
     */
    protected $disableFlashMessages = false;

    /**
     * List models.
     *
     * @Template
     */
    public function baseListAction(Request $request, $query = false, $modelAlias = false)
    {
        if (false === $modelAlias) {
            $modelAlias = $this->modelAlias;
        }

        if (!$query) {
            $query = $this->getModelManager()->getQueryBuilder()->hydrate(false)->getQuery();
        }

        return $this->container->get('templating')->renderResponse($this->getTemplate('list'), array(
            'modelAlias' => $modelAlias,
            'pagination' => $this->container->get('knp_paginator')->paginate(
                                $query,
                                $request->query->get('page', 1),
                                $this->listCount
                           )
            )
        );
    }

    /**
     * Create a new model.
     *
     */
    public function baseNewAction(Request $request, $prePersistCallback = false, $postPersistCallback = false, $preSetCallback = false)
    {
        $modelManager = $this->getModelManager();
        $model = $modelManager->create();

        if ($preSetCallback) {
            $model = $preSetCallback($model);
        }

        $form = $this->getForm($model);

        if ('POST' === $request->getMethod() || 'PUT' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $model = $form->getData();

                if ($prePersistCallback) {
                    $model = $prePersistCallback($model, $form);
                }

                $modelManager->persist($model);

                $this->addFlash('success', 'new');

                if ($postPersistCallback) {
                    return $postPersistCallback($model, $form);
                } else {
                    if ($request->isXmlHttpRequest()) {
                        $response = new Response(json_encode(array(
                            'status' => 'SUCCESS',
                            'message' => $this->get('translator')->trans(sprintf('%s.new.flash.success', $this->modelAlias), array(), $this->getBundleShortName()),
                            'data' => $model->toArray()
                        )));

                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
                    } else {
                        return $this->resolveRedirect('list');
                    }
                }
            } else {
                if ($request->isXmlHttpRequest()) {
                    $response = new Response(json_encode(array(
                        'status' => 'ERROR',
                        'errors' => $this->getFormErrors($form),
                        'form_name' => sprintf('%s_%s', $this->bundleAlias, $this->modelName ? lcfirst($this->modelName) : $this->modelAlias)
                    )));

                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                } else {
                    $this->addFlash('error', 'edit');
                }
            }
        }

        return $this->container->get('templating')->renderResponse($this->getTemplate('new'), array(
            'form' => $form->createview(),
            $this->modelAlias => $model
        ));
    }

    /**
     * Base edit action
     */
    public function baseEditAction(Request $request, $id, $prePersistCallback = false, $postPersistCallback = false)
    {
        $model = $this->getModel($id);

        if (empty($model)) {
            throw new NotFoundHttpException(sprintf('%s not found. Please try again.', $this->modelAlias));
        }

        $form = $this->getForm($model);

        if ('POST' === $request->getMethod() || 'PATCH' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $model = $form->getData();

                if ($prePersistCallback) {
                    $model = $prePersistCallback($model, $form);
                }

                $this->getModelManager()->update($model);

                $this->addFlash('success', 'edit');

                if ($postPersistCallback) {
                    return $postPersistCallback($model, $form);
                } else {
                    if ($request->isXmlHttpRequest()) {
                        $response = new Response(json_encode(array(
                            'status' => 'SUCCESS',
                            'message' => $this->get('translator')->trans(sprintf('%s.new.flash.success', $this->modelAlias), array(), $this->getBundleShortName()),
                            'data' => $model->toArray()
                        )));

                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
                    } else {
                        return $this->resolveRedirect('list');
                    }
                }
            } else {
                $this->addFlash('error', 'edit');
            }
        }

        return $this->get('templating')->renderResponse($this->getTemplate('edit'), array(
            sprintf('%s', $this->modelAlias) => $model,
            'form' => $form->createview(),
        ));
    }

    /**
     * Base delete action
     * @Template
     */
    public function baseDeleteAction(Request $request, $id)
    {
        $modelManager = $this->getModelManager();

        $model = $modelManager->find($id);

        try {
            $modelManager->delete($model);

            $this->addFlash('success', 'delete');
        } catch(\Exception $e) {
            $this->addFlash('error', 'delete');
        }

        return $this->redirect($this->generateUrl(sprintf('%s_%s_list', $this->bundleAlias, $this->modelAlias)));
    }

    private function getTemplate($name)
    {
        return $this->template ? $this->template : sprintf('%sBundle:%s:%s.html.twig', str_replace(' ', '', ucwords(str_replace('_', ' ', $this->bundleAlias))), $this->modelName ? $this->modelName : ucfirst($this->modelAlias), $name);
    }


    public function getForm($model)
    {
        if (!$this->formTypeClass) {
            $this->formTypeClass = sprintf('%sBundle\\Form\\Type\\%sFormType', str_replace(' ', '\\', ucwords(str_replace('_', ' ', $this->bundleAlias))), $this->modelName ? $this->modelName : ucfirst($this->modelAlias));
        }

        return $this->createForm(new $this->formTypeClass($this->getModelClass()), $model);
    }



    public function getPagination($request, $query) {
        return $this->get('knp_paginator')->paginate($query, $request->query->get('page', 1), $request->query->get('count', $this->listCount));
    }


    /**
     * addFlash
     *
     * @param string $status The flash status
     * @param string $alias The flash alias
     */
    private function addFlash($status, $alias) {
        if (!$this->disableFlashMessages) {
            $this->get('session')->getFlashBag()->add(
                $status,
                $this->get('translator')->trans(sprintf('%s.%s.flash.%s', $this->modelAlias, $alias, $status)), array(), $this->getBundleShortName()
            );
        }
    }

    private function resolveRedirect($action, array $routeParameters = array())
    {
        $redirectRoute = $this->get('request')->request->get('redirect_route');

        if (empty($redirectRoute)) {
            return $this->redirect($this->generateUrl(sprintf('%s_%s_%s', $this->bundleAlias, $this->modelAlias, $action)));
        } else {
            return $this->redirect($this->generateUrl($redirectRoute));
        }
    }

}
