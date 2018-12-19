import Mixin from '@ember/object/mixin';
import { inject as service } from '@ember/service';

export default Mixin.create({

  gui: service('gui'),

  afterModel() {
    const displayIn = this.get('displayIn');
    if (displayIn) {
      this.get('gui').enable(displayIn);
    }
    this._super(...arguments);
  },

  actions: {

    willTransition() {
      const displayIn = this.get('displayIn');
      if (displayIn) {
        this.get('gui').disable(displayIn);
      }
      this._super(...arguments);
    }
  }
});