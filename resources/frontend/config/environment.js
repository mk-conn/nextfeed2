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
      trackHasMany: true,
      auto: true,
      enableIsDirty: true
    },

    APP: {
      // Here you can pass flags/options to your application instance
      // when it is created
    },

    'ember-simple-auth': {
      serverTokenEndpoint: 'oauth/token',
      tokenPropertyName: 'access_token',
      authorizer: 'authorizer:oauth2',
      applicationRootUrl: '/',
      authenticationRoute: 'login'
    },
    fontawesome: {
      defaultPrefix: 'far' // light icons
    },
    EmberHammertime: {
      touchActionOnAction: true,
      touchActionAttributes: ['onclick'],
      touchActionSelectors: ['button', 'input', 'a', 'textarea'],
      touchActionProperties: 'touch-action: manipulation; -ms-touch-action: manipulation; cursor: pointer;'
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
