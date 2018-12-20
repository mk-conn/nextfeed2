import Route from '@ember/routing/route';
import { get, set } from '@ember/object';
import { inject as service } from '@ember/service';
import Gui from 'frontend/mixins/gui';
import $ from 'jquery';

export default Route.extend(Gui, {
  infinity: service(),
  displayIn: '#column-one',

  lastId: null,

  session: service(),

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
    this.render('index/feeds/feed/articles', {
      into: 'application',
      outlet: 'column-one'
    });
  },

  model(params) {
    const feed = this.modelFor('index.feeds.feed');

    let options = {
      perPage: 15,
      pageParam: 'page[number]',
      perPageParam: 'page[size]',
      totalPagesParam: 'meta.page.last-page',
      sort: '-updated-date'
    };
    delete params.sort;
    options['filter'] = {};

    Object.keys(this.get('queryParams')).forEach(queryParam => {
      if (params[queryParam]) {
        let param = queryParam.dasherize();
        options['filter'][param] = params[queryParam];
      }
    });



    let filter = {};
    if (feed.get('id') === 'archived') {
      filter['keep'] = true;
    } else {
      filter['feed'] = feed.id;
    }

    if (params.filterUnread === true) {
      filter['read'] = false;
    }

    params['filter'] = filter;
    params['fields'] = {article: 'title,description,author,keep,read,url,updated-date,categories'};

    return this.infinity.model('article', options);
  },

  setupController(controller, model) {
    this._super(controller, model);

    controller.set('feed', this.modelFor('index.feeds.feed'));
    controller.set('articleRoute', 'index.feeds.feed.articles.article');

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
      this.debug(`route: %s::read(%s)`, this.routeName, article);
      const read = get(article, 'read');
      this.debug(`\tread: %s -> will set to %s`, read, !read);
      const feed = this.modelFor('feeds.feed');
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
        let result = get(feedAction, 'result.success');

        if (result) {
          let currentUnreadCount = get(feed, 'meta.articles-unread-count');
          set(feed, 'unreadCount', (currentUnreadCount - result));
          this.refresh();
        }
      }, error => {
        console.log('error:', error);
      });
    }
  }
})
;
