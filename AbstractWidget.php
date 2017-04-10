<?php

namespace Amarkal\Widget;

abstract class AbstractWidget extends \WP_Widget
{   
    private $config;
    
    private $ui_form;
    
    public function __construct() 
    {
        $config = $this->get_config();
        
        parent::__construct( 
            $config['id'], 
            $config['name'], 
            $config['widget_options'], 
            $config['control_options'] 
        );
    }
    
    public function form( $instance ) 
    {
        $form       = $this->get_form();
        $components = $form->get_components();
        
        $form->update($instance);
        
        foreach( $components as $component )
        {
            $component->original_name = $component->name;
            $component->id = $this->get_field_id($component->name);
            $component->name = $this->get_field_name($component->name);
        }
        
        include __DIR__.'/Form.phtml';
        
        foreach( $components as $component )
        {
            $component->id = $component->original_name;
            $component->name = $component->original_name;
        }
    }

    public function update( $new_instance, $old_instance ) 
    {
        $form = $this->get_form();
        return $form->update($new_instance, $old_instance);
    }
    
    private function get_form()
    {
        if( !isset($this->ui_form) )
        {
            $config = $this->get_config();
            $this->ui_form = new \Amarkal\UI\Form($config['fields']);
        }
        return $this->ui_form;
    }
    
    private function default_config()
    {
        return array(
            'id'              => null,
            'name'            => null,
            'widget_options'  => array(),
            'control_options' => array(),
            'fields'          => array()
        );
    }
    
    private function get_config()
    {
        if( !isset($this->config) )
        {
            $this->config = array_merge(
                $this->default_config(),
                $this->config()
            );

            if( null === $this->config['id'] )
            {
                throw new \RuntimeException('No \'id\' was sepcified in the widget configuration');
            }
        }
        return $this->config;
    }
    
    abstract public function config();
}