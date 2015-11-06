(function ($) {

    'use strict';

    var add = '[data-action="add"]',
        remove = '[data-action="remove"]',
        temp = '[data-cont="list-temp"]',
        list = '[data-cont="list"]';

    function ListView(element, options) {
        this.options = options;
        if (this.options.viewParams.form !== undefined) {
            delete this.options.viewParams.form.attributes;
        }
        this.$element = $(element);
        this.$temp = this.$element.find(temp);
        this.$list = this.$element.find(list);
        this.$element.on('click', add, $.proxy(this.onAdd, this));
        this.$element.on('click', remove, $.proxy(this.onRemove, this));
        var id;
        if (id = this.$element.attr('id')) {
            var $toolbar = $('[data-toolbar="' + id + '"]');
            $toolbar.on('click', add, $.proxy(this.onAdd, this));
        }
        if (this.options.sortable) {
            this.sortable = this.$element.sortable('instance');
            this.sortable.element.on('sortupdate', $.proxy(this.onSortUpdate, this));
        }
        this.$element.on('pjax:success', temp, $.proxy(this.onResponse, this));
        //this.adjustItemsHeight();
    }

    ListView.DEFAULTS = {};

    ListView.prototype.onAdd = function (event) {
        event.preventDefault();
        var actionParams = $(event.target).data('actionParams');
        var data = {
            itemClass: this.options.itemClass,
            itemView: this.options.itemView,
            viewParams: this.options.viewParams,
            itemContainerView: this.options.itemContainerView
        }
        if (actionParams) {
            $.extend(data, actionParams)
        }
        var settings = {
            type: 'POST',
            url: this.options.addUrl,
            push: false,
            container: temp,
            data: data
        };
        settings.data.viewParams.fieldConfig = settings.data.viewParams.fieldConfig || {};
        settings.data.viewParams.fieldConfig.tabularIndex = this.getItemsCount() + 1;
        $.pjax(settings);
    };

    ListView.prototype.onRemove = function (event) {
        event.preventDefault();
        $(event.target).closest(this.options.items).slideUp().remove();
    };

    ListView.prototype.onSortUpdate = function (event, ui) {
        this.updateSort();
    };

    ListView.prototype.updateSort = function () {
        $(this.options.items, this.$element).each(function(index) {
            $(this).find('input[data-field="sort"]').val(index + 1);
        });
    };

    ListView.prototype.getItemsCount = function () {
        return this.$element.find(this.options.items).length;
    };

    ListView.prototype.onResponse = function () {
        this.$list.append(this.$temp.html());
        this.$temp.html('');
        this.updateSort();
    };

    //ListView.prototype.adjustItemsHeight = function () {
    //    var $items = $(this.options.items, this.$element),
    //        max = 0,
    //        height;
    //    $items.each(function() {
    //        height = $(this).height();
    //        if (height > max) {
    //            max = height;
    //        }
    //    });
    //    $items.height(max);
    //};

    function Plugin(option) {
        var args = arguments;
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('listView');
            var options = $.extend({}, ListView.DEFAULTS, $this.data(), typeof option === 'object' && option);
            if (!data) {
                $this.data('listView', (data = new ListView(this, options)));
            }
            if (typeof option === 'string' && typeof data[option] === 'function') {
                data[option].apply(data, Array.prototype.slice.call(args, 1));
            }
        });
    }

    $.fn.listView = Plugin;
    $.fn.listView.Constructor = ListView;

})(jQuery);
