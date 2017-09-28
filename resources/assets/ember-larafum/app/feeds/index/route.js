import Ember from 'ember';

export default Ember.Route.extend({
  renderTemplate() {
    this.render('feeds/index', {
      into: 'application',
      outlet: 'column-one'
    })
  }
});
