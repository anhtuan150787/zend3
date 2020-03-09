<?php

namespace Cms\Service\Controller;

use Cms\Service\Model;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

final class ControllerInvokableFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new $requestedName($container->get(Model::class));
    }
}