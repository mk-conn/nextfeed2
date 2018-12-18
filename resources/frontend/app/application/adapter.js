import DS from 'ember-data';
import DataAdapterMixin from 'ember-simple-auth/mixins/data-adapter-mixin';

export default DS.JSONAPIAdapter.extend(DataAdapterMixin, {
  namespace: 'api/v1',
  /**
   *
   * @param xhr
   */
  authorize(xhr) {
    let { access_token } = this.session.data.authenticated;
    if (isPresent(access_token)) {
      xhr.setRequestHeader('Authorization', `Bearer ${ access_token }`);
    }
  },
});
