# amarkal-widget [![Build Status](https://scrutinizer-ci.com/g/amarkal/amarkal-widget/badges/build.png?b=master)](https://scrutinizer-ci.com/g/amarkal/amarkal-widget/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/amarkal/amarkal-widget/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/amarkal/amarkal-widget/?branch=master) [![License](https://img.shields.io/badge/license-GPL--3.0%2B-red.svg)](https://raw.githubusercontent.com/amarkal/amarkal-widget/master/LICENSE)
Develop WordPress widgets based on Amarkal UI.

**Tested up to:** WordPress 4.7  
**Dependencies**: *[amarkal-ui](https://github.com/amarkal/amarkal-ui)*

![amarkal-widget](https://askupasoftware.com/wp-content/uploads/2015/04/amarkal-widget.png)

## Overview
**amarkal-widget** lets you develop widgets for your WordPress theme or plugin, using [amarkal-ui](https://github.com/amarkal/amarkal-ui). **amarkal-widget** takes care of building the admin user form and saving the user input, so you can concentrate on building the widget itself!

## Installation

### Via Composer

If you are using the command line:  
```
$ composer require askupa-software/amarkal-widget:dev-master
```

Or simply add the following to your `composer.json` file:
```javascript
"require": {
    "askupa-software/amarkal-widget": "dev-master"
}
```
And run the command 
```
$ composer install
```

This will install the package in the directory `vendors/askupa-software/amarkal-widget`.
Now all you need to do is include the composer autoloader.

```php
require_once 'path/to/vendor/autoload.php';
```

### Manually

Download [amarkal-ui](https://github.com/amarkal/amarkal-ui/archive/master.zip) and [amarkal-widget](https://github.com/amarkal/amarkal-widget/archive/master.zip) from github and include them in your project.

```php
require_once 'path/to/amarkal-ui/bootstrap.php';
require_once 'path/to/amarkal-widget/bootstrap.php';
```

## Usage

Create a class that inherits from `\Amarkal\Widget\AbstractWidget`. This class should implement 2 methods:

 * `config()` - a public function that returns an array with the widget's configuration.
 * `widget( $args, $instance )` - a public function that prints the widget's front-end HTML.
 
 ### Configuration Arguments
 
Name | Type | Default | Required | Description
---|---|---|:---:|---
`id`|*string*|`''`|Yes|Specifies the widget's id.
`name`|*string*|`''`|Yes|Specifies the widget's name.
`widget_options`|*array*|`array()`|No|Specifies a list of widget options, like a description.
`control_options`|*array*|`array()`|No|Specifies a list of widget control options.
`fields`|*array*|`array()`|No|Specifies a list of `amarkal-ui` components to be used for the widget's admin form. Each item in this array should be an array and have the original UI component arguments as specified in `amarkal-ui`, as well as the following: `default`, `title`, `description`.

### Example Code

Create a class for your widget:

```php
class MyCoolWidget 
extends \Amarkal\Widget\AbstractWidget
{   
    /**
     * The widget's configuration
     */
    public function config()
    {
        return array(
            'id'              => 'm-cool-widget',   // The widget's id
            'name'            => 'My Cool Widget',  // The widget's id
            'widget_options'  => array(
                'description' => 'Just a very very cool widget...'  // The widget's description
            ),
            'control_options' => array(),           // Optional
            
            /**
             * The 'fields' argument specifies a list of amarkal-ui components to be used for the widget's admin form.
             */
            'fields'          => array(
                array(
                    'name'          => 'title',
                    'title'         => __( 'Title:', 'slug' ),
                    'default'       => 'My Cool Widget',
                    'description'   => 'Specifies the widget\'s title',
                    'type'          => 'text'
                ),
                array(
                    'name'          => 'content',
                    'title'         => __( 'Text:', 'slug' ),
                    'default'       => 'My cool widget content',
                    'description'   => 'Specifies the widget\'s content',
                    'type'          => 'text'
                ),
            )
        );
    }
    
    /**
     * The front-end display of widget. User data is accesible through the $instance variable.
     */
    public function widget( $args, $instance ) 
    {
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );        
        
        echo $args['before_widget'];
        
        // Echo the widget's title if not empty
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Echo the widget's content if not empty
        if( $instance['content'] ) {
            echo '<p>'.$instance['content'].'</p>';
        }
        
        echo $args['after_widget'];
    }
}
```

Then register the widget as you would register any other widget:

```php
function register_widgets() {
    register_widget( 'MyCoolWidget' );
}
add_action( 'widgets_init', 'register_widgets' );
```