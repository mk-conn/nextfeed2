import DS from 'ember-data';
import { computed } from '@ember/object';
import { assert } from '@ember/debug';

const {Model, attr, belongsTo, hasMany} = DS;

export default Model.extend({
  name: attr('string'),
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
  settings: attr(),
  meta: attr(),
  /**
   *
   */
  decrementUnread() {
    let unreadCount = this.get('unreadCount');
    if (unreadCount > 0) {
      unreadCount--;
      this.set('unreadCount', unreadCount);
    }
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
      assert('You can not set the articles counter.', true);
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
