import { get } from '@ember/object';
import { inject as service } from '@ember/service';
import { isPresent } from '@ember/utils';
import DS from 'ember-data';
import DataAdapterMixin from 'ember-simple-auth/mixins/data-adapter-mixin';

export default DS.JSONAPIAdapter.extend(DataAdapterMixin, {
  namespace: 'api/v1',
  session: service(),
  /**
   * This method (urlPrefix) exists on the build-url-mixin but is marked private... so we
   * duplicate code here...
   */
  getUrlPrefix() {
    let host = get(this, 'host');
    let namespace = get(this, 'namespace');

    if (!host || host === '/') {
      host = '';
    }

    let url = [];
    if (host) {
      url.push(host);
    }
    if (namespace) {
      url.push(namespace);
    }
    return url.join('/');
  },
  /**
   *
   * @param xhr
   */
  authorize(xhr) {
    let {access_token} = this.session.data.authenticated;
    if (isPresent(access_token)) {
      xhr.setRequestHeader('Authorization', `Bearer ${access_token}`);
    }
  },
  /**
   *
   * @param store
   * @param type
   * @param id
   * @param snapshot
   * @returns {*}
   */
  findRecord(store, type, id, snapshot) {
    let {adapterOptions} = snapshot;
    if (adapterOptions) {
      let url = this.buildURL(type.modelName, id, snapshot, 'findRecord');
      let query = this.buildQuery(snapshot);
      query = Object.assign(query, adapterOptions);

      return this.ajax(url, 'GET', {data: query});
    }

    return this._super(...arguments);
  },
  /**
   * Overwritten to have better control over api-requests
   * @param store
   * @param type
   * @param sinceToken
   * @param snapshotRecordArray
   * @returns {*}
   */
  findAll(store, type, sinceToken, snapshotRecordArray) {
    let {adapterOptions} = snapshotRecordArray;
    if (adapterOptions) {
      let url = this.buildURL(type.modelName, null, snapshotRecordArray, 'findAll');
      let query = this.buildQuery(snapshotRecordArray);
      query = Object.assign(query, adapterOptions);
      if (sinceToken) {
        query.since = sinceToken;
      }

      return this.ajax(url, 'GET', {data: query});
    }

    return this._super(...arguments);
  }
});
