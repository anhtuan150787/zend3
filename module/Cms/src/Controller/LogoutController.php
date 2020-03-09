<?php
namespace Cms\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;

class LogoutController extends AbstractActionController
{
    public function indexAction()
    {
        $auth =  new AuthenticationService();
        $user = $auth->getIdentity();
        $auth->clearIdentity();
        return $this->redirect()->toRoute('cms/login');
    }
}
