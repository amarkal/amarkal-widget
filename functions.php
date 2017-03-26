<?php
/**
 * WordPress Widget
 *
 * Develop WordPress widgets based on Amarkal UI.
 * This is a component within the Amarkal framework.
 *
 * @package   amarkal-widget
 * @depends   amarkal-ui
 * @author    Askupa Software <hello@askupasoftware.com>
 * @link      https://github.com/askupasoftware/amarkal-widget
 * @copyright 2017 Askupa Software
 */

// Prevent direct file access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Prevent loading the library more than once
 */
if( defined( 'AMARKAL_WIDGET' ) ) return false;
define( 'AMARKAL_WIDGET', true );

if(!function_exists('amarkal_widget_style'))
{
    /**
     * Print widget styles
     */
    function amarkal_widget_style() 
    {
        $cs = get_current_screen();
        if( 'widgets' === $cs->base )
        {
            echo '<style>';
            include 'widget.css';
            echo '</style>';
        }
    }
    add_action('admin_footer', 'amarkal_widget_style');
}
