import Ember from 'ember';
import Gui from 'frontend/mixins/gui';
import RSVP from 'rsvp';

export default Ember.Route.extend(Gui, {
  displayIn: 'fullpage-content',
  enableOnClose: 'side-bar',

  model() {
    return RSVP.hash({
      folders: this.store.findAll('folder', { adapterOptions: { sort: 'name' } }),
      feeds: this.store.findAll('feed', { adapterOptions: { sort: 'name' } })
    });
  },

  renderTemplate() {
    this.render('index/settings', {
      into: 'application',
      outlet: 'main'
    })
  },
});
