import { getOwner } from '@ember/application';
import Route from '@ember/routing/route';
import Gui from 'frontend/mixins/gui';

export default Route.extend(Gui, {
  displayIn: 'fullpage-content',
  enableOnClose: 'side-bar',
  /**
   * Model
   * @returns {*|DS.Model|EmberPromise}
   */
  model() {
    return this.store.createRecord('folder');
  },

  renderTemplate() {
    this.render('index/folders/add', {
      into: 'application',
      outlet: 'main'
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
