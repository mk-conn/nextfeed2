import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, inject: {service}} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {
  currentUser: service(),

  model() {
    return this.get('store').query(
      'feed', {
        sort: 'order'
      });
  },

  renderTemplate() {
    this.render('feeds', {
      into: 'application',
      outlet: 'side-bar'
    })
  }

});
