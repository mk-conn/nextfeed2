import Ember from 'ember';

const {Component, computed, get, String, $} = Ember;


export default Component.extend({
  classNameBindings: [ 'read:read:unread' ],

  articleDescription: computed('article.description', function () {
    const stripAt = 240;
    // let description = get(this, 'article.description');
    // description = description.replace(/<a.*?a>/gm, "");
    let description = '';
    try {
      description = $(get(this, 'article.description')).text();
      if (description.length > stripAt) {
        description = description.slice(0, stripAt) + ' ...';
      }
    } catch (e) {
    }
    return String.htmlSafe(description);
  }),

  didRender() {
    const component = this.$();

    $('img', component).each(function () {
      $(this).addClass('img-fluid');
    });
  }
});
