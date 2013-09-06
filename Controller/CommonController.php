<?php

namespace Avro\ExtraBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Form;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
class CommonController extends Controller
{
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
    public function getModelClass()
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
            return $this->container->get($modelManagerAlias);
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

            return new $modelManagerClass($this->container->get($objectManager), $this->container->get('event_dispatcher'), $this->getModelClass());
        }
    }

    /**
     * getBundleShortName
     *
     * @return string
     */
    public function getBundleShortName()
    {
        return sprintf('%s%s', ucfirst(str_replace('_', '', $this->bundleAlias)), ucfirst($this->modelAlias));
    }

    public function findModel($id)
    {
        $model = $this->container->getModelManager()->getRepository()->find($id);

        if (!$model) {
            $this->container->get('session')->getFlashBag()->set('error', sprintf('%s.%s.not_found', $this->bundleAlias, $this->modelAlias));

            return new RedirectResponse($this->container->get('router')->generate('avro_blog_post_list'));
        }

        return $model;
    }

    public function findModelBySlug($slug)
    {
        $model = $this->container->getModelManager()->getRepository()->findOneBy(array('slug' => $slug));

        return $model;
    }

    /**
     * getFormErrors
     *
     * @param Form $form
     * @return array
     */
    public function getFormErrors(Form $form) {
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
}