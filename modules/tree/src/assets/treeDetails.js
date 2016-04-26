(function ($) {

    'use strict';

    function TreeDetails(element, options) {
        this.options = options;
        this.$element = $(element);
        this.initEvents();
    }

    TreeDetails.DEFAULTS = {
    };

    TreeDetails.prototype.initEvents = function () {
        var that = this;
//        this.$element.on('pjax:success', function(event, data, status, xhr, options) {
//
//        });
//        this.$element.on('pjax:complete', function(event, xhr, textStatus, options) {
//            //var data = $.parseJSON(xhr.responseText);
//            //console.log(data);
//            console.log(xhr.responseType);
//        });
//        this.$element.on('submit', 'form', function (event) {
//            that.submit(event, {dataType: 'json'}).done(function(data) {
//                console.log(data);
//            });
//            //$.pjax.submit(event, that.$element, {dataType: 'json', push: false, history: false, scrollTo: false});
//        });
    };

    TreeDetails.prototype.submit = function (event, options) {
        var form = event.currentTarget;
        var defaults = {
            type: form.method.toUpperCase(),
            url: form.action
        }
        if (defaults.type !== 'GET' && window.FormData !== undefined) {
            defaults.data = new FormData(form);
            defaults.processData = false;
            defaults.contentType = false;
        } else {
            if ($(form).find(':file').length) {
                return;
            }
            defaults.data = $(form).serializeArray();
        }
        options = $.extend({}, defaults, options);
        event.preventDefault();
        return $.ajax(options);
    };

    TreeDetails.prototype.create = function (url, data, settings) {
        var that = this,
            deferred = $.Deferred();
        $.pjax({container: this.$element, push: false, scrollTo: false, url: url, data: data});
        this.$element.off('submit.details', 'form');
        this.$element.on('submit.details', 'form', function (event) {
            that.submit(event, settings)
                .done(function (data, textStatus, jqXHR) {
                    if (jqXHR.responseJSON) {
                        deferred.resolve.apply(deferred, arguments);
                    } else {
                        that.$element.html(data);
                    }
                })
                .fail(function () {
                    deferred.reject.apply(deferred, arguments);
                });
        });
        return deferred.promise();
    };

    TreeDetails.prototype.edit = function (url, settings) {
        var that = this,
            deferred = $.Deferred();
        $.pjax({container: this.$element, push: false, scrollTo: false, url: url});
        this.$element.off('submit.details', 'form');
        this.$element.on('submit.details', 'form', function (event) {
            that.submit(event, settings)
                .done(function (data, textStatus, jqXHR) {
                    if (jqXHR.responseJSON) {
                        deferred.resolve.apply(deferred, arguments);
                    } else {
                        that.$element.html(data);
                    }
                })
                .fail(function () {
                    deferred.reject.apply(deferred, arguments);
                });
        });
        return deferred.promise();
    };

    function TreeDetailsPlugin(option) {
        var args = arguments,
            returns;
        this.each(function () {
            var $this = $(this);
            var instance = $this.data('treeDetails');
            var options = $.extend({}, TreeDetails.DEFAULTS, $this.data(), typeof option == 'object' && option);
            if (!instance) $this.data('treeDetails', (instance = new TreeDetails(this, options)));
            if (typeof option == 'string' && typeof instance[option] === 'function')
                returns = data[option].apply(data, Array.prototype.slice.call(args, 1));
            else
                returns = instance;
        });
        return returns !== undefined ? returns : this;
    };

    $.fn.treeDetails = TreeDetailsPlugin;
    $.fn.treeDetails.Constructor = TreeDetails;

})(jQuery);
