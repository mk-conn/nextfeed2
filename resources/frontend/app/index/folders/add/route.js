import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, getOwner} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {

  model() {
    return this.store.createRecord('folder');
  },

  renderTemplate() {
    this.render('index/folders/add', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  actions: {
    save(folder) {
      folder.save().then(folder => {
        getOwner(this).lookup('route:' + 'feeds').refresh();
      });
    },
    delete(folder) {
      folder.destroyRecord().then(() => {
        getOwner(this).lookup('route:' + 'feeds').refresh();
      });
    }
  }

});
