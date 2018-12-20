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

  afterModel() {
    this.transitionTo('index.feeds');
  },

  actions: {
    /**
     *
     * @param sortables
     */
    sort(sortables) {
      this.debug(`route: %s::sort()`, this.routeName);
      let changed = [];
      sortables.forEach((model, idx) => {
        // this.debug(`\t->id: %s idx: %s`, this.get(model, 'id'), idx + 1);
        let newIdx = idx + 1;
        const currentIdx = model.get('order');

        if (currentIdx !== newIdx) {
          // this.debug(`\tsetting order for %s to %s`, model.get('id'), model.get('order'));
          model.set('order', newIdx);
          changed.push(model);
        }
      });

      changed.invoke('save');
    }
  }

});
