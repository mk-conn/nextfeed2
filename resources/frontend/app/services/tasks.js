import Service, { inject as service } from '@ember/service';
import { task } from 'ember-concurrency';

export default Service.extend({
  store: service(),
  session: service(),
  /**
   *
   */
  runTask: task(function* (url) {
    const appAdapter = this.get('store').adapterFor('application');
    const urlPrefix = appAdapter.getUrlPrefix();
    let {access_token} = this.session.data.authenticated;
    let getUrl = `/${urlPrefix}/${url}`;
    let xhr;
    try {
      xhr = $.getJSON({
        url: getUrl,
        headers: {
          Authorization: `Bearer ${access_token}`
        },
      });
      return yield xhr.promise();
    } finally {
      xhr.abort();
    }
  }),

  /**
   *
   * @param feedId
   * @returns {*|void}
   */
  markAllRead(feedId) {
    let url = `feeds/${feedId}/mark-read`;

    return this.get('runTask').perform(url);
  },
  /**
   *
   * @param feedId
   * @returns {*|void}
   */
  reloadFeedIcon(feedId) {
    let url = `feeds/${feedId}/reload-icon`;

    return this.get('runTask').perform(url);
  }
});
