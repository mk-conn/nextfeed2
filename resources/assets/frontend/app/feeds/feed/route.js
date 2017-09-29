import Ember from 'ember';

const {Route} = Ember;

export default Route.extend({

  model(params) {
    return this.store.findRecord('feed', params.feed_id);
  },

});
