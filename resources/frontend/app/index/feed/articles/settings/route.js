import Route from '@ember/routing/route';
import { getOwner } from '@ember/application';
import { inject as service } from '@ember/service';
import Gui from 'frontend/mixins/gui';

export default Route.extend(Gui, {
  displayIn: 'column-two',
  enableOnClose: 'column-one',
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
    toggleKeepUnread() {
      const feed = this.get('currentModel');
      feed.toggleProperty('settings.articles.cleanup.keepUnread');
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
