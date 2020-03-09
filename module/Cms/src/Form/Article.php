<?php
namespace Cms\Form;

use Cms\Form\Form;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Validator\EmailAddress;

class Article extends Form {
    public function init() {
        parent::init();

        $this->setName('ArticleForm');
        $this->setAttributes(['id' => 'article-form']);
        $this->add([
            'type' => 'text',
            'name' => 'ArticleTitle',
            'options' => [
                'label' => 'Tiêu đề',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
            ],
            'attributes' => [
                'id' => 'ArticleTitle',
                'maxlength' => 255,
                'class' => 'form-control',
            ]
        ])

        ->add([
            'name' => 'CategoryId',
            'type' => 'Laminas\Form\Element\Select',
            'options' => [
                'label' => 'Danh mục',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ])

        ->add([
            'name' => 'ArticleDescription',
            'type' => 'Laminas\Form\Element\Textarea',
            'options' => [
                'label' => 'Mô tả ngắn',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => 'editor_description'
            ],
        ])

        ->add([
            'name' => 'ArticleContent',
            'type' => 'Laminas\Form\Element\Textarea',
            'options' => [
                'label' => 'Nội dung',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => 'editor_content',
            ],
        ])

        ->add([
            'name' => 'ArticleMetaKeyword',
            'type' => 'Laminas\Form\Element\Textarea',
            'options' => [
                'label' => 'Meta keywords',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ])

        ->add([
            'name' => 'ArticleMetaDescription',
            'type' => 'Laminas\Form\Element\Textarea',
            'options' => [
                'label' => 'Meta description',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ])

        ->add([
            'name' => 'ArticleStatus',
            'type' => 'Laminas\Form\Element\Select',
            'options' => [
                'label' => 'Trạng thái',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ])

        ->add([
            'type' => 'Submit',
            'name' => 'SubmitBtn',
            'attributes' => [
                'id' => 'submit-btn',
                'value' => 'Thêm',
                'class' => 'btn btn-success',
            ]
        ]);

        $this->getInputFilter()->add([
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
        ], 'ArticleTitle');

    }
}