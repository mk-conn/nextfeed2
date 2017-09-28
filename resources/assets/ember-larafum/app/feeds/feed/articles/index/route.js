import Ember from 'ember';

export default Ember.Route.extend({

  renderTemplate() {
    this.render('feeds/feed/articles/index', {
      into: 'application',
      outlet: 'column-two'
    })
  }
});
