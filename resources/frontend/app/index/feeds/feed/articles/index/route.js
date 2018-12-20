import Route from '@ember/routing/route';

export default Route.extend({

  renderTemplate() {
    this.render('index/feeds/feed/articles/index', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  setupController(controller, model) {
    this._super(controller, model);

    controller.set('feed', this.modelFor('index.feeds.feed'));
  },

  actions: {
    cleanup() {

    }
  }

});
