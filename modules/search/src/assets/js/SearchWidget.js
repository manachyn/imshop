var Bloodhound = require('typeahead.js');
var $ = require('jquery');

const defaults = {
    suggestionsUrl: '/api/v1/search/suggest/%QUERY',
    suggestionsWildcard: '%QUERY',
    suggestionsDisplayKey: 'text',
    suggestionsLimit: 10
};

export class SearchWidget {
    constructor (container, options = defaults) {
        this.container = container;
        this.options = options;
        this.init();
    }

    init () {
        var suggestions = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: this.options.suggestionsUrl,
                wildcard: this.options.suggestionsWildcard
            }
        });

        $(this.container).typeahead({
            hint: true,
            highlight: true
        }, {
            name: 'suggestions',
            display: this.options.suggestionsDisplayKey,
            source: suggestions,
            limit: this.options.suggestionsLimit
        });
    }
}

export default function initSearchWidget(selector) {
    for (let container of document.querySelectorAll(selector)) {
        new SearchWidget(container);
    }
}
