import Ember from 'ember';

const {Service, inject: {service}, isEmpty, RSVP, getOwner} = Ember;

export default Service.extend({
  session: service('session'),
  store: service(),

  init() {
    this._super(...arguments);

    this.user = null;
  },

  load() {

    const token = this.get('session.data.authenticated.token');

    if (!isEmpty(token)) {
      const authenticator = getOwner(this).lookup('authenticator:jwt');
      let session = this.get('session.session.content.authenticated');
      let tokenData;

      tokenData = authenticator.getTokenData(session.token);
      const user_id = tokenData.sub;
      return this.get('store').findRecord('user', user_id).then(user => {
        this.set('user', user);
      });
    }
    return RSVP.resolve();
  }
});
