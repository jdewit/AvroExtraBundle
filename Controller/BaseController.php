<?php

namespace Avro\ExtraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class BaseController extends Controller
{

    /**
     * Create a new model.
     */
    public function baseNewAction(Request $request)
    {
        $model = $this->createModel();

        $form = $this->container->get(sprintf('%s.%s.form', $this->alias, $this->modelAlias));
        $form->setData($model);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                return $this->persistModel($form->getData());
            }
        }

        return array(
            'form' => $form->createview(),
        );
    }

    /**
     * Base edit action
     */
    public function baseEditAction(Request $request, $model)
    {
        $form = $this->container->get(sprintf('%s.%s.form', $this->alias, $this->modelAlias));
        $form->setData($model);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                return $this->updateModel($form->getData());
            }
        }

        return array(
            sprintf('%s', $this->modelAlias) => $model,
            'form' => $form->createview(),
        );
    }

    /**
     * Base delete action
     */
    public function baseDeleteAction($model)
    {
        $modelManager = $this->getModelManager();
        return $modelManager->delete($model);
    }

    protected function setFlash($action, $value)
    {
        $this->get('session')->setFlash($action, $value);
    }


    public function getModelManager()
    {
        return $this->get(sprintf('%s.%s.manager', $this->alias, $this->modelAlias));
    }

    public function createModel()
    {
        $manager = $this->getModelManager();

        $model = $manager->create();
        $this->dispatchEvent(sprintf('%s.%s.created', $this->alias, $this->modelAlias), $model);

        return $model;
    }

    public function persistModel($model)
    {
        $manager = $this->getModelManager();

        $this->dispatchEvent(sprintf('%s.%s.persist', $this->alias, $this->modelAlias), $model);

        $model = $manager->persist($model);

        $this->dispatchEvent(sprintf('%s.%s.persisted', $this->alias, $this->modelAlias), $model);

        return $model;
    }


    public function updateModel($model)
    {
        $manager = $this->getModelManager();

        $this->dispatchEvent(sprintf('%s.%s.update', $this->alias, $this->modelAlias), $model);
        $manager->update($model);
        $this->dispatchEvent(sprintf('%s.%s.updated', $this->alias, $this->modelAlias), $model);

        return $model;
    }

    public function deleteModel($model)
    {
        $manager = $this->getModelManager();

        $this->dispatchEvent(sprintf('%s.%s.delete', $this->alias, $this->modelAlias), $model);
        $manager->delete($model);
        $this->dispatchEvent(sprintf('%s.%s.deleted', $this->alias, $this->modelAlias), $model);

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
            $this->get('session')->getFlashBag()->set('error', sprintf('%s.%s.not_found', $this->alias, $this->modelAlias));

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
