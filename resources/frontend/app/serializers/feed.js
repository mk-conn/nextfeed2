import ApplicationSerializer from 'frontend/application/serializer';
import { isPresent } from '@ember/utils';

export default ApplicationSerializer.extend({
  normalize(modelClass, resourceHash) {
    if (resourceHash.meta) {
      resourceHash.attributes.meta = resourceHash.meta;

      delete resourceHash.meta;
    }

    return this._super(...arguments);
  },
  /**
   *
   * @param snapshot
   * @param options
   * @returns {*|never}
   */
  serialize(snapshot, options) {
    let json = this._super(snapshot, options);

    if (isPresent(json.data.attributes.meta)) {
      delete json.data.attributes.meta;
    }
    return json;
  }
});
