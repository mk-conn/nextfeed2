import Route from '@ember/routing/route';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';


export default Route.extend(AuthenticatedRouteMixin, {

  init() {
    this._super(...arguments);
    this.generateController('index.folders');
  },

  model() {
    return this.store.findAll('feed');
  },

  renderTemplate() {
    this.render();

    const folderController = this.controllerFor('index.folders');

    this.render('index/folders', {
      into: 'application',
      outlet: 'side-bar',
      controller: folderController,
      model: this.store.findAll('folder')
    })

  },

  afterModel() {
    this.debug(`route: %s::afterModel()`, this.routeName);
    // this.transitionTo('feeds');
  }
});
