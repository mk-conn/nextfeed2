import Ember from 'ember';

const {Route} = Ember;

export default Route.extend({
  model(params) {
    return this.modelFor('feeds.feed');
  },

  renderTemplate() {
    this.render('feeds/feed/articles/settings', {
      into: 'application',
      outlet: 'column-two'
    })
  },
  actions: {
    updateFeed() {
      const feed = this.get('currentModel');

      feed.save().then(feed => {

      });
    }
  }
});
