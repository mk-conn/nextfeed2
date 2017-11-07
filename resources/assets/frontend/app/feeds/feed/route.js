import Ember from 'ember';

const {Route} = Ember;

export default Route.extend({

  model(params) {

    if (params.feed_id === 'archived') {

      return Ember.Object.create({
        id: 'archived',
        name: 'Archived',
        description: 'Your archived articles'
      });
    }

    return this.store.findRecord('feed', params.feed_id);
  },

});
