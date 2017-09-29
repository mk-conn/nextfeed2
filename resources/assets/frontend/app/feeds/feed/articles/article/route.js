import Ember from 'ember';

const {Route, inject} = Ember;

export default Route.extend({

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
  },

  renderTemplate() {
    this.render('feeds/feed/articles/article', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  actions: {
    originalArticle() {

    },

  }
});
