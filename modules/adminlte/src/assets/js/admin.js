(function ($) {
    'use strict';
    $(function () {
        $.AdminLTE.boxWidget.activate = function () {
            var _this = this;
            //Listen for collapse event triggers
            $(document).on('click', _this.selectors.collapse, function (e) {
                e.preventDefault();
                _this.collapse($(this));
            });

            //Listen for remove event triggers
            $(document).on('click', _this.selectors.remove, function (e) {
                e.preventDefault();
                _this.remove($(this));
            });
        };
        if ($.AdminLTE.options.enableBoxWidget) {
            $.AdminLTE.boxWidget.activate();
        }
    });
}(jQuery));
