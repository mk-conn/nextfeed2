import Route from '@ember/routing/route';
import Gui from 'frontend/mixins/gui';

export default Route.extend(Gui, {
  displayIn: 'fullpage-content',

  renderTemplate() {
    this.render('login', {
      into: 'application',
      outlet: 'main'
    });
  }
});
