import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, RSVP, get, set, getOwner} = Ember;

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

  setupController(controller, model) {

    controller.set('folders', model.folders);
    controller.set('feeds', model.feeds);

  },

  actions: {

    openFolder(folder) {
      folder.set('open', true);
      folder.save();
    },

    closeFolder(folder) {
      folder.set('open', false);
      folder.save();
    },
    addFeedToFolder(folder, feed) {

      feed.set('folder', folder);
      feed.save().then(() => {
        this.refresh();
      });


    },
    sort(sortables) {
      let changed = [];
      sortables.forEach((model, idx) => {
        let newIdx = idx + 1;
        const currentIdx = model.get('order');
        if (currentIdx !== newIdx) {
          model.set('order', newIdx);
          changed.push(model);
        }
      });

      changed.invoke('save');
    }
  }

});
