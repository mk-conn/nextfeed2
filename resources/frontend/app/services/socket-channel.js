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
    this.subscribed = [];

    const selector = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = selector.getAttribute('content');

    let host = window.location.hostname;
    if (window.location.port) {
      host = host + ':' + window.location.port;
    }

    this.echo = new Echo({
      broadcaster: 'socket.io',
      host: host,
      // wsPath: '/ws',
      csrfToken: csrfToken,
      client: io,
      auth: {
        headers: {
          Authorization: 'Bearer ' + this.get('session.data.authenticated.access_token')
        }
      }
    });

    const listenOnPrivate = () => {
      const channelId = `App.User.${ this.currentUser.user.id }`;
      this.echo.private(channelId).notification((notification) => {
        const msg = `<span>${ notification.type }</span>`;
        notify({ html: msg });
      });
      this.subscribed.push(channelId);
    };

    run.next(this, listenOnPrivate);


    // user.get('settings').then((settings) => {
    //   // const notifications = settings.findBy('key', 'notifications');
    //   const notifications = true;
    //
    //   if (notifications) {
    //
    //     let notification = false;
    //     const value = notifications.get('value');
    //     let keys = Object.keys(value);
    //
    //     const notify = (msg) => {
    //       this.notify.info(msg);
    //     };
    //
    //     keys.forEach((key) => {
    //       // todo: optimize this loop
    //       if (value[key]['browser'] === true) {
    //         notification = true;
    //       }
    //     });
    //
    //     if (!this.echo && notification) {
    //
    //       try {
    //         let host = window.location.hostname;
    //         if (window.location.port) {
    //           host = host + ':' + window.location.port;
    //         }
    //         debug(`socket-host: ${ host }`);
    //         const selector = document.querySelector('meta[name="csrf-token"]');
    //         const csrfToken = selector.getAttribute('content');
    //         this.echo = new Echo({
    //           broadcaster: 'socket.io',
    //           host: host,
    //           csrfToken: csrfToken,
    //           client: io,
    //           auth: {
    //             headers: {
    //               Authorization: 'Bearer ' + this.get('session.data.authenticated.access_token')
    //             }
    //           }
    //         });
    //
    //         const listenOnPrivate = () => {
    //           const channelId = `App.User.${ this.currentUser.user.id }`;
    //           this.echo.private(channelId).notification((notification) => {
    //             const msg = `<span>${ notification.type }</span>`;
    //             notify({ html: msg });
    //           });
    //           this.subscribed.push(channelId);
    //         };
    //
    //         const listenOnMessages = () => {
    //           this.echo.channel("messages").listen('.patch-released', (e) => {
    //             const msg = `<span>A new patch <a href="/patches/patch/${ e.slug }"><strong>${ e.name }</strong></a> for <strong>${ e.instrument }</strong> was created</span>`;
    //             notify({ html: msg });
    //           });
    //           this.subscribed.push("messages");
    //         };
    //
    //         run.next(this, listenOnPrivate);
    //         run.next(this, listenOnMessages);
    //       } catch (e) {
    //         console.log(e);
    //       }
    //     }
    //   }
    // });
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
