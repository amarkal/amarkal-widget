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
 * @link      https://github.com/amarkal/amarkal-widget
 * @copyright 2017 Askupa Software
 */

// Prevent direct file access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Prevent loading the library more than once
 */
if( defined( 'AMARKAL_WIDGET' ) ) return false;
define( 'AMARKAL_WIDGET', true );

if(!function_exists('amarkal_widget_assets'))
{
    /**
     * Print widget styles
     */
    function amarkal_widget_scripts( $hook ) 
    {
        if( 'widgets.php' === $hook )
        {
            \wp_enqueue_style('amarkal-widget',\Amarkal\Core\Utility::path_to_url(__DIR__.'/widget.css'));
            \wp_enqueue_script('amarkal-widget',\Amarkal\Core\Utility::path_to_url(__DIR__.'/widget.js'),array('jquery','amarkal-ui'));
        }
    }
    add_action('admin_enqueue_scripts', 'amarkal_widget_scripts');
}
