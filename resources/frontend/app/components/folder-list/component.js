import Component from '@ember/component';
import { computed } from '@ember/object';

export default Component.extend({

  init() {
    this._super(...arguments);
    this.sorting = ['name:asc']
  },

  sortedFolders: computed.sort('folders', 'sorting')
});
