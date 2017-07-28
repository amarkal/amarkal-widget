<?php

namespace Amarkal\Widget;

abstract class AbstractWidget extends \WP_Widget
{   
    /**
     * @var array The configuration array
     */
    private $config;
    
    /**
     * @var \Amarkal\UI\Form Amarkal UI for data processing 
     */
    private $form;
    
    /**
     * Get the user config and call the parent constructor
     */
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
    
    /**
     * Generates the administration form for the widget
     * 
     * @param array $instance The array of keys and values for the widget
     */
    public function form( $instance ) 
    {
        $form = $this->get_form();
        $cl   = $form->get_component_list();
        
        $form->update($instance);
        
        // Set the widget-specific names and ids
        foreach( $cl->get_value_components() as $component )
        {
            $component->original_name = $component->name;
            $component->id = $this->get_field_id($component->name);
            $component->name = $this->get_field_name($component->name);
        }
        
        include __DIR__.'/Form.phtml';
        
        // Use the original names again (for when the components update)
        foreach( $cl->get_value_components() as $component )
        {
            $component->id = $component->original_name;
            $component->name = $component->original_name;
        }
    }

    /**
     * Process the widget's options before they are saved into the db
     *
     * @param array $new_instance The previous instance of values before the update.
     * @param array $old_instance The new instance of values to be generated via the update.
     */
    public function update( $new_instance, $old_instance ) 
    {
        $form = $this->get_form();
        return $form->update($new_instance, $old_instance);
    }
    
    /**
     * Get the Amarkal UI form.
     * 
     * @return \Amarkal\UI\Form
     */
    private function get_form()
    {
        if( !isset($this->form) )
        {
            $config = $this->get_config();
            $this->form = new \Amarkal\UI\Form(
                new \Amarkal\UI\ComponentList($config['fields'])
            );
        }
        return $this->form;
    }
    
    /**
     * Get the default widget configuration.
     * 
     * @return array
     */
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
    
    /**
     * Get the configuraiton array, merging between the user and the default 
     * configuration values.
     * 
     * @return array
     * @throws \RuntimeException if the configuration is missing the ID argument
     */
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
    
    /**
     * The user configuration array. Must be implemented in the child class.
     */
    abstract public function config();
}