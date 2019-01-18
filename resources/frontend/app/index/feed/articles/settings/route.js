import { getOwner } from '@ember/application';
import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import Gui from 'frontend/mixins/gui';

export default Route.extend(Gui, {
  displayIn: 'column-two',
  enableOnClose: 'column-one',
  notify: service(),
  session: service(),
  tasks: service(),
  /**
   *
   * @returns {*|DS.Model}
   */
  model() {
    return this.modelFor('index.feed');
  },
  /**
   *
   * @param controller
   * @param model
   */
  setupController(controller, model) {
    this._super(...arguments);

    controller.set('feedIconLoadIsRunning', false);
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
        getOwner(this).lookup('route:' + 'index').refresh();
        this.transitionTo('index');
      });
    },
    /**
     *
     * @returns {*}
     */
    reloadFeedIcon() {
      const feed = this.get('currentModel');
      this.controller.set('feedIconLoadIsRunning', true);
      this.get('tasks').reloadFeedIcon(feed.id).then(() => {
        feed.reload();
        this.controller.set('feedIconLoadIsRunning', false);
      });
    }
  }
});
