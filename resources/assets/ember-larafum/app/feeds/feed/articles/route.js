import Ember from 'ember';
import InfinityRoute from "ember-infinity/mixins/route";

const {Route, RSVP, set} = Ember;

export default Route.extend(InfinityRoute, {

  perPageParam: 'page[size]',

  pageParam: 'page[number]',

  totalPagesParam: 'meta.page.last-page',

  queryParams: {
    sort: {
      refreshModel: true
    }
  },

  renderTemplate() {
    this.render('feeds.feed.articles', {
      into: 'application',
      outlet: 'column-one'
    })
  },

  model(params) {
    const feed = this.modelFor('feeds.feed');

    params[ 'filter' ] = {feed: feed.id};
    params[ 'fields' ] = {articles: 'title,description,author,keep,read,url,updated-date,categories'};

    return this.infinityModel('article', params);

    // return RSVP.hash({
    //   articles: this.get('store').query('article', params),
    //   feed: feed
    // });
  },

  setupController(controller, model) {
    this._super(controller, model);

    controller.set('feed', this.modelFor('feeds.feed'));
  },

  actions: {

    read(article) {
      article.set('read', true);
      article.save();
    },

    readAll() {

    }
  }
});
