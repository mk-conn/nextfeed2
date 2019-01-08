import Route from '@ember/routing/route';

export default Route.extend({

  renderTemplate() {
    this.render('index/feed/articles/index', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  setupController(controller, model) {
    this._super(controller, model);

    controller.set('feed', this.modelFor('index.feed'));
  },

  actions: {
    cleanup() {

    }
  }

});
