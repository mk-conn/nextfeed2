import Ember from 'ember';
import config from './config/environment';

const Router = Ember.Router.extend({
  location: config.locationType,
  rootURL: config.rootURL
});

Router.map(function() {
  this.route('login');
  this.route('settings');

  this.route('feeds', function() {
    this.route('feed', function() {
      this.route('articles');
    });
  });
});

export default Router;
