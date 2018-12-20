import Route from '@ember/routing/route';
import EmberObject from '@ember/object';

export default Route.extend({

  model(params) {

    if (params.feed_id === 'archived') {

      return EmberObject.create({
        id: 'archived',
        name: 'Archived',
        description: 'Your archived articles'
      });
    }

    return this.store.findRecord('feed', params.feed_id);
  },

});
