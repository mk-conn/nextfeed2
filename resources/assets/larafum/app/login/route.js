import Ember from 'ember';

const {Route, inject: {service}} = Ember;

export default Route.extend({
  session: service('session'),

  actions: {
    authenticate: function () {
      let credentials = this.getProperties('identification', 'password'),
        authenticator = 'authenticator:jwt';

      this.get('session').authenticate(authenticator, credentials);
    }
  }
});
