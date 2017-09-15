import Ember from 'ember';

const {Component, computed, get} = Ember;


export default Ember.Component.extend({
  classNameBindings: [ 'read:read:unread' ],

  description: computed('article.content', function() {
    const stripAt = 240;
    let text = '';
    try {
      text = $(get(this, 'article.content'))
        .text();
      if (text.length > stripAt) {
        text = text.slice(0, stripAt) + ' ...'.htmlSafe();
      }
    } catch (e) {
      console.error(e);
    }

    return text;
  })
});
