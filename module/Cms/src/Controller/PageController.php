<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Cms\Controller;

use Cms\Form\Article;
use Cms\Form\Page;
use Cms\Service\Model;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;

class PageController extends MasterController
{
    const TITLE = 'Nội dung trang';

    const ARTICLE_TYPE = 2;

    protected $status = ['1' => 'Hiển thị', '0' => 'Ẩn'];

    protected $messages = [
        'AddSuccess' => 'Thêm mới Nội dung trang thành công!',
        'UpdateSuccess' => 'Cập nhật Nội dung trang thành công',
        'DeleteSuccess' => 'Xóa Nội dung trang thành công',
    ];

    public function __construct(Model $model) {
        parent::__construct($model);

        $this->pageModel = $this->model->getModel('\Cms\Service\Article');

        $this->form = new Article();
        $this->form->init();
    }

    public function indexAction() {
        $messages = $this->flashMessenger()->getMessages('msgInfo');
        $msgInfo = $messages[0];

        $page = $this->params()->fromQuery('page', 1);
        $pageItemPerCount = 20;

        $pages = $this->pageModel->getWithPaging($page, $pageItemPerCount, self::ARTICLE_TYPE);
        $view = new ViewModel();

        $view->setVariables([
            'pages' => $pages,
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
                $this->pageModel->add([
                    'article_title' => $postData['ArticleTitle'],
                    'article_status' => $postData['ArticleStatus'],
                    'article_content' => $postData['ArticleContent'],
                    'article_meta_keyword' => $postData['ArticleMetaKeyword'],
                    'article_meta_description' => $postData['ArticleMetaDescription'],
                    'article_type' => self::ARTICLE_TYPE,
                    'article_created_date' => date('Y-m-d H:i:s'),
                    'article_updated_date' => date('Y-m-d H:i:s'),
                ]);

                $this->flashMessenger()->addMessage($this->messages['AddSuccess'], 'msgInfo');

                $this->redirect()->toRoute('cms/page', ['action' => 'add']);
            }
        }

        $this->setFormSelectOptions();

        $view->setVariables([
            'form' => $this->form,
            'msgInfo' => $msgInfo,
            'title' => self::TITLE,
        ]);

        $view->setTemplate('cms/page/form');

        return $view;
    }

    public function editAction() {
        $view = new ViewModel();
        $id = $this->params()->fromQuery('id', null);
        $data = $this->pageModel->get($id);

        if ($id == null) {
            return $this->redirect()->toRoute('cms/page');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            $this->form->setData($postData);
            if ($this->form->isValid()) {
                $this->pageModel->update([
                    'article_title' => $postData['ArticleTitle'],
                    'article_status' => $postData['ArticleStatus'],
                    'article_content' => $postData['ArticleContent'],
                    'article_meta_keyword' => $postData['ArticleMetaKeyword'],
                    'article_meta_description' => $postData['ArticleMetaDescription'],
                    'article_updated_date' => date('Y-m-d H:i:s'),
                ], $id);

                $this->flashMessenger()->addMessage($this->messages['UpdateSuccess'], 'msgInfo');

                $this->redirect()->toRoute('cms/page');
            }
        }

        $this->setFormSelectOptions();

        $this->form->get('SubmitBtn')->setAttributes(array('value' => 'Cập nhật'));
        $this->form->setData([
            'ArticleTitle' => $data['article_title'],
            'ArticleStatus' => $data['article_status'],
            'ArticleContent' => $data['article_content'],
            'ArticleMetaKeyword' => $data['article_meta_keyword'],
            'ArticleMetaDescription' => $data['article_meta_description'],
        ]);

        $view->setVariables([
            'form' => $this->form,
            'title' => self::TITLE,
        ]);

        $view->setTemplate('cms/page/form');

        return $view;
    }

    public function deleteAction() {
        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            foreach($postData['table_records'] as $id) {
                $this->pageModel->delete($id);
            }
        } else {
            $id = $this->params()->fromQuery('id', null);

            if ($id == null) {
                return $this->redirect()->toRoute('cms/page');
            }

            $this->pageModel->delete($id);
        }

        $this->flashMessenger()->addMessage($this->messages['DeleteSuccess'], 'msgInfo');

        $this->redirect()->toRoute('cms/page');
    }

    public function updateStatusRecordAction() {
        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            foreach($postData['table_records'] as $id) {
                $this->pageModel->update([
                    'article_status' => $_GET['status'],
                ], $id);
            }

            $this->flashMessenger()->addMessage($this->messages['UpdateSuccess'], 'msgInfo');

            $this->redirect()->toRoute('cms/page');
        }
    }

    public function setFormSelectOptions()
    {
        $this->form->get('ArticleStatus')->setOptions(array('value_options' => $this->status));
    }
}
