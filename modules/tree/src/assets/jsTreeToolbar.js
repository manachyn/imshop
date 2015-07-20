(function ($) {

    'use strict';

    var action = '[data-toolbar-action]';

    function TreeToolbar(element, options) {
        this.options = options;
        this.$element = $(element);
        this.$element.on('click.toolbar.action', action, $.proxy(this.performAction, this));
    }

    TreeToolbar.DEFAULTS = {
    };

    TreeToolbar.prototype.performAction = function (e) {
        var $target = $(e.currentTarget);
        var action = $target.attr('data-toolbar-action');
        if (typeof TreeToolbar[action] === "function") {
            TreeToolbar[action].apply(TreeToolbar, [$target, this]);
        }
        e.preventDefault();
        e.stopPropagation();
    };

    TreeToolbar.create = function ($target, toolbar) {
        var treeId = $target.attr('data-tree') || (toolbar && toolbar.options && toolbar.options.tree),
            tree = $('#' + treeId).jstree(true),
            sel = tree.get_selected();
        if (!sel.length) {
            sel = tree.create_node('#');
        } else {
            sel = sel[0];
            sel = tree.create_node(sel);
        }
        if(sel) {
            tree.edit(sel);
        }
    };

    TreeToolbar.delete = function ($target, toolbar) {
        var treeId = $target.attr('data-tree') || (toolbar && toolbar.options && toolbar.options.tree),
            tree = $('#' + treeId).jstree(true),
            sel = tree.get_selected(),
            notSelected = $target.attr('data-not-selected-message');
        if (sel.length) {
            tree.delete_node(sel);
        } else if (notSelected !== undefined) {
            alert(notSelected);
        }
    };

    TreeToolbar.edit = function ($target, toolbar) {
        var treeId = $target.attr('data-tree') || (toolbar && toolbar.options && toolbar.options.tree),
            tree = $('#' + treeId).jstree(true),
            sel = tree.get_selected(),
            notSelected = $target.attr('data-not-selected-message');
        if (sel.length) {
            sel = sel[0];
            tree.trigger('edit_node', {node: tree.get_node(sel)});
        } else if (notSelected !== undefined) {
            alert(notSelected);
        }
    }

    function TreeToolbarPlugin(option) {
        var args = arguments;
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('treeToolbar');
            var options = $.extend({}, TreeToolbar.DEFAULTS, $this.data(), typeof option == 'object' && option);
            if (!data) $this.data('treeToolbar', (data = new TreeToolbar(this, options)));
            if (typeof option == 'string' && typeof data[option] === 'function')
                data[option].apply(data, Array.prototype.slice.call(args, 1));
        });
    };

    $.fn.jsTreeToolbar = TreeToolbarPlugin;
    $.fn.jsTreeToolbar.Constructor = TreeToolbar;

})(jQuery);
