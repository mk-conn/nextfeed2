import Component from '@ember/component';

const ELEMENT_ID = 'scrollTopBtn';


export default Component.extend({
  tagName: 'button',
  classNames: ['scroll-top'],

  init() {
    this._super(...arguments);
    this.set('elementId', ELEMENT_ID);
    this.set('title', 'Scroll to top');
  },
  scrollFunction(elToWatch) {
    const scrollTopBtn = document.getElementById(ELEMENT_ID);

    if (elToWatch.scrollTop > 20) {
      scrollTopBtn.classList.add('show');
      scrollTopBtn.style.display = "block";
    } else {
      scrollTopBtn.style.display = "none";
    }

    // if (document[elToWatch].scrollTop > 20 || document.documentElement.scrollTop > 20) {
    // } else {
    // }
  },
  click() {
    this.get('elToWatch').scrollTop = 0;
  },

  didRender() {
    let elToWatch = this.get('elementToWatch');
    if (elToWatch) {
      elToWatch = document.getElementById(elToWatch);
    } else {
      elToWatch = window;
    }

    this.set('elToWatch', elToWatch);

    elToWatch.onscroll = () => {
      this.scrollFunction(elToWatch)
    };
  }
});
