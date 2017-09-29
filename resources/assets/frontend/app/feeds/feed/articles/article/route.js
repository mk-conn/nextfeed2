import Ember from 'ember';

const {Route, $} = Ember;

export default Route.extend({

  beforeModel() {
    $('#column-two').animate({scrollTop: 0, duration: 400});
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
