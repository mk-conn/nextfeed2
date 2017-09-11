import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, getOwner, inject : {service}} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {


  model: function () {

    // const adapter = getOwner(this).lookup('adapter:application');
    //
    // return adapter.ajax('api/current-user', 'GET');

  },

  setupController(controller, model) {
    // if (!model.username) {
    //   this.get('session').invalidate();
    // }
  }
});
