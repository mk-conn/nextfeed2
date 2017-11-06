import Ember from 'ember';
import { PerfectScrollbarMixin } from 'ember-perfect-scrollbar';

const {Component} = Ember;

export default Component.extend(PerfectScrollbarMixin, {
  classNames: [ 'side-bar', 'enabled' ],
  perfectScrollbarOptions: {
    suppressScrollX: true
  },
});
