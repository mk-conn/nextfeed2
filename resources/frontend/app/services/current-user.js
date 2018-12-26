import Service, { inject as service } from '@ember/service';
import RSVP from 'rsvp';
import $ from 'jquery';

export default Service.extend({

  session: service('session'),
  store: service(),

  load() {
    if (this.get('session.isAuthenticated')) {

      return $.ajax('/auth/me', {
        method: 'GET',
        headers: {
          Authorization: 'Bearer ' + this.get('session.session.content.authenticated.access_token')
        }
      }).then((user) => {
        this.set('user', user);
      });
    } else {
      return RSVP.resolve();
    }
  }
});
