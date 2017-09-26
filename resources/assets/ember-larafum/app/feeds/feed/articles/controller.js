import Ember from 'ember';

const {Controller} = Ember;

export default Controller.extend({
  queryParams: [ 'sort' ],
  sort: '-updated-date,-id'
});
