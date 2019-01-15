import Service, { inject as service } from '@ember/service';
// noinspection ES6CheckImport
import { task } from 'ember-concurrency';

export default Service.extend({
  store: service(),
  session: service(),

  runTask: task(function* (url) {
    let { access_token } = this.session.data.authenticated;
    const appAdapter = this.get('store').adapterFor('application');
    const urlPrefix = appAdapter.getUrlPrefix();
    url = `/${ urlPrefix }/${ url }`;

    return yield fetch(url, {
      method: 'GET',
      headers: {
        Authorization: `Bearer ${ access_token }`,
        'X-Requested-With': 'XMLHttpRequest',
        "Content-Type": "application/json"
      },
    }).then(response => {
      return response.json()
    });
  }),

  /**
   *
   * @param feedId
   * @returns {*|void}
   */
  markAllRead(feedId) {
    let url = `feeds/${ feedId }/mark-read`;

    return this.get('runTask').perform(url);
  },
  /**
   *
   * @param feedId
   * @returns {*|void}
   */
  reloadFeedIcon(feedId) {
    let url = `feeds/${ feedId }/reload-icon`;

    return this.get('runTask').perform(url);
  },
  /**
   *
   * @param articleId
   * @returns {*|void}
   */
  remoteArticle(articleId) {
    let url = `articles/remote/${ articleId }`;

    return this.get('runTask').perform(url);
  }
});
