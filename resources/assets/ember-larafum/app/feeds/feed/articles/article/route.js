import Ember from 'ember';

const {Route, inject} = Ember;

export default Route.extend({

  gui: inject.service(),

  beforeModel() {
    this.get('gui').activate('article');
  },

  model(params) {
    console.log('article:', params);
    console.log('article:', this.routeName);

    return this.get('store').findRecord('article', params.article_id);
  },

  renderTemplate() {
    this.render('feeds/feed/articles/article', {
      into: 'application',
      outlet: 'article'
    })
  },

  actions: {
    originalArticle() {

    },

    willTransition() {
      Ember.debug(`>>>> Feeds.Show.Articles.ShowRoute::willTransition()`);
      this.get('gui').deactivate('article');
    }
  }
});
