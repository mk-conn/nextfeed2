import { debug } from '@ember/debug';
import { get, set } from '@ember/object';
import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import Gui from 'frontend/mixins/gui';
import $ from 'jquery';
import { task } from 'ember-concurrency';
import InfinityModel from 'ember-infinity/lib/infinity-model';

const meta = {
  lastArticleId: null
};

const ExtendedInfinityModel = InfinityModel.extend({
  /**
   *
   * @param articles
   */
  afterInfinityModel(articles) {
    meta.lastArticleId = articles.get('firstObject.id');
  }
});

export default Route.extend(Gui, {
  infinity: service(),
  displayIn: '#column-one',

  lastId: null,

  session: service(),

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
    this.render('index/feed/articles', {
      into: 'application',
      outlet: 'column-one'
    });
  },

  model(params) {
    const feed = this.modelFor('index.feed');

    let options = {
      perPage: 15,
      pageParam: 'page[number]',
      perPageParam: 'page[size]',
      totalPagesParam: 'meta.page.last-page',
      sort: '-updated-date',
      feed: feed.id
    };
    let filter = {};
    if (feed.get('id') === 'archived') {
      filter['keep'] = true;
    } else {
      filter['feed'] = feed.id;
    }

    if (params.filterUnread === true) {
      filter['read'] = false;
    }

    options['filter'] = filter;
    options['fields'] = {article: 'title,description,author,keep,read,url,updated-date,categories'};

    return this.infinity.model('article', options, ExtendedInfinityModel);
  },

  setupController(controller, model) {
    this._super(controller, model);

    controller.set('feed', this.modelFor('index.feed'));
    controller.set('articleRoute', 'index.feed.articles.article');

  },

  /**
   * Mark all articles known until now (thats why the lastArticleId) as read
   */
  markAllRead: task(function* (feedId, lastArticleId) {
    this.store.adapterFor('application');
    const appAdapter = this.get('store').adapterFor('application');
    const urlPrefix = appAdapter.getUrlPrefix();
    let {access_token} = this.session.data.authenticated;
    let url = `/${urlPrefix}/feeds/${feedId}/mark-read/${lastArticleId}`;
    let xhr;
    try {
      xhr = $.getJSON({
        url: url,
        headers: {
          Authorization: `Bearer ${access_token}`
        },
      });
      return yield xhr.promise();
    } finally {
      xhr.abort();
    }
  }),

  actions: {
    /**
     *
     * @param article
     */
    read(article) {
      this.debug(`route: %s::read(%s)`, this.routeName, article);
      const read = get(article, 'read');
      this.debug(`\tread: %s -> will set to %s`, read, !read);
      const feed = this.modelFor('index.feed');
      const decrement = article.toggleProperty('read');
      article.save().then(() => {
        if (feed) {
          if (decrement) {
            feed.decrementUnread();
          } else {
            feed.incrementUnread();
          }
        }
      });
    },

    openArticle(article) {
      this.transitionTo('index.feed.articles.article', get(article, 'id'));
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
    markAllRead() {
      this.debug(`route %s::readAll() `, this.routeName);
      const feed = this.modelFor('index.feed');
      this.get('markAllRead').perform(feed.id, meta.lastArticleId).then((result) => {
        let currentUnreadCount = get(feed, 'meta.articles-unread-count');
        set(feed, 'unreadCount', (currentUnreadCount - result));
        this.refresh();
      });
    }
  }
});
