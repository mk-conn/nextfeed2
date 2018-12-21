import DS from 'ember-data';
import { inject as service } from '@ember/service';
import { isPresent } from '@ember/utils';
import { get } from '@ember/object';
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
});
