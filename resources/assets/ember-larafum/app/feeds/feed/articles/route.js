import Ember from 'ember';

const {Route, RSVP} = Ember;

export default Route.extend({

  queryParams: {
    sort: {
      refreshModel: true
    }
  },

  renderTemplate() {
    this.render('feeds.feed.articles', {
      into: 'feeds',
      outlet: 'article-list'
    })
  },

  model(params) {
    const feed = this.modelFor('feeds.feed');
    params[ 'filter' ] = {feed: feed.id};

    return RSVP.hash({
      articles: this.get('store').query('article', params),
      feed: feed
    });
  }
});
