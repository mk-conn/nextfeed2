import { debug } from '@ember/debug';
import { run } from '@ember/runloop';
import Service, { inject as service } from '@ember/service';
import Echo from 'laravel-echo';
import io from 'socket.io-client';

export default Service.extend({

  currentUser: service(),

  session: service(),

  notify: service(),

  /**
   * Init
   */
  init() {
    this._super(...arguments);
    this.echo = null;
  },
  /**
   * Start echo and initialize channels
   * @param user
   */
  start(user) {
    if (!this.echo) {
      this.subscribed = [];

      const selector = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = selector.getAttribute('content');

      let host = window.location.hostname;
      if (window.location.port) {
        host = host + ':' + window.location.port;
      }

      let token = this.get('session.data.authenticated.access_token');

      this.echo = new Echo({
        broadcaster: 'socket.io',
        host: host,
        // wsPath: '/ws',
        csrfToken: csrfToken,
        client: io,
        // disableStats: true,
        auth: {
          headers: {
            Authorization: 'Bearer ' + token
          }
        }
      });
    }
  },

  /**
   * Restart echo
   */
  restart(user) {
    debug('socketChannel.restart()');
    if (this.echo) {
      this.subscribed.forEach(channelId => {
        this.echo.leave(channelId);
      });
      this.echo.disconnect();

      this.echo = null;
    }
    this.start(user);
  }
});
