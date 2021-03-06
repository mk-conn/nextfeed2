import Ember from 'ember';

const {Controller} = Ember;

export default Controller.extend({
  queryParams: [ 'sort', 'filterUnread' ],
  filterUnread: false,
  sort: '-id,-updated-date'
});
