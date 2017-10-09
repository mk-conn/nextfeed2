import Ember from 'ember';

const {Route, getOwner} = Ember;

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
    },
    deleteFeed() {
      const feed = this.get('currentModel');
      feed.destroyRecord().then(() => {
        this.transitionTo('feeds');
        getOwner(this).lookup('route:' + 'feeds').refresh();
      });
    }
  }
});
