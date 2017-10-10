import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, RSVP, set, get, A, getOwner} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {

  model() {
    return RSVP.hash({
      discover: Ember.Object.create({
        url: null,
        errors: null,
        feeds: null
      }),
      feed: this.get('store').createRecord('feed'),
      folders: this.get('store').findAll('folder'),

      feedAction: null
    })
  },

  renderTemplate() {
    this.render('feeds/add', {
      into: 'application',
      outlet: 'column-two'
    })
  },
  /**
   *
   * @param controller
   * @param model
   */
  setupController(controller, model) {
    this._super(controller, model);

    controller.set('processed', false);
    controller.set('processing', false);
  },

  actions: {
    /**
     *
     * @param feed
     */
    toggleSelectFeed(feed) {
      feed.toggleProperty('selected');
    },

    /**
     *
     * @param siteUrl
     */
    discover() {
      const discover = this.get('currentModel').discover;
      if (discover.get('url')) {
        this.controller.set('processing', true);
        this.store.queryRecord('feed-action', {
          action: 'discover',
          params: {
            url: discover.get('url')
          }
        }).then(feedAction => {
          let feeds = new A();
          let items = get(feedAction, 'result.feeds');

          items.forEach(item => {
            feeds.pushObject(Ember.Object.create({
              url: item,
              folder: null,
              selected: false
            }));
          });
          set(get(this, 'currentModel'), 'discover.feeds', feeds);
          this.controller.set('processing', false);
          this.controller.set('processed', true);
        }, error => {
          this.controller.set('processing', false);
          set(discover, 'errors', error.errors)
        });
      }
    },

    subscribe() {
      const discover = get(this, 'currentModel').discover;
      const selectedFeeds = get(discover, 'feeds').filterBy('selected', true);

      selectedFeeds.forEach(feed => {
        this.store.createRecord('feed', {
          url: feed.get('url'),
          folder: feed.get('folder'),
        }).save().then(() => {
          getOwner(this).lookup('route:' + 'feeds').refresh();
        });
      });
    }
  }


});
