'use strict';

import initSearchWidget from 'im/search/SearchWidget';

document.addEventListener('DOMContentLoaded', () => {
    initSearchWidget('[data-component="search-form"] input');
});