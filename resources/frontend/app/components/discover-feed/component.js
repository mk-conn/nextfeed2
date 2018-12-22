import Component from '@ember/component';
import { task } from 'ember-concurrency';
import { inject as service } from '@ember/service';
import $ from 'jquery';

export default Component.extend({
  result: null,
  store: service(),
  session: service(),
  discover: task(function* () {
    const appAdapter = this.store.adapterFor('application');
    let {access_token} = this.session.data.authenticated;
    let url = `${appAdapter.getUrlPrefix()}/feeds/discover`;
    let data = {
      url: this.get('url')
    };
    let result = yield $.getJSON({
      url: url,
      data: data,
      headers: {
        Authorization: `Bearer ${access_token}`
      }
    });
    this.set('result', result);
  }).drop()
});

