import DS from 'ember-data';

const {Model, attr, belongsTo, hasMany} = DS;

export default Model.extend({
  type: attr('string'),
  user: belongsTo('user'),
  filter: attr(),
  result: attr()
});
