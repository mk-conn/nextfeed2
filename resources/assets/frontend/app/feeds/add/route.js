import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, RSVP, set, get} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {

  model() {
    return RSVP.hash({
      discover: Ember.Object.create({
        url: null,
        errors: null
      }),
      feed: this.get('store').createRecord('feed'),
      feedAction: null
    })
  },

  renderTemplate() {
    this.render('feeds/add', {
      into: 'application',
      outlet: 'column-two'
    })
  },

  actions: {
    /**
     *
     * @param siteUrl
     */
    discover() {
      const discover = this.get('currentModel').discover;
      if (discover.get('url')) {
        this.store.queryRecord('feed-action', {
          action: 'discover',
          params: {
            url: discover.get('url')
          }
        }).then(feedAction => {
          set(get(this, 'currentModel'), 'feedAction', feedAction);
        }, error => {
          set(discover, 'errors', error.errors)
        });

      }
    }
  }


});
