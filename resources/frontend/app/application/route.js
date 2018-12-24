import ApplicationRouteMixin from 'ember-simple-auth/mixins/application-route-mixin';
import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import { get } from '@ember/object';

export default Route.extend(ApplicationRouteMixin, {
  currentUser: service(),

  session: service(),

  beforeModel() {
    return this._loadCurrentUser();
  },

  sessionAuthenticated() {
    this._super(...arguments);
    this._loadCurrentUser();
  },

  _loadCurrentUser() {
    return this.get('currentUser').load()
      .catch(() => this.get('session').invalidate());
  },

  setupController(controller, model) {
    this._super(controller, model);
  },

  actions: {
    updateAllFeeds() {

    },

    searchArticle(q) {
      this.store.queryRecord('article-action', {
        action: 'search',
        params: {
          q: q
        }
      }).then(articleAction => {
        let articles = get(articleAction, 'result.articles');
        this.controller.set('articlesSearchResult', articles);
        this.controller.set('processing', false);
        this.controller.set('processed', true);
      }, error => {
        this.controller.set('processing', false);
        // set(discover, 'errors', error.errors)
      });
    },

    invalidateSession() {
      this.get('session').invalidate();
    }
  }
});
