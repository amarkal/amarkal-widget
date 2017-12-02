(function($){

    // Refresh the UI components when the user expands one of the widgets.
    // This is needed since some of the components are rendered based on their
    // parnet's dimensions.
    $(document).on('click','.widget-top',function(){
        $(this).parent().find('.amarkal-widget-form').amarkalWidgetForm();
    });

    // Reinitiate components when the user clicks on 'save'
    $(document).on('widget-updated', function(e, $widget){
        // $widget represents the jQuery object of the affected widget's DOM element
        $widget.find('.amarkal-widget-form').amarkalWidgetForm();
    });

    // Reinitiate the components when the user drags a new widget to one of the sidebars
    $(document).on('widget-added', function(e, $widget){
        // Wait a bit for the widget form to expand after being added
        setTimeout(function(){
            $widget.find('.amarkal-widget-form').amarkalWidgetForm();
        },100);
    });


    $.fn.extend({
        amarkalWidgetForm: function() {
            var $form = $(this[0]);
            
            // If this is the initial call for this form, instantiate a new 
            // form object
            if( typeof $form.data('amarkal-widget-form') === 'undefined' ) {
                $form.data('amarkal-widget-form', new WidgetForm($form));
            }
    
            $form.data('amarkal-widget-form').refresh();
            return this;
        }
    });

    function WidgetForm($form) {
        this.$form = $form;
        this.$components = $form.find('.amarkal-ui-component');
        this.init();
    }

    WidgetForm.prototype.init = function() {
        this.updateVisibilityConditions();
        this.addEventListeners();
        this.$form.amarkalUIForm();
    };

    /**
     * Since components in widget forms get their names dynamically, we
     * update the visibility conditions to use the newly assigned names
     */
    WidgetForm.prototype.updateVisibilityConditions = function() {
        var _this = this;
        this.$components.each(function(){
            var $props = $(this).amarkalUIComponent('getProps');
            if($props.show) {
                $(this).amarkalUIComponent('setProps', {
                    show: _this.updateVisibilityCondition($props.show)
                });
            }
        });
    };

    /**
     * update a visibility condition to use the updated component names.
     * 
     * @param {string} condition 
     */
    WidgetForm.prototype.updateVisibilityCondition = function(condition) {
        var _this = this;
        return condition.replace(/\{\{(\S+)\}\}/g, function(match, name){
            var $comp = _this.getComponentByOriginalName(name),
                name = $comp.attr('amarkal-component-name');
            
            $comp.amarkalUIComponent('setProps', {name: name});
            return '{{'+name+'}}';
        });
    };

    /**
     * Add show/hide event listeners
     */
    WidgetForm.prototype.addEventListeners = function() {
        this.$components.on('amarkal.hide', function(){
            $(this).parents('.amarkal-widget-field').hide();
        }).on('amarkal.show', function(){
            $(this).parents('.amarkal-widget-field').show();
            $(this).amarkalUIComponent('refresh');
        });
    };

    /**
     * Add a component by its original name. That is, the name it had in the original
     * widget configuraion.
     * 
     * @param {string} name 
     */
    WidgetForm.prototype.getComponentByOriginalName = function(name) {
        var $component;
        this.$components.each(function(){
            if($(this).amarkalUIComponent('getProps').original_name === name) {
                $component = $(this);
                return false;
            }
        });
        return $component;
    };

    WidgetForm.prototype.refresh = function() {
        this.$form.amarkalUIForm('refresh');
    };
}(jQuery))
