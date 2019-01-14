import { get, set } from '@ember/object';
import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import Gui from 'frontend/mixins/gui';

export default Route.extend(Gui, {
  tasks: service(),
  displayIn: 'column-two',
  enableOnClose: 'column-one',

  beforeModel() {
    this.debug('route %s::beforeModel()', this.routeName);
    $('#' + this.get('displayIn')).animate({ scrollTop: 0, duration: 400 });

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
    loadRemoteArticle(article) {
      this.tasks.remoteArticle(article).then(content => {
        article.set('scraped', content);
      });
    },
    originalArticle() {

    },
  }
});
