import Ember from 'ember';

const {Component, computed, get, String, $} = Ember;

export default Component.extend({

  classNames: [ 'full-article' ],

  content: computed('article.content', function () {

    const article = get(this, 'article');
    const content = get(article, 'content');

    return String.htmlSafe(content);
  }),

  didRender() {
    const component = this.$();

    this.$('img', 'iframe', 'video', component).each(function () {
      console.log('found img/iframe/video:', $(this));
      $(this).addClass('img-fluid');
    });

  }

});
