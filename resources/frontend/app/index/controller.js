import Controller from '@ember/controller';
import { computed } from '@ember/object';
import { task, timeout } from 'ember-concurrency';

export default Controller.extend({

  sortBy: ['order'],

  sortedFolders: computed.sort('folders', 'sortBy'),

  // /**
  //  *
  //  */
  // searchArticle: task(function* (term) {
  //
  //   yield timeout(600);
  //
  //   return this.get('store').queryRecord('article-action', {
  //     action: 'search',
  //     params: {q: term}
  //   }).then(articleAction => {
  //     // this.set('articleSearchResults', articleAction.get('result.articles'));
  //     let articles = [];
  //
  //     const articlesResult = articleAction.get('result.articles');
  //
  //     for (let key in articlesResult) {
  //       if (articlesResult.hasOwnProperty(key)) {
  //         const article = articlesResult[ key ];
  //         articles.push(article);
  //       }
  //     }
  //
  //     return articles;
  //   });
  // }),

  actions: {

    // /**
    //  *
    //  * @param article
    //  */
    // goToArticle(article) {
    //   if (article) {
    //     this.transitionToRoute('feeds.feed.articles.article', article.feed.id, article.id);
    //   }
    // }
  }
});
