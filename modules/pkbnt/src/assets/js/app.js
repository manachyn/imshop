'use strict';

import initSearchWidget from 'im/search/SearchWidget';
import initCarousel from 'im/carousel/Carousel';
import carousel from 'bootstrap/js/carousel';
import transition from 'bootstrap/js/transition';

function fixListItemHeight() {

}

document.addEventListener('DOMContentLoaded', () => {
    initSearchWidget('[data-component="search-form"] input');
    initCarousel('[data-component="carousel"]');
});