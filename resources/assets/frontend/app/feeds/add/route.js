import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, RSVP, set, get, A} = Ember;

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
        }, error => {
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
          user: this.get('currentUser.user')
        }).save();
      });
    }
  }


});
