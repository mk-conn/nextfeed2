import Ember from 'ember';

const {Controller, inject: {service}} = Ember;

export default Controller.extend({
  session: service('session'),

  actions: {
    authenticate: function () {

      let credentials = this.getProperties('identification', 'password'),
        authenticator = 'authenticator:jwt';

      this.get('session').authenticate(authenticator, credentials);
    }
  }
});
