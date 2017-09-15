import Ember from 'ember';

const {Component, computed, get, String, $} = Ember;


export default Component.extend({
  classNameBindings: [ 'read:read:unread' ],

  articleDescription: computed('article.description', function () {

    let description = get(this, 'article.description');
    description = description.replace(/<a.*?a>/gm, "");

    return String.htmlSafe(description);
  }),

  didRender() {
    const component = this.$();

    $('img', component).each(function () {
      $(this).addClass('img-fluid');
    });
  }
});
