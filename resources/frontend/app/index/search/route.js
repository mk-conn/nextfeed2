import Route from '@ember/routing/route';
import Gui from 'frontend/mixins/gui';

export default Route.extend(Gui, {
  displayIn: 'column-one',
  enableOnClose: 'side-bar',

  renderTemplate() {
    this.render('index/search', {
      into: 'application',
      outlet: 'column-one'
    });
  },
});
