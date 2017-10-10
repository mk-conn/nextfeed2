import Ember from 'ember';
import Articles from 'frontend/feeds/feed/articles/route';

const {Route} = Ember;

export default Articles.extend({

  templateName: 'feeds/feed/articles',

  model(params) {

    let filter = {};

    if (params.filterUnread === true) {
      filter[ 'read' ] = false;
    }
    filter[ 'keep' ] = true;

    delete params.filterUnread;

    params[ 'filter' ] = filter;
    params[ 'fields' ] = {articles: 'title,description,author,keep,read,url,updated-date,categories'};
    params[ 'page' ] = {size: 10};

    return this.infinityModel('article', params, {});
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
