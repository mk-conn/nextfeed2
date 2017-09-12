import Ember from 'ember';

const {Route} = Ember;

export default Route.extend({

  queryParams: {
    sort: {
      refreshModel: true
    }
  },

  model() {
    const feed = this.modelFor('feeds.feed');

    return this.get('store').query('article', {filter: {feed: feed.id}})
  }
});
