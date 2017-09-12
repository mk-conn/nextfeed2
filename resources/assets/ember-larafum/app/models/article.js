import DS from 'ember-data';

const {Model, attr, belongsTo} = DS;

export default Model.extend({
  title: attr('string'),
  author: attr('string'),
  language: attr('string'),
  publishDate: attr('date'),
  feed: belongsTo('feed')
});
