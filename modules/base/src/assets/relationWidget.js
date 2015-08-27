(function ($) {

    'use strict';

    var add = '[data-action="add"]',
        remove = '[data-action="remove"]',
        temp = '[data-cont="temp"]';

    function RelationWidget(element, options) {
        this.options = options;
        this.$element = $(element);
        this.$element.on('click', add, $.proxy(this.onAdd, this));
        this.$element.on('click', remove, $.proxy(this.onRemove, this));
        if (this.options.sortable) {
            this.sortable = this.$element.sortable('instance');
            this.sortable.element.on('sortupdate', $.proxy(this.onSortUpdate, this));
        }
    }

    RelationWidget.DEFAULTS = {};

    RelationWidget.prototype.onAdd = function (event) {
        event.preventDefault();
        var settings = {
            type: 'POST',
            url: this.options.addUrl,
            push: false,
            container: temp,
            data: {
                form: this.options.form,
                itemClass: this.options.itemClass,
                itemView: this.options.itemView,
                index: this.getItemsCount() + 1
            }
        };
        console.log(settings);
        $.pjax(settings);
    };

    RelationWidget.prototype.onRemove = function (event) {
        event.preventDefault();
        $(event.target).closest(this.options.items).slideUp().remove();
        this.setPlaceholder();
    };

    RelationWidget.prototype.onSortUpdate = function (event, ui) {
        this.updateSort();
    };

    RelationWidget.prototype.updateSort = function () {
        $(this.options.items, this.$element).each(function(index) {
            $(this).find('input[data-field="sort"]').val(index + 1);
        });
    };

    RelationWidget.prototype.getItemsCount = function () {
        return this.$element.find(this.options.items).length;
    };

    function Plugin(option) {
        var args = arguments;
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('relationWidget');
            var options = $.extend({}, RelationWidget.DEFAULTS, $this.data(), typeof option === 'object' && option);
            if (!data) {
                $this.data('relationWidget', (data = new RelationWidget(this, options)));
            }
            if (typeof option === 'string' && typeof data[option] === 'function') {
                data[option].apply(data, Array.prototype.slice.call(args, 1));
            }
        });
    }

    $.fn.relationWidget = Plugin;
    $.fn.relationWidget.Constructor = RelationWidget;

})(jQuery);
