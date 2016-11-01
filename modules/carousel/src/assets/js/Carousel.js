import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel/dist/assets/owl.theme.default.css';
import $ from 'jquery';
import 'imports?jQuery=jquery!owl.carousel';

const defaults = {
    
};

export class Carousel {
    constructor (container, options = defaults) {
        this.container = container;
        this.options = options;
        this.init();
    }

    init () {
        var that = this;
        $(this.container).owlCarousel({
            items: 1,
            loop: true,
            margin: 10,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            animateOut: 'fadeOut'
        });
        $(this.container).find('.item.window-size').css('height', $(window).height());
        $(window).resize(function () {
            $(that.container).find('.item.window-size').css('height', $(window).height());
        });
    }
}

export default function initCarousel(selector) {
    for (let container of document.querySelectorAll(selector)) {
        new Carousel(container);
    }
}
