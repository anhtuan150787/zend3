<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Cms\Controller;

use Cms\Form\Category;
use Cms\Service\Model;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;

class CategoryController extends MasterController
{
    const TITLE = 'Danh mục';

    protected $status = ['1' => 'Hiển thị', '0' => 'Ẩn'];

    protected $messages = [
        'AddSuccess' => 'Thêm mới danh mục thành công!',
        'UpdateSuccess' => 'Cập nhật danh mục thành công',
        'DeleteSuccess' => 'Xóa danh mục thành công',
    ];

    public function __construct(Model $model) {
        parent::__construct($model);

        $this->categoryModel = $this->model->getModel('\Cms\Service\Category');

        $this->form = new Category();
        $this->form->init();
    }

    public function indexAction() {
        $messages = $this->flashMessenger()->getMessages('msgInfo');
        $msgInfo = $messages[0];

        $categories = $this->categoryModel->getAll();
        $view = new ViewModel();

        $view->setVariables([
            'categories' => $categories,
            'msgInfo' => $msgInfo,
            'status' =>  $this->status,
            'title' => self::TITLE,
        ]);

        return $view;
    }

    public function addAction() {
        $view = new ViewModel();
        $messages = $this->flashMessenger()->getMessages('msgInfo');
        $msgInfo = $messages[0];

        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            $this->form->setData($postData);
            if ($this->form->isValid()) {
                $this->categoryModel->add([
                    'category_name' => $postData['CategoryName'],
                    'category_parent' => $postData['CategoryParent'],
                    'category_status' => $postData['CategoryStatus'],
                ]);

                $this->flashMessenger()->addMessage($this->messages['AddSuccess'], 'msgInfo');

                $this->redirect()->toRoute('cms/category', ['action' => 'add']);
            }
        }

        $this->setFormSelectOptions();

        $view->setVariables([
            'form' => $this->form,
            'msgInfo' => $msgInfo,
            'title' => self::TITLE,
        ]);

        $view->setTemplate('cms/category/form');

        return $view;
    }

    public function editAction() {
        $view = new ViewModel();
        $id = $this->params()->fromQuery('id', null);
        $data = $this->categoryModel->get($id);

        if ($id == null) {
            return $this->redirect()->toRoute('cms/category');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            $this->form->setData($postData);
            if ($this->form->isValid()) {
                $this->categoryModel->update([
                    'category_name' => $postData['CategoryName'],
                    'category_parent' => $postData['CategoryParent'],
                    'category_status' => $postData['CategoryStatus'],
                ], $id);

                $this->flashMessenger()->addMessage($this->messages['UpdateSuccess'], 'msgInfo');

                $this->redirect()->toRoute('cms/category');
            }
        }

        $this->setFormSelectOptions();

        $this->form->get('SubmitBtn')->setAttributes(array('value' => 'Cập nhật'));
        $this->form->setData([
            'CategoryName' => $data['category_name'],
            'CategoryParent' => $data['category_parent'],
            'CategoryStatus' => $data['category_status'],
        ]);

        $view->setVariables([
            'form' => $this->form,
            'title' => self::TITLE,
        ]);

        $view->setTemplate('cms/category/form');

        return $view;
    }

    public function deleteAction() {
        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            foreach($postData['table_records'] as $id) {
                $this->categoryModel->delete($id);
            }
        } else {
            $id = $this->params()->fromQuery('id', null);

            if ($id == null) {
                return $this->redirect()->toRoute('cms/category');
            }

            $this->categoryModel->delete($id);
        }

        $this->flashMessenger()->addMessage($this->messages['DeleteSuccess'], 'msgInfo');

        $this->redirect()->toRoute('cms/category');
    }

    public function updateStatusRecordAction() {
        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            foreach($postData['table_records'] as $id) {
                $this->categoryModel->update([
                    'category_status' => $_GET['status'],
                ], $id);
            }

            $this->flashMessenger()->addMessage($this->messages['UpdateSuccess'], 'msgInfo');

            $this->redirect()->toRoute('cms/category');
        }
    }

    public function setFormSelectOptions()
    {
        $list = $this->categoryModel->getAll();
        $optionsList = array(0 => '--- Danh mục ---');
        foreach ($list as $k => $v) {
            $optionsList[$v['category_id']] = str_repeat('__', $v['category_level']) . ' ' . $v['category_name'];
        }

        $this->form->get('CategoryParent')->setOptions(array('value_options' => $optionsList));
        $this->form->get('CategoryStatus')->setOptions(array('value_options' => $this->status));
    }
}
