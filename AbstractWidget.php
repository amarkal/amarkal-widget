<?php

namespace Amarkal\Widget;

abstract class AbstractWidget extends \WP_Widget
{   
    private $config;
    
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
        $values = array_merge($this->get_default_field_values(),$instance);
        $config = $this->get_config();

        foreach( $config['fields'] as $field_args )
        {
            $field_args['value'] = $values[$field_args['name']];
            $field_args['id'] = $this->get_field_id($field_args['name']);
            $field_args['name'] = $this->get_field_name($field_args['name']);
            
            $field = new FormField($field_args);
            echo $field->render();
        }
    }

    public function update( $new_instance, $old_instance ) 
    {
        $instance = array_merge($this->get_default_field_values(), $new_instance);
        $config   = $this->get_config();
        
        foreach( $config['fields'] as $field )
        {
            $name     = $field['name'];
            $value    = $new_instance[$name];
            $instance[$name] = $value;
        }
        return $instance;
    }
    
    private function get_default_field_values()
    {
        $defaults = array();
        $config = $this->get_config();
        foreach( $config['fields'] as $field )
        {
            $defaults[$field['name']] = $field['default'];
        }
        return $defaults;
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