import Ember from 'ember';
import ApplicationRouteMixin from 'ember-simple-auth/mixins/application-route-mixin';

const {Route, inject: {service}, get} = Ember;

export default Route.extend(ApplicationRouteMixin, {
  currentUser: service(),

  session: service('session'),

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
    controller.set('currentUser', this.get('currentUser'));
    controller.set('session', this.get('session'));
  },

  actions: {
    updateAllFeeds() {

    },

    searchArticle(q) {
      console.log('route searchArticle', q);

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
