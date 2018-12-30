import Component from '@ember/component';

export default Component.extend({
  tagName: 'button',
  classNames: ['scroll-top'],

  init() {
    this._super(...arguments);
    this.set('title', 'Scroll to top');
  },
  scrollFunction(elToWatch) {
    const scrollTopBtn = document.getElementById(this.get('elementId'));

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
