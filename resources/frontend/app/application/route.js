import ApplicationRouteMixin from 'ember-simple-auth/mixins/application-route-mixin';
import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import { get } from '@ember/object';
import { debug } from '@ember/debug';

export default Route.extend(ApplicationRouteMixin, {
  currentUser: service(),

  session: service(),

  beforeModel() {
    debug('application-route.beforeRoute(): load current user');
    return this._loadCurrentUser();
  },

  sessionAuthenticated() {
    this._super(...arguments);
    debug('application-route: Session is authenticated, loading user');
    this._loadCurrentUser();
  },

  _loadCurrentUser() {
    return this.get('currentUser').load()
      .catch(() => this.get('session').invalidate());
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
