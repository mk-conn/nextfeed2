import Ember from 'ember';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

const {Route, inject: {service}, $} = Ember;

export default Route.extend(AuthenticatedRouteMixin, {

  session: service(),

  init() {
    this._super(...arguments);

    this.sessionToken = null;
  },

  beforeModel() {
    const token = this.get('session.data.authenticated.token');
    let sessionToken = this.get('session.session.content.authenticated');
  },

  model() {
    return Ember.Object.create({
      url: null,
      feeds: null
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
      if (this.currentModel.get('url')) {
        const feedAction = this.store.createRecord('feedAction', {
          type: 'discover',
          filter: {
            url: this.currentModel.get('url')
          }
        });

        feedAction.save().then(feedAction => {
          let results = feedAction.get('results');
        })

      }
    }
  }


});
