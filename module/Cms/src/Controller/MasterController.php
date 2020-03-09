<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Cms\Controller;

use Cms\Service\Model;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;

class MasterController extends AbstractActionController
{
    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function onDispatch(MvcEvent $e)
    {
        $this->layout('layout/cms');

        $auth = new AuthenticationService();

        if (!$auth->hasIdentity()) {
            return $this->redirect()->toRoute('cms/login');
        }

        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->user = $auth->getIdentity();

        return parent::onDispatch($e);
    }
}
