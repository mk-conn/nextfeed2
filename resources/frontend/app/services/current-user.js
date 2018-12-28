import { debug } from '@ember/debug';
import Service, { inject as service } from '@ember/service';
import RSVP from 'rsvp';

export default Service.extend({
  session: service(),
  store: service(),
  /**
   * Init
   */
  init() {
    this._super(...arguments);
  },

  /**
   *
   * @returns {*}
   */
  load() {
    debug('current-user:load()');

    if (!this.user && this.get('session').isAuthenticated) {
      debug('user not set, loading user...');

      return this.get('store').queryRecord('user', {filter: {me: true}}).then((user) => {
        this.set('user', user);
      });
    } else {
      return RSVP.resolve();
    }
  }
});
