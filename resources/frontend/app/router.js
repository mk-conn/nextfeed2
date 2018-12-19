import EmberRouter from '@ember/routing/router';
import config from './config/environment';

const Router = EmberRouter.extend({
  location: config.locationType,
  rootURL: config.rootURL
});

Router.map(function () {
  this.route('login');
  this.route('settings');

  this.route('feeds', { path: '/feeds' }, function () {
    this.route('add');

    this.route('folders', function () {
      this.route('add');
    });

    this.route('feed', { path: '/:feed_id' }, function () {
      this.route('articles', function () {
        this.route('article', { path: '/:article_id' });
        this.route('settings');
      });
    });

    this.route('articles', function () {
    });

  });
});

export default Router;