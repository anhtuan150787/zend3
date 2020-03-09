<?php
namespace Cms\Form;

use Cms\Form\Form;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Validator\EmailAddress;

class Login extends Form {
    public function init() {
        parent::init();

        $this->setName('LoginForm');
        $this->setAttributes(['id' => 'login-form']);
        $this->add([
            'type' => 'email',
            'name' => 'Email',
            'options' => [
                'label' => 'Email'
            ],
            'attributes' => [
                'id' => 'Email',
                'maxlength' => 255,
                'placeholder' => 'Email',
                'class' => 'form-control',
            ]
        ])
        ->add([
            'type' => 'password',
            'name' => 'Password',
            'options' => [
                'label' => 'Password'
            ],
            'attributes' => [
                'id' => 'Password',
                'maxlength' => 255,
                'placeholder' => 'Password',
                'class' => 'form-control',
            ]
        ])
        ->add([
            'type' => 'Submit',
            'name' => 'LoginBtn',
            'attributes' => [
                'id' => 'login-btn',
                'value' => 'Login',
                'class' => 'btn btn-primary submit',
            ]
        ]);

        $this->getInputFilter()->add([
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => EmailAddress::class,
                    'break_chain_on_failure' => true,
                ]
            ],
        ], 'Email')->add([
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
        ], 'Password');

    }
}