import Ember from 'ember';

const {Component, computed, get, String} = Ember;

export default Component.extend({

  classNames: [ 'full-article' ],

  content: computed('article.content', function () {
    console.log('component: article:', this.get('article'));

    const article = get(this, 'article');
    const content = get(article, 'content');

    return String.htmlSafe(content);
  })

});
