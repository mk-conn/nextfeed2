import Ember from 'ember';

const {Component, computed, get} = Ember;

export default Component.extend({

  content: computed('article.content', function () {
    console.log('article:', this.get('article'));
    return this.get('article.content').htmlSafe();
  })

});
