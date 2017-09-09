import Ember from 'ember';

const {Controller, inject: {service}, computed} = Ember;

export default Controller.extend({
  session: service('session'),

  sessionData: computed('session.session.content.authenticated', function () {
    return JSON.stringify(this.get('session.session.content.authenticated'), null, '\t');
  }),

  tokenData: computed('session.session.content.authenticated', function () {
    var authenticator = this.container.lookup('authenticator:jwt'),
      session = this.get('session.session.content.authenticated'),
      tokenData = {};

    if (session && Object.keys(session).length > 0) {
      tokenData = authenticator.getTokenData(session.token);
    }

    return JSON.stringify(tokenData, null, '\t');
  })
});
