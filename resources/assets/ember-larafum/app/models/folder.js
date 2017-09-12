import DS from 'ember-data';

const {Model, attr, hasMany, belongsTo} = DS;

export default Model.extend({
  name: attr('string'),
  feeds: hasMany('feed'),
  user: belongsTo('user')
});
