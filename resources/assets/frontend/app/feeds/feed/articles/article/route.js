import Ember from 'ember';
import Gui from 'frontend/mixins/gui';

const {Route, $} = Ember;

export default Route.extend(Gui, {
  displayIn: '#column-two',

  beforeModel() {
    this.debug('route %s::beforeModel()', this.routeName);
    $(this.get('displayIn')).animate({scrollTop: 0, duration: 400});

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

    if (!model.get('read')) {
      const feed = this.modelFor('feeds.feed');
      feed.decrementUnread();

      model.set('read', true);
      model.save();
    }
    this._super(...arguments);
  },

  renderTemplate() {
    this.render('feeds/feed/articles/article', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  actions: {
    scrapeArticle() {

    },
    originalArticle() {

    },

  }
});
