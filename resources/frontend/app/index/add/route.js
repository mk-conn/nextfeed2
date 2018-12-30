import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';
import Route from '@ember/routing/route';
import RSVP from 'rsvp';
import Gui from 'frontend/mixins/gui';
import { getOwner } from '@ember/application';

export default Route.extend(AuthenticatedRouteMixin, Gui, {
  displayIn: 'fullpage-content',
  enableOnClose: 'side-bar',
  model() {
    return RSVP.hash({
      feed: this.get('store').createRecord('feed'),
      folders: this.get('store').findAll('folder'),
    })
  },

  renderTemplate() {
    this.render('index/add', {
      into: 'application',
      outlet: 'main'
    })
  },
  /**
   *
   * @param controller
   * @param model
   */
  setupController(controller, model) {
    this._super(controller, model);

    controller.set('processed', false);
    controller.set('processing', false);
  },

  actions: {
    /**
     *
     * @param feed
     */
    toggleSelectFeed(feed) {
      feed.toggleProperty('selected');
    },

    setFolder(folder) {
      this.get('currentModel').feed.set('folder', folder);
    },

    subscribe() {
      const feed = this.get('currentModel').feed;
      feed.save().then(feed => {
        getOwner(this).lookup('route:' + 'index').refresh();
      });
    }
  }


});
