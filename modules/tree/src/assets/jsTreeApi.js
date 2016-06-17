(function ($, window) {

    'use strict';

    var replace = (function () {
        var replace = function (data) {
            return function (s, name) {
                return data[name] || '';
            };
        };
        return function (template, data) {
            var tmp = template.replace(/{\s*([\w-]+)\s*}/g, replace(data));
            return tmp.replace(/%7B\+*([\w-]+)\+*%7D/g, replace(data));
        };
    })();

    function JsTreeApi(element, options) {
        this.options = options;
        this.$element = $(element);
        this.tree = false;
        if (this.options.treeDetails) {
            this.treeDetails = $('#' + this.options.treeDetails).treeDetails();
        }
        this.$element.on('init.jstree', $.proxy(this.initTree, this));
        this.notPersisted = [];
        this.searchTimeout = false;
    }

    JsTreeApi.DEFAULTS = {
    };

    JsTreeApi.prototype.initEvents = function () {
        this.$element.on('ready.jstree', $.proxy(this.ready, this));
        this.$element.on('load_node.jstree', $.proxy(this.load, this));
        this.$element.on('create_node.jstree', $.proxy(this.create, this));
        this.$element.on('edit_node.jstree', $.proxy(this.edit, this));
        this.$element.on('delete_node.jstree', $.proxy(this.remove, this));
        this.$element.on('rename_node.jstree', $.proxy(this.rename, this));
        this.$element.on('move_node.jstree', $.proxy(this.move, this));
        this.$element.on('changed.jstree', $.proxy(this.selectionChanges, this));
        this.$element.on('dblclick.jstree', ".jstree-anchor", $.proxy(function (e) {
            this.trigger('edit_node', {node: this.get_node(e.target)});
        }, this.tree));
        $('body').on('keyup', this.options.searchInput, $.proxy(this.search, this));
        this.$element.on('check_node.jstree', $.proxy(this.checkNode, this));
        this.$element.on('uncheck_node.jstree', $.proxy(this.uncheckNode, this));
    };

    JsTreeApi.prototype.initTree = function () {
        this.tree = this.$element.jstree(true);
        this.tree.settings.core.check_callback = $.proxy(this.checkCallback, this);
        if (this.options.attributesMap) {
            this.tree.settings.core.data.dataFilter = $.proxy(this.dataFilter, this);
            this.tree.settings.core.data.beforeSend = $.proxy(this.beforeSend, this);
        }
        this.tree.settings.search.ajax.dataFilter = $.proxy(this.searchDataFilter, this);
        this.tree.settings.search.ajax.beforeSend = $.proxy(this.searchBeforeSend, this);
        this.tree.settings.search.search_callback = $.proxy(this.searchCallback, this);
        this.initEvents();
    };

    JsTreeApi.prototype.load = function () {
        if (this.options.opened) {
            this.openNodes(this.options.opened);
        }
        if (this.options.checked) {
            this.checkNodes(this.options.checked);
        }
    };

    JsTreeApi.prototype.ready = function () {
        if (this.options.checked) {
            var that = this,
                promise = this.getParents(this.options.checked);
            promise.done(function (parents) {
                that.options.opened = parents;
                that.openNodes(that.options.opened);
                that.checkNodes(that.options.checked);
            });
        }
    };

    JsTreeApi.prototype.openNodes = function (ids) {
        this.tree.open_node(ids);
    };

    JsTreeApi.prototype.checkNodes = function (ids) {
        this.tree.check_node(ids);
    };

    JsTreeApi.prototype.checkCallback = function (operation, node, node_parent, node_position, more) {
        //node.delete_with_children = true;
        //return operation === 'delete_node' ? confirm('Are you sure you want to delete?') : true;
        //return operation === 'delete_node' ? false : true;
        if (operation === 'delete_node') {
            var res;
            if ($.inArray(node.id, this.notPersisted) > -1) {
                res = true;
            } else {
                res = window.confirm('Are you sure you want to delete?');
            }
            return res;
        }
        return true;
    };

    JsTreeApi.prototype.create = function (e, data) {
        var nodeData = {position: data.position},
            parent,
            promise,
            settings = {};
        this.deleteNotPersisted();
        this.notPersisted.push(data.node.id);
        if (data.node.parent && data.node.parent !== '#') {
            parent = data.node.parent;
            nodeData.parent = data.node.parent;
        }
        if (this.tree.settings.core.data.beforeSend) {
            settings.beforeSend = this.tree.settings.core.data.beforeSend;
        }
        if (this.tree.settings.core.data.dataFilter) {
            settings.dataFilter = this.tree.settings.core.data.dataFilter;
        }
        promise = this.treeDetails.create(this.options.createUrl, nodeData, settings);
        promise.done(function (res) {
            data.instance.set_id(data.node, res.id);
            data.instance.set_text(data.node, res.text);
            data.instance.trigger('edit_node', {node: data.node});
        });
        promise.fail(function () {
            if (parent) {
                data.instance.refresh_node(parent);
            } else {
                data.instance.refresh();
            }
        });
    };

    JsTreeApi.prototype.edit = function (e, data) {
        var that = this,
            editUrl = replace(this.options.editUrl, data.node),
            promise,
            parent,
            settings = {};
        this.deleteNotPersisted();
        if (this.treeDetails) {
            if (this.tree.settings.core.data.beforeSend) {
                settings.beforeSend = this.tree.settings.core.data.beforeSend;
            }
            if (this.tree.settings.core.data.dataFilter) {
                settings.dataFilter = this.tree.settings.core.data.dataFilter;
            }
            if (data.parent && data.parent !== '#') {
                parent = data.parent;
            }
            promise = this.treeDetails.edit(editUrl, settings);
            promise.done(function (res) {
                data.instance.set_text(data.node, res.text);
                that.edit(e, data);
            });
            promise.fail(function () {
                if (parent) {
                    data.instance.refresh_node(parent);
                } else {
                    data.instance.refresh();
                }
            });
        } else {
            window.location.href = editUrl;
        }
    };

    JsTreeApi.prototype.remove = function (e, data) {
        var deleteUrl = replace(this.options.deleteUrl, data.node),
            withChildren = data.node.delete_with_children || false,
            isParent = data.instance.is_parent(data.node),
            parent,
            index;
        index = $.inArray(data.node.id, this.notPersisted);
        if (index > -1) {
            this.notPersisted.splice(index, 1);
        } else {
            if (withChildren) {
                deleteUrl += '&children=1';
            }
            if (data.parent && data.parent !== '#') {
                parent = data.parent;
            }
            $.ajax({
                url: deleteUrl,
                type: 'DELETE'
            })
                .done(function () {
                    if (!withChildren && isParent && parent) {
                        data.instance.refresh_node(parent);
                    }
                })
                .fail(function () {
                    if (parent) {
                        data.instance.refresh_node(parent);
                    } else {
                        data.instance.refresh();
                    }
                });
        }
    };

    JsTreeApi.prototype.rename = function (e, data) {
        var updateUrl = replace(this.options.updateUrl, data.node),
            settings = {url: updateUrl, type: 'PUT', dataType: 'json', data: {}, processData: false},
            parent;
        if (data.text !== data.old) {
            if (data.parent && data.parent !== '#') {
                parent = data.parent;
            }
            settings.data.text = data.text;
            if (this.tree.settings.core.data.beforeSend) {
                settings.beforeSend = this.tree.settings.core.data.beforeSend;
            }
            if (this.tree.settings.core.data.dataFilter) {
                settings.dataFilter = this.tree.settings.core.data.dataFilter;
            }
            $.ajax(settings)
                .done(function (res) {
                    data.instance.set_text(data.node, res.text);
                })
                .fail(function () {
                    if (parent) {
                        data.instance.refresh_node(parent);
                    } else {
                        data.instance.refresh();
                    }
                });
        }
    };

    JsTreeApi.prototype.move = function (e, data) {
        var moveUrl = replace(this.options.moveUrl, data.node),
            settings = {url: moveUrl, type: 'PUT', dataType: 'json', data: {parent: data.parent, position: data.position}},
            parent;
        if (data.parent && data.parent !== '#') {
            parent = data.parent;
        }
        $.ajax(settings)
            .done(function (res) {

            })
            .fail(function () {
                if (parent) {
                    data.instance.refresh_node(parent);
                } else {
                    data.instance.refresh();
                }
            });
    };

    JsTreeApi.prototype.selectionChanges = function (e, data) {

    };

    JsTreeApi.prototype.search = function (e) {
        var that = this;
        if (this.searchTimeout) {
            window.clearTimeout(this.searchTimeout);
        }
        this.searchTimeout = window.setTimeout(function () {
            var str = $(e.currentTarget).val();
            that.tree.search(str);
        }, 500);
    };

    JsTreeApi.prototype.checkNode = function (e, data) {
        var id = parseInt(data.node.id);
        if (this.options.input && $.inArray(id, this.options.checked) === -1) {
            if (!this.options.input.$element) {
                this.options.input.$element = $('#' + this.options.input.id);
            }
            var name = this.options.multiple ? this.options.input.name + '[]' : this.options.input.name;
            this.options.input.$element.append('<input type="hidden" name="' + name + '" value="' + id + '" />');
            this.options.checked.push(id);
        }
    };

    JsTreeApi.prototype.uncheckNode = function (e, data) {
        var id = parseInt(data.node.id),
            index = $.inArray(id, this.options.checked);
        if (this.options.input && index > -1) {
            if (!this.options.input.$element) {
                this.options.input.$element = $('#' + this.options.input.id);
            }
            this.options.input.$element.find('input[value="' + id + '"]').remove();
            this.options.checked.splice(index, 1);
        }
    };

    JsTreeApi.prototype.deleteNotPersisted = function () {
        var that = this;
        if (this.notPersisted.length > 0) {
            $.each(this.notPersisted, function (index, value) {
                that.tree.delete_node(value);
            });
        }
    };

    JsTreeApi.prototype.dataFilter = function (data, type) {
        var that = this;
        data = JSON.parse(data);
        if (data instanceof Array) {
            $.each(data, function (i, item) {
                data[i] = $.extend({data: item}, that.mapFrom(item, that.options.attributesMap));
//                if (that.options.selected && $.inArray(data[i].id, that.options.selected) > -1) {
//                    data[i].state = {};
//                    data[i].state.selected = true;
//                }
            });
        } else {
            data = $.extend({data: data}, this.mapFrom(data, this.options.attributesMap));
        }
        return JSON.stringify(data);
    };

    JsTreeApi.prototype.beforeSend = function (jqXHR, settings) {
        if (typeof settings.data === 'object' && (window.FormData === undefined || !(settings.data instanceof window.FormData))) {
            settings.data = this.mapTo(settings.data, this.options.attributesMap);
            settings.data = $.param(settings.data);
        }
    };

    JsTreeApi.prototype.mapFrom = function (data, map) {
        var mapped = {};
        $.each(map, function (to, from) {
            if (data[from] !== undefined) {
                mapped[to] = data[from];
            }
        });
        return mapped;
    };

    JsTreeApi.prototype.mapTo = function (data, map) {
        var mapped = {};
        $.each(map, function (from, to) {
            if (data[from] !== undefined) {
                mapped[to] = data[from];
            }
        });
        return mapped;
    };

    JsTreeApi.prototype.searchDataFilter = function (data, type) {
        var parsedData = [];
        data = JSON.parse(data);
        $.each(data, function (i, item) {
            parsedData.push(item.id);
        });
        return JSON.stringify(parsedData);
    };

    JsTreeApi.prototype.searchBeforeSend = function (jqXHR, settings) {
        if (typeof settings.data === 'object') {
            settings.data = this.mapTo(settings.data, this.options.attributesMap);
            settings.data = $.param(settings.data);
        }
    };

    JsTreeApi.prototype.getParents = function (ids) {
        var that = this,
            deferred = $.Deferred(),
            results = [],
            parents = [],
            settings = {dataType: 'json'};

        if (this.tree.settings.core.data.beforeSend) {
            settings.beforeSend = this.tree.settings.core.data.beforeSend;
        }
        if (this.tree.settings.core.data.dataFilter) {
            settings.dataFilter = this.tree.settings.core.data.dataFilter;
        }
        $.each(ids, function (i, id) {
            settings.url = replace(that.options.parentsUrl, {id: id});
            results.push($.ajax(settings));
        });
        $.when.apply(this, results).done(function () {
            var args = results.length === 1 ? [arguments] : arguments;
            for (var i = 0; i < args.length; i++) {
                $.each(args[i][0], function (i, item) {
                    if ($.inArray(item.id, parents) === -1) {
                        parents.push(item.id);
                    }
                });
            }
            deferred.resolve(parents);
        });
        return deferred.promise();
    };

    JsTreeApi.prototype.searchCallback = function (string, node) {
        var f = new $.vakata.search(string, true, {caseSensitive: false, fuzzy: false});
        if (this.options.searchableAttributes) {
            for (var i = 0; i < this.options.searchableAttributes.length; i++) {
                var attr = this.options.searchableAttributes[i];
                var parts = attr.split('.');
                var match = false;
                if (parts[1] !== undefined && parts[0] === 'data') {
                    match = f.search(node.data[parts[1]]).isMatch;
                } else {
                    match = f.search(node[attr]).isMatch;
                }
                if (match) { return true; }
            }
            return false;
        } else {
            return f.search(node.text).isMatch;
        }
    };

    function JsTreeApiPlugin(option) {
        var args = arguments,
            returns;
        this.each(function () {
            var $this = $(this);
            var instance = $this.data('jsTreeApi');
            var options = $.extend({}, JsTreeApi.DEFAULTS, $this.data(), typeof option === 'object' && option);
            if (!instance) { $this.data('jsTreeApi', (instance = new JsTreeApi(this, options))); }
            if (typeof option === 'string' && typeof instance[option] === 'function') {
                returns = instance[option].apply(instance, Array.prototype.slice.call(args, 1));
            } else {
                returns = instance;
            }
        });

        return returns !== undefined ? returns : this;
    }

    $.fn.jsTreeApi = JsTreeApiPlugin;
    $.fn.jsTreeApi.Constructor = JsTreeApi;

})(jQuery, window);
