import Component from '@ember/component';
import { computed, get } from '@ember/object';
import $ from 'jquery';
import { htmlSafe } from '@ember/template';

export default Component.extend({
  classNameBindings: ['isRead:read:unread'],

  isRead: computed('article.read', function () {
    return get(this, 'article.read');
  }),

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

    return htmlSafe(description);
  }),

  didRender() {
    const component = this.$();

    $('img', component).each(function () {
      $(this).addClass('img-fluid');
    });
  }

});
