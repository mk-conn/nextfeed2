import Component from '@ember/component';
import { get, set } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank, isPresent } from '@ember/utils';
import { task, timeout } from 'ember-concurrency';
import $ from 'jquery';

const DEBOUNCE_MS = 700;

export default Component.extend({
  session: service(),
  store: service(),

  tagName: 'div',

  classNames: ['nav-item'],

  init() {
    this._super(...arguments);
    this.showResults = false;
    this.articles = [];
    this.showInput = false;
  },

  /**
   *
   */
  searchArticle: task(function* (term) {
    this.debug(`component: search-articles::searchArticle(%s)`, term);

    const appAdapter = this.get('store').adapterFor('application');
    const urlPrefix = appAdapter.getUrlPrefix();
    let url = `${urlPrefix}/articles/search`;
    let data = {
      q: term
    };
    if (isBlank(term)) {
      set(this, 'articles', []);
      return [];
    }

    yield timeout(DEBOUNCE_MS);

    let json = yield this.get('getResults').perform(url, data);

    return json;
  }).restartable(),
  /**
   *
   */
  getResults: task(function* (url, data) {
    let xhr;
    let {access_token} = this.session.data.authenticated;
    try {

      xhr = $.getJSON({
        url: url,
        data: data,
        headers: {
          Authorization: `Bearer ${access_token}`
        },
      });

      return yield xhr.promise();
    } catch (e) {
      console.error(e);
    } finally {
      this.debug('search aborted');
      xhr.abort();
    }
  }),
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
        this.get('router').transitionTo('index.feed.articles.article', article.feed.id, article.id)
      }
    },
    toggleSearch() {
      this.toggleProperty('showInput');
      this.toggleProperty('showResults');
    },
  }
});
