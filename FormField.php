<?php

namespace Amarkal\Widget;

class FormField
extends \Amarkal\UI\AbstractController
{
    public function default_model() 
    {
        return array(
            'type'          => '',
            'label'         => '',
            'description'   => '',
            'default'       => ''
        );
    }
    
    public function get_template_path()
    {
        return __DIR__.'/FormField.phtml';
    }
}