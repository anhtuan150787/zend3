<?php
namespace Cms\Form;

use Cms\Form\Form;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Validator\EmailAddress;

class Category extends Form {
    public function init() {
        parent::init();

        $this->setName('CategoryForm');
        $this->setAttributes(['id' => 'category-form']);
        $this->add([
            'type' => 'text',
            'name' => 'CategoryName',
            'options' => [
                'label' => 'Tên',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
            ],
            'attributes' => [
                'id' => 'CategoryName',
                'maxlength' => 255,
                'class' => 'form-control',
            ]
        ])

        ->add([
            'name' => 'CategoryParent',
            'type' => 'Laminas\Form\Element\Select',
            'options' => [
                'label' => 'Danh mục gốc',
                'label_attributes' => ['class' => 'col-form-label col-md-3 col-sm-3 label-align'],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ])


        ->add([
            'name' => 'CategoryStatus',
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
        ], 'CategoryName');

    }
}