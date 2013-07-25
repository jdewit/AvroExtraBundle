<?php

namespace Avro\ExtraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * The models event class
     *
     * @var string
     */
    protected $eventClass = 'Avro\ExtraBundle\Event\ModelEvent';

    /**
     * dbDriver
     *
     * @var string
     */
    protected $dbDriver = 'mongodb';

    /**
     * The modelManager service id
     *
     * The default modelManager is used if set to false
     *
     * @var string
     */
    protected $modelManager = false;

    /**
     * List models.
     *
     * @Template
     */
    public function baseListAction(Request $request)
    {
        return $this->get('templating')->renderResponse($this->getTemplate('list'), array(
           'pagination' => $this->get('knp_paginator')->paginate(
                               $this->getModelManager()->getQueryBuilder()->hydrate(false)->getQuery(),
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
    public function baseNewAction(Request $request)
    {
        $model = $this->createModel();

        $form = $this->getForm($model);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->persistModel($form->getData());

                return $this->redirect($this->generateUrl(sprintf('%s_%s_list', $this->bundleAlias, $this->modelAlias)));
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
    public function baseEditAction(Request $request, $id)
    {
        $model = $this->getModel($id);

        $form = $this->getForm($model);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->updateModel($form->getData());

                return $this->redirect($this->generateUrl(sprintf('%s_%s_list', $this->bundleAlias, $this->modelAlias)));
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

        $model = $modelManager->getRepository()->find($id);

        $modelManager->delete($model);

        return $this->redirect($this->generateUrl(sprintf('%s_%s_list', $this->bundleAlias, $this->modelAlias)));
    }

    protected function setFlash($action, $value)
    {
        $this->get('session')->setFlash($action, $value);
    }

    private function getTemplate($name)
    {
        return sprintf('%sBundle:%s:%s.html.twig', str_replace(' ', '', ucwords(str_replace('_', ' ', $this->bundleAlias))), ucfirst($this->modelAlias), $name);
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
        return sprintf('%sBundle\\Document\\%s', str_replace(' ', '\\', ucwords(str_replace('_', ' ', $this->bundleAlias))), ucfirst($this->modelAlias));
    }

    /**
     * getModelManager
     *
     * @return $modelManager
     */
    public function getModelManager()
    {
        if ($this->modelManager) {
            return $this->get($this->modelManager);
        } else {
            switch ($this->dbDriver) {
                case 'mongodb':
                    $objectManager = 'doctrine.odm.mongodb.document_manager';
                    $modelManagerClass = 'Avro\ExtraBundle\Document\Manager\DocumentManager';
                    break;
                case 'orm':
                    $objectManager = 'doctrine.orm.entity_manager';
                    $modelManagerClass = 'Avro\ExtraBundle\ORM\Manager\EntityManager';
                    break;

            }

            return new $modelManagerClass($this->get($objectManager), $this->getModelClass());
        }
    }

    public function getForm($model)
    {
        $formType = sprintf('%sBundle\\Form\\Type\\%sFormType', str_replace(' ', '\\', ucwords(str_replace('_', ' ', $this->bundleAlias))), ucfirst($this->modelAlias));

        return $this->createForm(new $formType($this->getModelClass(), $model));
    }

    public function createModel()
    {
        $manager = $this->getModelManager();

        $model = $manager->create();
        $this->dispatchEvent(sprintf('%s.%s.created', $this->bundleAlias, $this->modelAlias), $model);

        return $model;
    }

    public function persistModel($model)
    {
        $manager = $this->getModelManager();

        $this->dispatchEvent(sprintf('%s.%s.persist', $this->bundleAlias, $this->modelAlias), $model);

        $model = $manager->persist($model);

        $this->dispatchEvent(sprintf('%s.%s.persisted', $this->bundleAlias, $this->modelAlias), $model);

        return $model;
    }


    public function updateModel($model)
    {
        $manager = $this->getModelManager();

        $this->dispatchEvent(sprintf('%s.%s.update', $this->bundleAlias, $this->modelAlias), $model);
        $manager->update($model);
        $this->dispatchEvent(sprintf('%s.%s.updated', $this->bundleAlias, $this->modelAlias), $model);

        return $model;
    }

    public function deleteModel($model)
    {
        $manager = $this->getModelManager();

        $this->dispatchEvent(sprintf('%s.%s.delete', $this->bundleAlias, $this->modelAlias), $model);
        $manager->delete($model);
        $this->dispatchEvent(sprintf('%s.%s.deleted', $this->bundleAlias, $this->modelAlias), $model);

        return true;
    }

    public function dispatchEvent($name, $model)
    {
        $event = new $this->eventClass($model);

        $this->get('event_dispatcher')->dispatch($name, $event);
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
