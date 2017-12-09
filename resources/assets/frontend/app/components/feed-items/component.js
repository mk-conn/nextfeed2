import Component from '@ember/component';
import { computed } from '@ember/object';

export default Component.extend({
  sortBy: [ 'order' ],

  sortedFeeds: computed.sort('feeds', 'sortBy')
});
