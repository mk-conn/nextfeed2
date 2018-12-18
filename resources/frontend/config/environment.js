/* eslint-env node */
'use strict';

module.exports = function (environment) {
  let ENV = {
    modulePrefix: 'frontend',
    environment,
    rootURL: '/',
    locationType: 'auto',
    EmberENV: {
      FEATURES: {
        // Here you can enable experimental features on an ember canary build
        // e.g. 'with-controller': true
      },
      EXTEND_PROTOTYPES: {
        // Prevent Ember Data from overriding Date.parse.
        Date: false
      }
    },

    changeTracker: {
      trackHasMany: false,
      auto: true,
      enableIsDirty: true
    },

    APP: {
      // Here you can pass flags/options to your application instance
      // when it is created
    },

    'ember-simple-auth': {
      authorizer: 'authorizer:token',
      applicationRootUrl: '/',
      authenticationRoute: 'login',
      routeAfterAuthentification: 'index',
      routeIfAlreadyAuthenticated: 'feeds',
      // crossOriginWhitelist:
    },

    'ember-simple-auth-token': {
      refreshTokenPropertyName: 'access_token',
      tokenPropertyName: 'access_token',
      identificationField: 'username',
      passwordField: 'password',
      serverTokenEndpoint: '/auth/login',
      serverTokenRefreshEndpoint: '/auth/refresh',
      refreshLeeway: 5
    }

  };

  if (environment === 'development') {
    // ENV.APP.LOG_RESOLVER = true;
    // ENV.APP.LOG_ACTIVE_GENERATION = true;
    // ENV.APP.LOG_TRANSITIONS = true;
    // ENV.APP.LOG_TRANSITIONS_INTERNAL = true;
    // ENV.APP.LOG_VIEW_LOOKUPS = true;
  }

  if (environment === 'test') {
    // Testem prefers this...
    ENV.locationType = 'none';

    // keep test console output quieter
    ENV.APP.LOG_ACTIVE_GENERATION = false;
    ENV.APP.LOG_VIEW_LOOKUPS = false;

    ENV.APP.rootElement = '#ember-testing';
  }

  if (environment === 'production') {
  }

  return ENV;
};
