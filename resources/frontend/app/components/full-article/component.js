import Component from '@ember/component';
import { debug } from '@ember/debug';
import { computed, get } from '@ember/object';
import { inject as service } from '@ember/service';
import { htmlSafe } from '@ember/template';
import { typeOf } from '@ember/utils';
import $ from 'jquery';

export default Component.extend({
  tasks: service(),

  classNames: ['full-article'],

  content: computed('article.content', function () {

    const article = get(this, 'article');
    const content = get(article, 'content');

    return htmlSafe(content);
  }),

  init() {
    this._super(...arguments);
    this.set('elementId', 'full-article');
    this.fullArticleContent = null;
    this.loader = false;
  },

  didRender() {
    const _self = this;
    this.$('img', 'video').each(function () {
      $(this).addClass('img-fluid');
    });

    this.$('iframe').each(function () {

      $(this).attr('sandbox', '');
      $(this).attr('sandbox', 'allow-same-origin allow-scripts allow-presentation');
      // if (articleSettings && get(articleSettings, 'allowEmbedded') === true) {
      $(this).attr('sandbox', 'allow-same-origin allow-scripts');
      // } else {
      //   const hint = 'Embedded content disabled: <a href="/settings">Enable in settings</a>';
      //   $(this).after('<div class="text-muted">' + hint + '</div>');
      // }

    });
  },
  /**
   *
   */
  didUpdateAttrs() {
    this.set('fullArticleContent', null);
  },
  actions: {
    /**
     *
     * @param articleId
     */
    loadRemoteArticle(articleId) {
      this.set('loader', true);
      this.tasks.remoteArticle(articleId).then(content => {
        this.set('fullArticleContent', htmlSafe(content));
        this.set('loader', false);
      });
    },
    /**
     *
     */
    restoreContent() {
      this.set('fullArticleContent', null);
    }
  }

});
