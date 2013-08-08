<?php

namespace Avro\ExtraBundle\Request\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Avro\ExtraBundle\Event\ParamConverterEvent;

class SecurityContextUserParamConverter implements ParamConverterInterface
{
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $user = $this->context->getToken()->getUser();

        $param = $configuration->getName();
        $request->attributes->set($param, $user);
    }

    public function supports(ConfigurationInterface $configuration)
    {
        return "FOS\UserBundle\Model\UserInterface" === $configuration->getClass();
    }
}
