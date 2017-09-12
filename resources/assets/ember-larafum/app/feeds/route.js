import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, RSVP} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {

  model() {

    return RSVP.hash({
      feeds: this.get('store').query(
        'feed', {
          sort: 'order'
        }),
      folders: this.get('store')
                   .query('folder', {
                     sort: 'order',
                     include: 'feeds'
                   })
    });
  },

});
