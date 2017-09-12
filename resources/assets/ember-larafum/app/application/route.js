import Ember from 'ember';
import ApplicationRouteMixin from 'ember-simple-auth/mixins/application-route-mixin';

const {Route, inject: {service}} = Ember;

export default Route.extend(ApplicationRouteMixin, {
  currentUser: service('current-user'),

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
    invalidateSession() {
      this.get('session').invalidate();
    }
  }
});
