// noinspection NpmUsedModulesInstalled
import Service, {inject as service} from '@ember/service';
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

    const token = this.get('session.data.authenticated.token');

    if (token) {
      const authenticator = getOwner(this).lookup('authenticator:jwt');
      let session = this.get('session.session.content.authenticated');
      let tokenData;

      tokenData = authenticator.getTokenData(session.token);
      const user_id = tokenData.sub;

      $.ajax(host + '/auth/me', {
        method: 'POST',
        headers: {
          Authorization: 'Bearer' + session.token
        }
      }).then((result) => {
        this.set('user', result.user);
      });

      // return this.get('store').findRecord('user', user_id).then(user => {
      //   this.set('user', user);
      // });
    }
    return RSVP.resolve();
  }
});
