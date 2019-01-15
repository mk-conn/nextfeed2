import Component from '@ember/component';
import { task } from 'ember-concurrency';
import { inject as service } from '@ember/service';
import { run } from '@ember/runloop';
import { isPresent } from '@ember/utils';
import $ from 'jquery';

export default Component.extend({
  /**
   * Init
   */
  init() {
    this._super(...arguments);
    this.protocols = ['http://', 'https://'];
    this.selectedProtocol = 0;
  },
  store: service(),
  session: service(),
  validateUrl() {
    this.set('url', this.url.replace(/https?:\/\//, ''));
  },
  discover: task(function* () {
    this.set('result', null);
    this.set('errors', null);

    if (this.get('url')) {

      const appAdapter = this.store.adapterFor('application');
      let {access_token} = this.session.data.authenticated;
      let url = `api/actions/feeds/discover`;
      this.validateUrl();
      let discoverUrl = `${this.protocols[this.selectedProtocol]}${this.get('url') || ''}`;

      let data = {
        url: discoverUrl
      };
      let result = yield $.getJSON({
        url: url,
        data: data,
        headers: {
          Authorization: `Bearer ${access_token}`
        }
      }).catch(err => {
        run.next(this, () => {
          let errors = [];

          if (isPresent(err.responseJSON.errors.url)) {
            let urlError = err.responseJSON.errors.url;
            errors.push({message: urlError.join("<br />")})
          } else if (isPresent(err.responseJSON.errors.message)) {
            let errMessage = err.responseJSON.errors.message;
            errors.push({message: errMessage})
          }
          this.set('errors', errors);
        });
      });
      this.set('result', result);
    }
  }).drop(),
  actions: {
    /**
     *
     * @param {Event} select
     */
    setProtocol(select) {
      let value = select.currentTarget.selectedOptions[0].getAttribute('value');
      this.set('selectedProtocol', value);
    },

    selectLink(link) {
      this.feed.set('feedUrl', link.href);
    }
  }
});

