import Ember from 'ember';

const {Component, computed, get, String, $, typeOf} = Ember;

export default Component.extend({

  classNames: [ 'full-article' ],

  scrapeDisabled: computed('article.scraped', function () {
    if (get(this, 'article.scraped')) {
      return true;
    }

    return false;
  }),

  content: computed('article.content', function () {

    const article = get(this, 'article');
    const content = get(article, 'content');

    return String.htmlSafe(content);
  }),

  didRender() {
    const _self = this;
    this.$('img', 'video').each(function () {
      $(this).addClass('img-fluid');
    });

    this.$('iframe').each(function () {
      const origWidth = typeOf($(this).attr('width')) !== 'undefined' ? $(this).attr('width') : $(this).width();
      const origHeight = typeOf($(this).attr('height')) !== 'undefined' ? $(this).attr('height') : $(this).height();
      const currentWidth = _self.$('.article-content').width();

      if (origWidth > currentWidth) {
        const factor = origWidth / currentWidth;
        const scaledWidth = currentWidth;
        const scaledHeight = Math.round(origHeight / factor);

        $(this).attr('width', scaledWidth);
        $(this).attr('height', scaledHeight);
      }

      $(this).attr('sandbox', '');
      $(this).attr('sandbox', 'allow-same-origin allow-scripts allow-presentation');
      // if (articleSettings && get(articleSettings, 'allowEmbedded') === true) {
      //   $(this).attr('sandbox', 'allow-same-origin allow-scripts');
      // } else {
      //   const hint = 'Embedded content disabled: <a href="/settings">Enable in settings</a>';
      //   $(this).after('<div class="text-muted">' + hint + '</div>');
      // }

    });


  }

});
