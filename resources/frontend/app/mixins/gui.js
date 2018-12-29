import Mixin from '@ember/object/mixin';
import { inject as service } from '@ember/service';
import { debug } from '@ember/debug';

export default Mixin.create({

  gui: service('gui'),

  afterModel() {
    const displayIn = this.get('displayIn');
    debug(`displayIn: ${displayIn}`);

    if (displayIn) {
      this.get('gui').enable(displayIn);
    }
    this._super(...arguments);
  },

  actions: {

    willTransition() {
      const displayIn = this.get('displayIn');
      if (displayIn) {
        // this.get('gui').disable(displayIn);
        this.get('gui').enable(this.get('enableOnClose'));
      }
      this._super(...arguments);
    }
  }
});
