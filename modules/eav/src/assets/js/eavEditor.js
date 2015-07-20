(function ($, window) {

    'use strict';

    var add = '[data-action="add"]',
        remove = '[data-action="remove"]',
        select = '[data-field="attributes"]',
        temp = '[data-cont="temp"]',
        fields = '[data-cont="fields"]';

    function EAVEditor(element, options) {
        this.options = options;
        this.$element = $(element);
        this.$attributesSelect = this.$element.find(select);
        this.$temp = this.$element.find(temp);
        this.$fields = this.$element.find(fields);
        this.$element.on('click', add, $.proxy(this.onAdd, this));
        this.$element.on('click', remove, $.proxy(this.onRemove, this));
        this.$element.on('pjax:success', temp, $.proxy(this.onResponse, this));
    }

    EAVEditor.DEFAULTS = {
    };

    EAVEditor.prototype.onAdd = function (e) {
        e.preventDefault();
        var attributes = this.$attributesSelect.val();
        if (attributes) {
            this.addAttributes(attributes);
            this.$attributesSelect.val('');
        }
    };

    EAVEditor.prototype.onRemove = function (e) {
        e.preventDefault();
        $(e.currentTarget).closest('.form-group').remove();
    };

    EAVEditor.prototype.onResponse = function () {
        this.$fields.append(this.$temp.html());
        this.$temp.html('');
    };
    
    EAVEditor.prototype.setAttributes = function (attributes, add) {
        var settings = {
            type: 'POST',
            url: this.options.fieldsUrl,
            push: false,
            container: add ? temp : fields,
            data: {attributes: attributes, form: this.options.form}
        };
        $.pjax(settings);
    };

    EAVEditor.prototype.addAttributes = function (attributes) {
        this.setAttributes(attributes, true);
    };

    function EAVEditorPlugin(option) {
        var args = arguments,
            returns;
        this.each(function () {
            var $this = $(this);
            var instance = $this.data('eavEditor');
            var options = $.extend({}, EAVEditor.DEFAULTS, $this.data(), typeof option === 'object' && option);
            if (!instance) { $this.data('eavEditor', (instance = new EAVEditor(this, options))); }
            if (typeof option === 'string' && typeof instance[option] === 'function') {
                returns = instance[option].apply(instance, Array.prototype.slice.call(args, 1));
            } else {
                returns = instance;
            }
        });

        return returns !== undefined ? returns : this;
    }

    $.fn.eavEditor = EAVEditorPlugin;
    $.fn.eavEditor.Constructor = EAVEditor;

})(jQuery, window);
