import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';
import ENV from '../config/environment';

const {Route} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {

  model: function () {
    const adapter = this.container.lookup('adapter:application');

    return adapter.ajax((ENV['API_URL'] || '') + '/api/users/', 'GET');
  },

  setupController: function (controller, model) {
    if (!model.username) {
      this.get('session').invalidate();
    }
  }
});
