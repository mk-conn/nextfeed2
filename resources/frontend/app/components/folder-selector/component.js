import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { task } from 'ember-concurrency';

export default Component.extend({
  store: service(),
  tagName: 'select',

  init() {
    this._super(...arguments);
    this.result = null;
  },

  didReceiveAttrs() {
    this.get('getFolders').perform();
  },

  change() {
    let folderId = document.getElementById(this.elementId).value;
    console.log('folderid', folderId);
    let folder = this.store.peekRecord('folder', folderId);

    this.feed.set('folder', folder);
  },

  getFolders: task(function* () {
    return yield this.store.findAll('folder', { sort: 'name' }).then(folders => {
      this.set('result', folders);
    });
  })
});
