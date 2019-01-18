import Ember from 'ember';
import Gui from 'frontend/mixins/gui';

export default Ember.Route.extend(Gui, {
  displayIn: 'fullpage-content',
  enableOnClose: 'side-bar',

  renderTemplate() {
    this.render('index/settings', {
      into: 'application',
      outlet: 'main'
    })
  },
});
