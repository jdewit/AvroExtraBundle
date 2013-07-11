<?php

namespace Avro\ExtraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class BaseController extends Controller
{


    protected function setFlash($action, $value)
    {
        $this->get('session')->setFlash($action, $value);
    }


    public function getModelManager()
    {
        return $this->get($this->modelManager);
    }

    public function createModel()
    {
        $manager = $this->getModelManager();

        $model = $manager->create();
        $this->dispatchEvent('created', $model);

        return $model;
    }

    public function persistModel($model)
    {
        $manager = $this->getModelManager();

        $this->dispatchEvent('persist', $model);

        $model = $manager->persist($model);

        $this->dispatchEvent('persisted', $model);

        return $model;
    }


    public function updateModel($resource)
    {
        $manager = $this->getManager();

        $this->dispatchEvent('pre_update', $resource);
        $manager->persist($resource);
        $this->dispatchEvent('update', $resource);
        $manager->flush();
        $this->dispatchEvent('post_update', $resource);
    }

    public function deleteModel($resource)
    {
        $manager = $this->get($this->modelManager);

        $this->dispatchEvent('pre_delete', $resource);
        $manager->remove($resource);
        $this->dispatchEvent('delete', $resource);
        $manager->flush();
        $this->dispatchEvent('post_delete', $resource);
    }

    public function persistAndFlush($resource)
    {
        $manager = $this->getManager();

        $manager->persist($resource);
        $manager->flush();
    }

    public function removeAndFlush($resource)
    {
        $manager = $this->getManager();

        $manager->remove($resource);
        $manager->flush();
    }

    public function dispatchEvent($name, $model)
    {
        $event = new $this->eventClass($model);

        $this->get('event_dispatcher')->dispatch($name, $event);
    }

}

