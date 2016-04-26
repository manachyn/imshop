(function ($) {

    'use strict';

    var action = '[data-toolbar-action]';

    function Action(element, options) {
        this.options = options;
        this.$element = $(element);
        //this.$element.on('click.toolbar.action', action, $this.performAction);
    }

    Action.prototype.perform = function (event, ui) {

    };

    function Toolbar(element, options) {
        this.options = options;
        this.$element = $(element);
        this.$element.on('click.toolbar.action', action, $.proxy(this.performAction, this));
    }

    Toolbar.DEFAULTS = {
    };

    Toolbar.prototype.performAction = function (e) {
        var $target = $(e.currentTarget);
        var action = $target.attr('data-toolbar-action');
        if (typeof Toolbar[action] === "function") {
            Toolbar[action].apply(Toolbar, [$target, this]);
        }
        e.preventDefault();
        e.stopPropagation();
    };

    Toolbar.delete = function ($target, toolbar) {
        var url = $target.attr('href'),
            grid = $target.attr('data-grid') || (toolbar && toolbar.options && toolbar.options.grid),
            confirmation = $target.attr('data-confirmation-message'),
            notSelected = $target.attr('data-not-selected-message'),
            selected;

        if (grid !== undefined) {
            selected = jQuery('#' + grid).yiiGridView('getSelectedRows');
            if (selected.length > 0) {
                if (confirmation === undefined || confirm(confirmation))
                    $.ajax({type: 'POST', url: url, data: {ids: selected}});
            }
            else if (notSelected !== undefined)
                alert(notSelected);
        }
        //$.pjax.reload({container:'#idofyourpjaxwidget'});
    };

    Toolbar.update = function ($target, toolbar) {
        var url = $target.attr('href'),
            grid = $target.attr('data-grid') || (toolbar && toolbar.options && toolbar.options.grid),
            modal = $target.attr('data-modal') || (toolbar && toolbar.options && toolbar.options.modal),
            popover = {'title' : $target.attr('data-popover-title') || 'Fields to update'},
            notSelected = $target.attr('data-not-selected-message'),
            $tip,
            selected,
            $modal;

        if (grid !== undefined) {
            selected = $('#' + grid).yiiGridView('getSelectedRows');
            if (selected.length > 0) {
                if (!$target.data('action.settings'))
                    $.ajax({
                        url: url
                    }).done(function(content) {
                        $target.data('action.settings', true);
                        $tip = $target.popover({'content': content, 'title': popover.title, 'placement': 'auto right', 'html': true})
                            .data('bs.popover').tip().addClass('horizontal');
                        $target.popover('show');
                        $tip.on('submit', 'form', function(e) {
                            var $settings = $(this);
                            $target.popover('hide');
                            if (modal !== undefined) {
                                $modal = $('#' + modal);
                                $modal.on('submit', 'form', function(e) {
                                    var $form = $(this);
                                    var fields = [];
                                    $.each(selected, function(index, value) {
                                        fields.push($('<input type="hidden" name="ids[]" value="' + value + '">'));
                                    });
                                    $form.append(fields);
                                });
                                $.post(url, $settings.serialize(), function(data) {
                                    $modal.find('.modal-content').html(data);
                                    $modal.modal('show');
                                });
                            }
                            e.preventDefault();
                        });
                    });
            }
            else if (notSelected !== undefined)
                alert(notSelected);
        }
    }

    function ToolbarPlugin(option) {
        var args = arguments;
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('toolbar');
            var options = $.extend({}, Toolbar.DEFAULTS, $this.data(), typeof option == 'object' && option);
            if (!data) $this.data('toolbar', (data = new Toolbar(this, options)));
            if (typeof option == 'string' && typeof data[option] === 'function')
                data[option].apply(data, Array.prototype.slice.call(args, 1));
        });
    };

    function ActionPlugin(option) {
        var args = arguments;
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('toolbar');
            var options = $.extend({}, Toolbar.DEFAULTS, $this.data(), typeof option == 'object' && option);
            if (!data) $this.data('toolbar', (data = new Toolbar(this, options)));
            if (typeof option == 'string' && typeof data[option] === 'function')
                data[option].apply(data, Array.prototype.slice.call(args, 1));
        });
    }

    $.fn.toolbar = ToolbarPlugin;
    $.fn.toolbar.Constructor = Toolbar;
    $.fn.toolbarAction = ActionPlugin;
    $.fn.toolbarAction.Constructor = Action;

    // Toolbar data-api
//    $(document).on('click.toolbar.data-api', action, function (e) {
//        var $this = $(this);
//        var $toolbar = $($this.attr('data-toolbar'));
//        if ($toolbar)
//        ActionPlugin.call($this, 'perform');
//        e.preventDefault()
//    });

})(jQuery);
