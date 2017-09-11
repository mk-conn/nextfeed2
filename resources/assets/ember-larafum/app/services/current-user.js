import Ember from 'ember';

const {inject: {service}, RSVP, getOwner} = Ember;

export default Ember.Service.extend({
  session: service('session'),
  store: service(),

  load() {
    if (this.get('session.isAuthenticated')) {
      const authenticator = getOwner(this).lookup('authenticator:jwt');

      let session = this.get('session.session.content.authenticated');
      console.log('session:', session);
      let tokenData;

      if (session && Object.keys(session).length > 0) {
        tokenData = authenticator.getTokenData(session.token);
        console.log('tokenData:', tokenData);
      }

      const adapter = getOwner(this).lookup('adapter:application');
      //
      return adapter.ajax('api/auth-user', 'GET').then(user => {
        this.set('user', user);
      });
      // adapter - authentication/auth-user
      //   return this.get('store').queryRecord('user', {me: true}).then((user) => {
      //     this.set('user', user);
      //   });
    } else {
      return RSVP.resolve();
    }
  }
});
