import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { get, set } from '@ember/object';
import { isBlank } from '@ember/utils';
import { task, timeout } from 'ember-concurrency';

export default Component.extend({

  store: service(),

  classNames: [ 'article-search' ],

  classNameBindings: [ 'searchActivated' ],

  init() {
    this._super(...arguments);
    this.showResults = false;
  },

  /**
   *
   */
  searchArticle: task(function* (term) {
    this.debug(`component: search-articles::searchArticle(%s)`, term);

    if (isBlank(term)) {
      set(this, 'articles', []);
    }

    yield timeout(700);

    return this.get('store').queryRecord('article-action', {
      action: 'search',
      params: {q: term}
    }).then(articleAction => {
      // this.set('articleSearchResults', articleAction.get('result.articles'));
      let articles = [];

      const articlesResult = articleAction.get('result.articles');

      for (let key in articlesResult) {
        if (articlesResult.hasOwnProperty(key)) {
          const article = articlesResult[ key ];
          articles.push(article);
        }
      }
      set(this, 'articles', articles);
      set(this, 'showResults', true);
    });
  }).restartable(),
  /**
   * actions
   */
  actions: {
    /**
     *
     * @param article
     */
    goToArticle(article) {
      if (article) {
        this.toggleProperty('showInput');
        this.toggleProperty('showResults');
        this.get('router').transitionTo('feeds.feed.articles.article', article.feed.id, article.id)
      }
    },
    toggleSearch() {
      this.toggleProperty('showInput');
      this.toggleProperty('showResults');
    },

    // showSearch() {
    //   if (get(this, 'articles')) {
    //     set(this, 'showResults', true);
    //   }
    //   set(this, 'showInput', true);
    // }
  }
});
