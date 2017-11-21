// noinspection NpmUsedModulesInstalled
import Service, {inject as service} from '@ember/service';
import {get} from '@ember/object';
// noinspection NpmUsedModulesInstalled
import {getOwner} from '@ember/application';
// noinspection NpmUsedModulesInstalled
import RSVP from 'rsvp';

export default Service.extend({

  session: service('session'),

  store: service(),

  init() {
    this._super(...arguments);

    this.user = null;
  },

  load() {
    let authenticator = getOwner(this).lookup('authenticator:jwt'),
      session = this.get('session.session.content.authenticated'),
      tokenData = {};

    if (session && Object.keys(session).length > 0) {
      tokenData = authenticator.getTokenData(session.access_token);

      $.ajax('/auth/me', {
        method: 'POST',
        headers: {
          Authorization: 'Bearer' + session.access_token
        }
      }).then((result) => {
        this.set('user', result);
      });
      return RSVP.resolve();
    }
  }
});
