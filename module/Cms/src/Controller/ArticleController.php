<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Cms\Controller;

use Cms\Form\Article;
use Cms\Service\Model;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;

class ArticleController extends MasterController
{
    const TITLE = 'Bài viết';
    const ARTICLE_TYPE = 1;

    protected $status = ['1' => 'Hiển thị', '0' => 'Ẩn'];

    protected $messages = [
        'AddSuccess' => 'Thêm mới bài viết thành công!',
        'UpdateSuccess' => 'Cập nhật bài viết thành công',
        'DeleteSuccess' => 'Xóa bài viết thành công',
    ];

    public function __construct(Model $model) {
        parent::__construct($model);

        $this->articleModel = $this->model->getModel('\Cms\Service\Article');
        $this->categoryModel = $this->model->getModel('\Cms\Service\Category');

        $this->form = new Article();
        $this->form->init();
    }

    public function indexAction() {
        $messages = $this->flashMessenger()->getMessages('msgInfo');
        $msgInfo = $messages[0];

        $page = $this->params()->fromQuery('page', 1);
        $pageItemPerCount = 20;

        $articles = $this->articleModel->getWithPaging($page, $pageItemPerCount, self::ARTICLE_TYPE);
        $view = new ViewModel();

        $view->setVariables([
            'articles' => $articles,
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
                $this->articleModel->add([
                    'article_title' => $postData['ArticleTitle'],
                    'category_id' => $postData['CategoryId'],
                    'article_status' => $postData['ArticleStatus'],
                    'article_description' => $postData['ArticleDescription'],
                    'article_content' => $postData['ArticleContent'],
                    'article_meta_keyword' => $postData['ArticleMetaKeyword'],
                    'article_meta_description' => $postData['ArticleMetaDescription'],
                    'article_type' => self::ARTICLE_TYPE,
                    'article_created_date' => date('Y-m-d H:i:s'),
                    'article_updated_date' => date('Y-m-d H:i:s'),
                ]);

                $this->flashMessenger()->addMessage($this->messages['AddSuccess'], 'msgInfo');

                $this->redirect()->toRoute('cms/article', ['action' => 'add']);
            }
        }

        $this->setFormSelectOptions();

        $view->setVariables([
            'form' => $this->form,
            'msgInfo' => $msgInfo,
            'title' => self::TITLE,
        ]);

        $view->setTemplate('cms/article/form');

        return $view;
    }

    public function editAction() {
        $view = new ViewModel();
        $id = $this->params()->fromQuery('id', null);
        $data = $this->articleModel->get($id);

        if ($id == null) {
            return $this->redirect()->toRoute('cms/article');
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            $this->form->setData($postData);
            if ($this->form->isValid()) {
                $this->articleModel->update([
                    'article_title' => $postData['ArticleTitle'],
                    'category_id' => $postData['CategoryId'],
                    'article_status' => $postData['ArticleStatus'],
                    'article_description' => $postData['ArticleDescription'],
                    'article_content' => $postData['ArticleContent'],
                    'article_meta_keyword' => $postData['ArticleMetaKeyword'],
                    'article_meta_description' => $postData['ArticleMetaDescription'],
                    'article_updated_date' => date('Y-m-d H:i:s'),
                ], $id);

                $this->flashMessenger()->addMessage($this->messages['UpdateSuccess'], 'msgInfo');

                $this->redirect()->toRoute('cms/article');
            }
        }

        $this->setFormSelectOptions();

        $this->form->get('SubmitBtn')->setAttributes(array('value' => 'Cập nhật'));
        $this->form->setData([
            'ArticleTitle' => $data['article_title'],
            'ArticleStatus' => $data['article_status'],
            'CategoryId' => $data['category_id'],
            'ArticleDescription' => $data['article_description'],
            'ArticleContent' => $data['article_content'],
            'ArticleMetaKeyword' => $data['article_meta_keyword'],
            'ArticleMetaDescription' => $data['article_meta_description'],
        ]);

        $view->setVariables([
            'form' => $this->form,
            'title' => self::TITLE,
        ]);

        $view->setTemplate('cms/article/form');

        return $view;
    }

    public function deleteAction() {
        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            foreach($postData['table_records'] as $id) {
                $this->articleModel->delete($id);
            }
        } else {
            $id = $this->params()->fromQuery('id', null);

            if ($id == null) {
                return $this->redirect()->toRoute('cms/article');
            }

            $this->articleModel->delete($id);
        }

        $this->flashMessenger()->addMessage($this->messages['DeleteSuccess'], 'msgInfo');

        $this->redirect()->toRoute('cms/article');
    }

    public function updateStatusRecordAction() {
        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            foreach($postData['table_records'] as $id) {
                $this->articleModel->update([
                    'article_status' => $_GET['status'],
                ], $id);
            }

            $this->flashMessenger()->addMessage($this->messages['UpdateSuccess'], 'msgInfo');

            $this->redirect()->toRoute('cms/article');
        }
    }

    public function setFormSelectOptions()
    {
        $list = $this->categoryModel->getAll();
        $optionsList = array(0 => '--- Danh mục ---');
        foreach ($list as $k => $v) {
            $optionsList[$v['category_id']] = str_repeat('__', $v['category_level']) . ' ' . $v['category_name'];
        }

        $this->form->get('CategoryId')->setOptions(array('value_options' => $optionsList));
        $this->form->get('ArticleStatus')->setOptions(array('value_options' => $this->status));
    }
}
