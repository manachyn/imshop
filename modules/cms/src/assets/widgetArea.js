(function ($) {

    'use strict';

    var dropArea = '[data-cont="drop-area"]',
        placeholder = '[data-cont="drop-area-placeholder"]',
        temp = '[data-cont="temp"]',
        remove = '[data-action="remove"]';

    function WidgetArea(element, options) {
        this.options = options;
        delete this.options.form.attributes;
        this.$element = $(element);
        this.$dropArea = this.$element.find(dropArea);
        this.$placeholder = this.$element.find(placeholder);
        this.tempSelector = '#' + this.$element.attr('id') + ' ' + temp;
        this.$temp = $(this.tempSelector);
        if (this.options.droppable) {
            this.options.droppable.element.on('drop', $.proxy(this.onDrop, this));
        }
        if (this.options.sortable) {
            this.options.sortable.element.on('sortupdate', $.proxy(this.onSortUpdate, this));
        }
        //this.$element.on('click.widget.edit', '[data-action="edit"]', $.proxy(this.onEdit, this));
        this.$element.on('click.widget.remove', remove, $.proxy(this.onRemove, this));;
        this.$element.on('change.widgetArea.display', '[data-action="display"]', $.proxy(this.onDisplay, this));
    }

    WidgetArea.DEFAULTS = {
        displayNone: 0,
        displayInherit: 1,
        displayCustom: 2
    };

    WidgetArea.prototype.onDrop = function (event, ui) {
        this.addWidget($.extend({}, ui.draggable.data()));
    };

    WidgetArea.prototype.onSortUpdate = function (event, ui) {
        this.updateSort();
    };

    WidgetArea.prototype.onRemove = function (event) {
        event.preventDefault();
        $(event.target).closest(this.options.items).slideUp().remove();
        this.setPlaceholder();
    };

    WidgetArea.prototype.onDisplay = function (event) {
        event.preventDefault();
        var display = $(event.target).val();
        if (display === WidgetArea.DEFAULTS.displayCustom) {
            this.$dropArea.show();
        } else {
            this.$dropArea.hide();
        }
    };

    WidgetArea.prototype.addWidget = function (widget) {
        var that = this,
            settings;
        if (!widget.id) {
            this.$element.on('pjax:success', temp, function(){
                that.$dropArea.append(that.$temp.html());
                that.$temp.empty();
                that.setPlaceholder();
            });
            if (!this.options.form.fieldConfig.length) {
                this.options.form.fieldConfig = {};
            }
            this.options.form.fieldConfig.namePrefix = 'Widgets[' + this.options.widgetArea.code + ']';
            settings = {
                type: 'POST',
                url: this.options.addUrl,
                push: false,
                container: this.tempSelector,
                data: {type: widget.type, form: this.options.form, index: this.getItemsCount() + 1}
            };
            $.pjax(settings);
        }
    };

    WidgetArea.prototype.updateSort = function () {
        $(this.options.sortable.options.items, this.options.sortable.element).each(function(index) {
            $(this).find('input[data-field="sort"]').val(index + 1);
        });
    };

    WidgetArea.prototype.getItemsCount = function () {
        return this.$element.find(this.options.items).length;
    };

    WidgetArea.prototype.setPlaceholder = function () {
        if (this.getItemsCount() > 0) {
            this.$placeholder.hide();
        } else {
            this.$placeholder.show();
        }
    };

    function Plugin(option) {
        var args = arguments;
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('widgetArea');
            var options = $.extend({}, WidgetArea.DEFAULTS, $this.data(), typeof option === 'object' && option);
            if (!data) {
                $this.data('widgetArea', (data = new WidgetArea(this, options)));
            }
            if (typeof option === 'string' && typeof data[option] === 'function') {
                data[option].apply(data, Array.prototype.slice.call(args, 1));
            }
        });
    }

    var old = $.fn.widgetArea;

    $.fn.widgetArea = Plugin;
    $.fn.widgetArea.Constructor = WidgetArea;

    $.fn.widgetArea.noConflict = function () {
        $.fn.tooltip = old;
        return this;
    };

})(jQuery);
