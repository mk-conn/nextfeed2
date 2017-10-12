import Ember from 'ember';
import Articles from 'frontend/feeds/feed/articles/route';

const {Route} = Ember;

export default Articles.extend({

  model(params) {
    let filter = {};

    if (params.filterUnread === true) {
      filter['read'] = false;
    }
    filter['keep'] = true;

    delete params.filterUnread;

    params['filter'] = filter;
    params['fields'] = {articles: 'title,description,author,keep,read,url,updated-date,categories'};
    params['page'] = {size: 10};

    return this.infinityModel('article', params, {});
  },

  renderTemplate(controller, model) {
    this.render('feeds/feed/articles', {
      into: 'application',
      outlet: 'column-one',
      model: model,
      controller: controller
    })
  },

  setupController(controller, model) {
    this._super(controller, model);
    const feed = Ember.Object.create({
      name: 'Starred',
      description: 'All your starred articles'
    });
    controller.set('feed', feed);
  }

  // renderTemplate() {
  //   this.render('feeds/articles/starred', {
  //     into: 'application',
  //     outlet: 'column-one'
  //   })
  // }

});
