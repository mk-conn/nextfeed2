import Ember from 'ember';

export default Ember.Route.extend({

  renderTemplate() {
    this.render('feeds/feed/articles/index', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  setupController(controller, model) {
    this._super(controller, model);

    controller.set('feed', this.modelFor('feeds.feed'));
  },

  actions : {
    cleanup() {

    }
  }

});
