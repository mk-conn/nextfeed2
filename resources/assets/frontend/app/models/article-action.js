import DS from 'ember-data';

const {Model, attr} = DS;

export default Model.extend({
  result: attr()
});
