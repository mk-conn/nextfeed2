import { PerfectScrollbarMixin } from 'ember-perfect-scrollbar';
import Component from '@ember/component';

export default Component.extend(PerfectScrollbarMixin, {
  /**
   * Init
   */
  init() {
    this._super(...arguments);
    this.perfectScrollbarOptions = {
      suppressScrollX: true
    };
  }
});
