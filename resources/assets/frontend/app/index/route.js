import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {
  afterModel() {
    this.debug(`route: %s::afterModel()`, this.routeName);
    this.transitionTo('feeds');
  }
});
