import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';
import ENV from '../config/environment';

const {Route} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {

  model: function () {

  },

  setupController: function (controller, model) {

  }
});
