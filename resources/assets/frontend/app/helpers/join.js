import Ember from 'ember';

export function join(params) {
  console.log('params', params);
  return params[1].join(params[0])
}

export default Ember.Helper.helper(join);
