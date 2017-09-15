import Ember from 'ember';

export default Ember.Route.extend({

  model(params) {
    console.log('article:', params);
    return this.get('store').findRecord('article', params.id);
  },

  renderTemplate() {
    this.render('feeds/feed/articles/article', {
      into: 'application',
      outlet: 'article'
    })
  },

  actions: {
    originalArticle() {

    }
  }
});
