import Ember from 'ember';
import InfinityRoute from "ember-infinity/mixins/route";
import Gui from 'frontend/mixins/gui';

const {Route, get, set, $, inject: {service}} = Ember;

export default Route.extend(InfinityRoute, Gui, {

  displayIn: '#column-one',

  lastId: null,

  session: service(),

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

    this._super(...arguments);
  },

  renderTemplate() {
    this.render('feeds/feed/articles', {
      into: 'application',
      outlet: 'column-one'
    });
  },

  model(params) {

    const feed = this.modelFor('feeds.feed');
    let filter = {};

    if (feed.get('id') === 'archived') {
      filter[ 'keep' ] = true;
    } else {
      filter[ 'feed' ] = feed.id;
    }

    if (params.filterUnread === true) {
      filter[ 'read' ] = false;
    }

    delete params.filterUnread;

    params[ 'filter' ] = filter;
    params[ 'fields' ] = {article: 'title,description,author,keep,read,url,updated-date,categories'};

    return this.infinityModel('article', params, {});
  },

  setupController(controller, model) {
    this._super(controller, model);

    controller.set('feed', this.modelFor('feeds.feed'));
    controller.set('articleRoute', 'feeds.feed.articles.article');

  },
  /**
   *
   * @param articles
   */
  afterInfinityModel(articles) {
    const latestArticleId = articles.get('firstObject.id');

    this.set('lastId', latestArticleId);
  },

  actions: {
    /**
     *
     * @param article
     */
    read(article) {

      const feed = this.modelFor('feeds.feed');

      const decrement = article.toggleProperty('read');
      article.save().then(() => {
        if (decrement) {
          feed.decrementUnread();
        } else {
          feed.incrementUnread();
        }
      });
    },

    openArticle(article) {
      this.transitionTo('feeds.feed.articles.article', get(article, 'id'));
    },

    /**
     *
     * @param article
     */
    keep(article) {
      article.toggleProperty('keep');
      article.save();
    },
    /**
     *
     * @param feed
     */
    readAll(feed) {
      this.debug(`route %s::readAll() `, this.routeName);

      this.store.queryRecord('feed-action', {
        action: 'read',
        params: {
          feed_id: get(feed, 'id'),
          last_article_id: get(this, 'lastId')
        }
      }).then(feedAction => {
        feed.set('unreadCount', 0);
        this.refresh();
      }, error => {
        console.log('error:', error);
      });
    }
  }
});
