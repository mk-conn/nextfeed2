import DS from 'ember-data';

export default DS.JSONAPISerializer.extend({
  normalize(modelClass, resourceHash) {
    if (resourceHash.meta) {
      resourceHash.attributes.meta = resourceHash.meta;

      delete resourceHash.meta;
    }

    return this._super(...arguments);
  }
});
