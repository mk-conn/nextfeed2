import Component from '@ember/component';
import { run } from '@ember/runloop';
import { inject as service } from '@ember/service';
import { isPresent } from '@ember/utils';
import { task } from 'ember-concurrency';

export default Component.extend({
  tasks: service(),
  store: service(),
  session: service(),
  /**
   * Init
   */
  init() {
    this._super(...arguments);
    this.protocols = ['https://', 'http://'];
    this.selectedProtocol = 0;
  },
  /**
   *
   */
  validateUrl() {
    this.set('url', this.url.replace(/https?:\/\//, ''));
  },
  discover: task(function* () {
    this.set('result', null);
    this.set('errors', null);

    if (this.get('url')) {
      this.validateUrl();
      let url = `${ this.protocols[this.selectedProtocol] }${ this.get('url') || '' }`;

      return yield this.get('tasks').discoverFeed(url).then(result => {
        this.set('result', result);
      });
    }
  }).drop(),
  actions:
    {
      /**
       *
       * @param {Event} select
       */
      setProtocol(select) {
        let value = select.currentTarget.selectedOptions[0].getAttribute('value');
        this.set('selectedProtocol', value);
      }
      ,
      /**
       *
       * @param link
       */
      selectLink(link) {
        this.feed.set('feedUrl', link.href);
      }
    }
})
;

