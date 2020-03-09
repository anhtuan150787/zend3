<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Cms\Controller;

use Cms\Form\Login;
use Cms\Service\Model;
use Cms\Service\User;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\Adapter\DbTable;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session;

class LoginController extends AbstractActionController
{
    public function __construct(Model $model) {
        $this->adapter = $model->getAdapter();
    }

    public function indexAction()
    {
        $authService    = new AuthenticationService();
        if ($authService->hasIdentity()) {
            $this->redirect()->toRoute('cms');
        }

        $this->layout('layout/cms_login');

        $view = new ViewModel();

        $form = new Login();
        $form->init();

        $error = '';

        if($this->getRequest()->isPost()) {
            if ($form->setData($_POST)->isValid()) {
                $email      = $_POST['Email'];
                $password   = $_POST['Password'];

                $authAdapter    = new DbTable($this->adapter);

                $authAdapter->setTableName('user')->setIdentityColumn('email')->setCredentialColumn('password');
                $authAdapter->setIdentity($email)->setCredential(md5(hash('sha512', $password)));

                $result = $authAdapter->authenticate();

                if ($result->isValid()) {

                    $storage = new Session();
                    $storage->write($authAdapter->getResultRowObject(array(
                        'email',
                    )));

                    $authService->setStorage($storage)->setAdapter($authAdapter);

                    $this->redirect()->toRoute('cms');
                } else {
                    $error = 'Email or Password is wrong';
                }
            }
        }

        $view->setVariables([
            'form' => $form,
            'error' => $error,
        ]);

        return $view;
    }
}
