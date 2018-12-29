import Route from '@ember/routing/route';
import { get, set } from '@ember/object';
import Gui from 'frontend/mixins/gui';

export default Route.extend(Gui, {
  displayIn: 'column-two',
  enableOnClose: 'column-one',

  beforeModel() {
    this.debug('route %s::beforeModel()', this.routeName);
    $('#' + this.get('displayIn')).animate({scrollTop: 0, duration: 400});

    this._super(...arguments);
  },

  /**
   *
   * @param params
   * @returns {*|Promise}
   */
  model(params) {
    return this.get('store').findRecord('article', params.article_id);
  },
  /**
   *
   * @param model
   */
  afterModel(model) {

    const feed = this.modelFor('index.feed');
    if (feed.id !== 'archived') {
      feed.decrementUnread();
    }
    model.set('read', true);
    model.save();

    this._super(...arguments);
  },

  renderTemplate() {
    this.render('index/feed/articles/article', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  actions: {
    scrapeArticle(article) {
      this.store.queryRecord('article-action', {
        action: 'scrape',
        params: {
          article_id: get(article, 'id')
        }
      }).then(articleAction => {
        const scraped = get(articleAction, 'result.scraped').htmlSafe();
        article.set('scraped', scraped);
      })
    },
    originalArticle() {

    },
  }
});
