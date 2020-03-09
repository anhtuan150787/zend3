<?php
namespace Cms\Form;

use Laminas\Form\Form as LaminasForm;

class Form extends LaminasForm {
    public function init() {
        parent::init();

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'lifetime' => 2700
                ]
            ]
        ]);
    }
}