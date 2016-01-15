var Bloodhound = require('typeahead.js');
var $ = require('jquery');

export class SearchWidget {
    constructor (container) {
        this.container = container;
        this.init();
    }
    init () {
        var bestPictures = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '../data/films/queries/%QUERY.json',
                wildcard: '%QUERY'
            }
        });

        $(this.container).typeahead(null, {
            name: 'best-pictures',
            display: 'value',
            source: bestPictures
        });
    }
}

export default function initSearchWidget(selector) {
    for (let container of document.querySelectorAll(selector)) {
        new SearchWidget(container);
    }
}
