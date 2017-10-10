import Ember from 'ember';

const {Route, getOwner} = Ember;

export default Route.extend({

  model() {
    return this.store.createRecord('folder');
  },

  renderTemplate() {
    this.render('feeds/folders/add', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  actions: {
    save(folder) {

      folder.set('user', this.get('currentUser.user'));
      folder.save().then(folder => {
        getOwner(this).lookup('route:' + 'feeds').refresh();
      });
    }
  }

});
