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

  renderTemplate() {
    this.render('feeds', {
      into: 'application',
      outlet: 'side-bar'
    })
  },

  actions: {

    openFolder(folder) {
      folder.set('open', true);
      folder.save();
    },

    closeFolder(folder) {
      folder.set('open', false);
      folder.save();
    }
  }

});
