import ApplicationSerializer from 'frontend/application/serializer';

export default ApplicationSerializer.extend({
  normalize(modelClass, resourceHash) {
    if (resourceHash.meta) {
      resourceHash.attributes.meta = resourceHash.meta;

      delete resourceHash.meta;
    }

    return this._super(...arguments);
  }
});
