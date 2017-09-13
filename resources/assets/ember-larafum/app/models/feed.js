import DS from 'ember-data';

const {Model, attr, belongsTo, hasMany} = DS;

export default DS.Model.extend({
  name: attr('string'),
  url: attr('string'),
  feed_url: attr('string'),
  site_url: attr('string'),
  guid: attr('string'),
  description: attr('string'),
  icon: attr('string'),
  logo: attr('string'),
  language: attr('string'),
  etag: attr('string'),
  authUser: attr('string'),
  authPassword: attr('string'),
  order: attr('number'),
  user: belongsTo('user'),
  folder: belongsTo('folder'),
  articles: hasMany('article'),
  meta: attr()
});
