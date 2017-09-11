import Ember from 'ember';
import ApplicationRouteMixin from 'ember-simple-auth/mixins/application-route-mixin';

const {Route, inject: {service}} = Ember;

export default Route.extend(ApplicationRouteMixin, {
  currentUser: service(),
  session: service(),

  beforeModel() {
    return this._loadCurrentUser();
  },

  _loadCurrentUser() {
    return this.get('currentUser')
               .load()
               .catch(() => this.get('session').invalidate());
  },

  sessionAuthenticated() {
    this._super(...arguments);
    this._loadCurrentUser();
  },

  actions: {
    invalidateSession() {
      this.get('session').invalidate();
    }
  }
});
