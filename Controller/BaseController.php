<?php

namespace Avro\ExtraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
class BaseController extends Controller
{
    /**
     * The default number of items to show in the list view
     *
     * @var int
     */
    protected $listCount = 10;

    /**
     * dbDriver
     *
     * @var string
     */
    protected $dbDriver = 'mongodb';

    /**
     * modelName
     */
    protected $modelName = false;

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

        return $this->get('templating')->renderResponse($this->getTemplate('list'), array(
            'modelAlias' => $modelAlias,
            'pagination' => $this->get('knp_paginator')->paginate(
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
    public function baseNewAction(Request $request, $prePersistCallback = false, $postPersistCallback = false, $formErrorCallback = false)
    {
        $modelManager = $this->getModelManager();
        $model = $modelManager->create();

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

        return $this->get('templating')->renderResponse($this->getTemplate('new'), array(
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

    /**
     * getModel
     *
     * @param string $id
     * @return $model
     */
    protected function getModel($id)
    {
        return $this->getModelManager()->getRepository()->find($id);
    }

    /**
     * getModelClass
     *
     * @return Model class name
     */
    private function getModelClass()
    {
        if ($this->modelName) {
            return sprintf('%sBundle\\Document\\%s', str_replace(' ', '\\', ucwords(str_replace('_', ' ', $this->bundleAlias))), ucfirst($this->modelName));
        } else {
            return sprintf('%sBundle\\Document\\%s', str_replace(' ', '\\', ucwords(str_replace('_', ' ', $this->bundleAlias))), ucfirst($this->modelAlias));
        }
    }

    /**
     * getModelManager
     *
     * @return $modelManager
     */
    public function getModelManager()
    {
        $modelManagerAlias = sprintf('%s.%s.manager', $this->bundleAlias, $this->modelAlias);

        if ($this->container->has($modelManagerAlias)) {
            return $this->get($modelManagerAlias);
        } else {
            switch ($this->dbDriver) {
                case 'mongodb':
                    $objectManager = 'doctrine.odm.mongodb.document_manager';
                    $modelManagerClass = 'Avro\ExtraBundle\Doctrine\MongoDB\Manager\MongoDBManager';
                    break;
                case 'orm':
                    $objectManager = 'doctrine.orm.entity_manager';
                    $modelManagerClass = 'Avro\ExtraBundle\ORM\Manager\EntityManager';
                    break;

            }

            return new $modelManagerClass($this->get($objectManager), $this->get('event_dispatcher'), $this->getModelClass());
        }
    }

    public function getForm($model)
    {
        if (!$this->formTypeClass) {
            $this->formTypeClass = sprintf('%sBundle\\Form\\Type\\%sFormType', str_replace(' ', '\\', ucwords(str_replace('_', ' ', $this->bundleAlias))), $this->modelName ? $this->modelName : ucfirst($this->modelAlias));
        }

        return $this->createForm(new $this->formTypeClass($this->getModelClass()), $model);
    }

    /**
     * getFormErrors
     *
     * @param Form $form
     * @return array
     */
    private function getFormErrors(Form $form) {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getFormErrors($child);
                }
            }
        }
        return $errors;
    }

    public function getPagination($request, $query) {
        return $this->get('knp_paginator')->paginate($query, $request->query->get('page', 1), $request->query->get('count', $this->listCount));
    }

    /**
     * getBundleShortName
     *
     * @return string
     */
    private function getBundleShortName()
    {
        return sprintf('%s%s', ucfirst(str_replace('_', '', $this->bundleAlias)), ucfirst($this->modelAlias));
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

    public function findModel($id)
    {
        $model = $this->getModelManager()->getRepository()->find($id);

        if (!$model) {
            $this->get('session')->getFlashBag()->set('error', sprintf('%s.%s.not_found', $this->bundleAlias, $this->modelAlias));

            return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_list'));
        }

        return $model;
    }

    public function findModelBySlug($slug)
    {
        $model = $this->getModelManager()->getRepository()->findOneBy(array('slug' => $slug));

        return $model;
    }
}
