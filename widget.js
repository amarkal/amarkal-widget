// Refresh the UI components when the user expands one of the widgets.
// This is needed since some of the components are rendered based on their
// parnet's dimensions.
jQuery(document).on('click','.widget-top',function(){
    jQuery(this).parent().find('.amarkal-ui-component').each(function(){
        jQuery(this).amarkalUIComponent('refresh');
    });
});

// Reinitiate components when the user clicks on 'save'
jQuery(document).on('widget-updated', function(e, $widget){
    // $widget represents the jQuery object of the affected widget's DOM element
    $widget.find('.amarkal-ui-component').amarkalUIComponent();
});

// Reinitiate the components when the user drags a new widget to one of the sidebars
jQuery(document).on('widget-added', function(e, $widget){
    $widget.find('.amarkal-ui-component').amarkalUIComponent();
});