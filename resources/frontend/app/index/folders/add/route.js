import { getOwner } from '@ember/application';
import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';
import Gui from 'frontend/mixins/gui';

export default Route.extend(Gui, {
  displayIn: 'fullpage-content',
  enableOnClose: 'side-bar',
  notify: service(),
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
        this.get('notify').success({ html: `Folder ${ folder.name } created` });
        this.transitionTo('index');
      });
    },
    delete(folder) {
      folder.destroyRecord().then(() => {
        // getOwner(this).lookup('route:' + 'index').refresh();
        this.transitionTo('index');
      });
    }
  }

});
