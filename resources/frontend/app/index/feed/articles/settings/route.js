import Route from '@ember/routing/route';
import { getOwner } from '@ember/application';
import { inject as service } from '@ember/service';

export default Route.extend({
  notify: service(),
  /**
   *
   * @returns {*|DS.Model}
   */
  model() {
    return this.modelFor('index.feed');
  },

  renderTemplate() {
    this.render('index/feed/articles/settings', {
      into: 'application',
      outlet: 'column-two'
    })
  },
  actions: {
    updateFeed() {
      const feed = this.get('currentModel');

      feed.save().then(feed => {
        this.get('notify').success({html: `<strong>${feed.name}</strong> settings saved`});
      });
    },
    deleteFeed() {
      const feed = this.get('currentModel');
      feed.destroyRecord().then(() => {
        this.transitionTo('feeds');
        getOwner(this).lookup('route:' + 'index').refresh();
      });
    }
  }
});
