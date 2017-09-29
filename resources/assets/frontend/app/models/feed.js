import DS from 'ember-data';
import Ember from 'ember';

const {Model, attr, belongsTo, hasMany} = DS;
const {computed} = Ember;

export default Model.extend({
  name: attr('string'),
  url: attr('string'),
  feedUrl: attr('string'),
  siteUrl: attr('string'),
  guid: attr('string'),
  description: attr('string'),
  icon: attr('string'),
  logo: attr('string'),
  language: attr('string'),
  etag: attr('string'),
  authUser: attr('string'),
  authPassword: attr('string'),
  order: attr('number'),
  user: belongsTo('user'),
  folder: belongsTo('folder'),
  articles: hasMany('article'),
  meta: attr(),
  /**
   *
   */
  decrementUnread() {
    let unreadCount = this.get('unreadCount');
    unreadCount--;

    this.set('unreadCount', unreadCount);
  },
  /**
   *
   */
  incrementUnread() {
    let unreadCount = this.get('unreadCount');
    unreadCount++;

    this.set('unreadCount', unreadCount);
  },

  articlesCount: computed('meta.articles-count', {
    get() {
      return this.get('meta.articles-count')
    },
    set(key, val) {
      Ember.assert('You can not set the articles counter.', true);
    }
  }),
  unreadCount: computed('meta.articles-unread-count', {
    get() {
      return this.get('meta.articles-unread-count');
    },
    set(prop, value) {
      this.set('meta.articles-unread-count', value);
      return value;
    }
  })
});
