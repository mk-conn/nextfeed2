import Ember from 'ember';
import InfinityRoute from "ember-infinity/mixins/route";

const {Route, RSVP, set, $} = Ember;

export default Route.extend(InfinityRoute, {

  perPageParam: 'page[size]',

  pageParam: 'page[number]',

  totalPagesParam: 'meta.page.last-page',

  queryParams: {
    sort: {
      refreshModel: true
    },
    filterUnread: {
      refreshModel: true
    }
  },

  beforeModel() {
    $('#article-list-items').animate({scrollTop: 0, duration: 400});
  },

  renderTemplate() {
    this.render('feeds.feed.articles', {
      into: 'application',
      outlet: 'column-one'
    })
  },

  model(params) {
    const feed = this.modelFor('feeds.feed');

    let filter = {
      feed: feed.id
    };

    if (params.filterUnread === true) {
      filter[ 'read' ] = false;
    }

    delete params.filterUnread;

    params[ 'filter' ] = filter;
    params[ 'fields' ] = {articles: 'title,description,author,keep,read,url,updated-date,categories'};
    params[ 'page' ] = {size: 10};

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
      article.toggleProperty('read');
      article.save();
    },

    keep(article) {
      article.toggleProperty('keep');
      article.save();
    },

    readAll() {

    }
  }
});
