import Component from '@ember/component';
import { task } from 'ember-concurrency';

export default Component.extend({

  tagName: 'select',

  getFolders: task(function* () {
    return yield this.store.findAll('folder');
  })
});
