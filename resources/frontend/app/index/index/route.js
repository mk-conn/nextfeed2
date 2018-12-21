import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

export default Ember.Route.extend(AuthenticatedRouteMixin, {
  renderTemplate() {
    this.render('index/index', {
      into: 'application',
      outlet: 'column-one'
    })
  }
});
