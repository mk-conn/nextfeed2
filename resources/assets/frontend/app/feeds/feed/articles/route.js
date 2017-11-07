import Ember from 'ember';
import InfinityRoute from "ember-infinity/mixins/route";
import Gui from 'frontend/mixins/gui';

const {Route, get, $, inject: {service}} = Ember;

export default Route.extend(InfinityRoute, Gui, {

  displayIn: '#column-one',

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

  actions: {
    /**
     *
     * @param article
     */
    read(article, callback) {
      return article.save().then(article => {
        const feed = this.modelFor('feeds.feed');
        const decrement = article.toggleProperty('read');
        if (feed.id !== 'archived') {
          if (decrement) {
            feed.decrementUnread();
          } else {
            feed.incrementUnread();
          }
        }

        if (callback) {
          callback();
        }
      });
    },

    readAndOpenArticle(article) {
      let _this = this;
      this.send('read', article, function () {
        _this.transitionTo('feeds.feed.articles.article', get(article, 'id'));
      });
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
      const adapter = this.get('store').adapterFor('application');
      const host = adapter.get('host') || '';
      // const namespace = adapter.get('namespace');
      const token = this.get('session.session.content.authenticated.token');
      let url = `${host}/api/feeds/${feed.get('id')}/articles/mark-read`;
      // let url = `${host}/${namespace}/feeds/${feed.get('id')}/relationships/articles`; -- does not work

      $.ajax(url, {
        method: 'GET',
        dataType: 'json',
        contentType: 'application/vnd.api+json',
        headers: {
          Accept: 'application/vnd.api+json',
          Authorization: 'Bearer ' + token
        }
      }).then(() => {
        feed.set('unreadCount', 0);
        this.refresh();
      });
    }
  }
});
