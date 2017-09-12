'use strict';

define('ember-larafum/tests/app.lint-test', [], function () {
  'use strict';

  QUnit.module('ESLint | app');

  QUnit.test('app.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'app.js should pass ESLint\n\n');
  });

  QUnit.test('application/adapter.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'application/adapter.js should pass ESLint\n\n');
  });

  QUnit.test('application/route.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'application/route.js should pass ESLint\n\n');
  });

  QUnit.test('components/article-item/component.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'components/article-item/component.js should pass ESLint\n\n');
  });

  QUnit.test('components/feed-item/component.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'components/feed-item/component.js should pass ESLint\n\n');
  });

  QUnit.test('components/folder-item/component.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'components/folder-item/component.js should pass ESLint\n\n');
  });

  QUnit.test('feeds/feed/articles/article/route.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'feeds/feed/articles/article/route.js should pass ESLint\n\n');
  });

  QUnit.test('feeds/feed/articles/controller.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'feeds/feed/articles/controller.js should pass ESLint\n\n');
  });

  QUnit.test('feeds/feed/articles/route.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'feeds/feed/articles/route.js should pass ESLint\n\n');
  });

  QUnit.test('feeds/feed/route.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'feeds/feed/route.js should pass ESLint\n\n');
  });

  QUnit.test('feeds/route.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'feeds/route.js should pass ESLint\n\n');
  });

  QUnit.test('index/route.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'index/route.js should pass ESLint\n\n');
  });

  QUnit.test('initializers/current-user.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'initializers/current-user.js should pass ESLint\n\n');
  });

  QUnit.test('login/controller.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'login/controller.js should pass ESLint\n\n');
  });

  QUnit.test('login/route.js', function (assert) {
    assert.expect(1);
    assert.ok(false, 'login/route.js should pass ESLint\n\n4:24 - \'service\' is assigned a value but never used. (no-unused-vars)');
  });

  QUnit.test('models/article.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'models/article.js should pass ESLint\n\n');
  });

  QUnit.test('models/feed.js', function (assert) {
    assert.expect(1);
    assert.ok(false, 'models/feed.js should pass ESLint\n\n3:8 - \'Model\' is assigned a value but never used. (no-unused-vars)');
  });

  QUnit.test('models/folder.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'models/folder.js should pass ESLint\n\n');
  });

  QUnit.test('models/setting.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'models/setting.js should pass ESLint\n\n');
  });

  QUnit.test('models/user.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'models/user.js should pass ESLint\n\n');
  });

  QUnit.test('resolver.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'resolver.js should pass ESLint\n\n');
  });

  QUnit.test('router.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'router.js should pass ESLint\n\n');
  });

  QUnit.test('services/current-user.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'services/current-user.js should pass ESLint\n\n');
  });

  QUnit.test('settings/route.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'settings/route.js should pass ESLint\n\n');
  });

  QUnit.test('transitions.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'transitions.js should pass ESLint\n\n');
  });
});
define('ember-larafum/tests/helpers/destroy-app', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = destroyApp;
  function destroyApp(application) {
    Ember.run(application, 'destroy');
  }
});
define('ember-larafum/tests/helpers/ember-simple-auth', ['exports', 'ember-simple-auth/authenticators/test'], function (exports, _test) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.authenticateSession = authenticateSession;
  exports.currentSession = currentSession;
  exports.invalidateSession = invalidateSession;


  var TEST_CONTAINER_KEY = 'authenticator:test'; /* global wait */

  function ensureAuthenticator(app, container) {
    var authenticator = container.lookup(TEST_CONTAINER_KEY);
    if (!authenticator) {
      app.register(TEST_CONTAINER_KEY, _test.default);
    }
  }

  function authenticateSession(app, sessionData) {
    var container = app.__container__;

    var session = container.lookup('service:session');
    ensureAuthenticator(app, container);
    session.authenticate(TEST_CONTAINER_KEY, sessionData);
    return wait();
  }

  function currentSession(app) {
    return app.__container__.lookup('service:session');
  }

  function invalidateSession(app) {
    var session = app.__container__.lookup('service:session');
    if (session.get('isAuthenticated')) {
      session.invalidate();
    }
    return wait();
  }
});
define('ember-larafum/tests/helpers/module-for-acceptance', ['exports', 'qunit', 'ember-larafum/tests/helpers/start-app', 'ember-larafum/tests/helpers/destroy-app'], function (exports, _qunit, _startApp, _destroyApp) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  exports.default = function (name) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    (0, _qunit.module)(name, {
      beforeEach: function beforeEach() {
        this.application = (0, _startApp.default)();

        if (options.beforeEach) {
          return options.beforeEach.apply(this, arguments);
        }
      },
      afterEach: function afterEach() {
        var _this = this;

        var afterEach = options.afterEach && options.afterEach.apply(this, arguments);
        return resolve(afterEach).then(function () {
          return (0, _destroyApp.default)(_this.application);
        });
      }
    });
  };

  var resolve = Ember.RSVP.resolve;
});
define('ember-larafum/tests/helpers/resolver', ['exports', 'ember-larafum/resolver', 'ember-larafum/config/environment'], function (exports, _resolver, _environment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });


  var resolver = _resolver.default.create();

  resolver.namespace = {
    modulePrefix: _environment.default.modulePrefix,
    podModulePrefix: _environment.default.podModulePrefix
  };

  exports.default = resolver;
});
define('ember-larafum/tests/helpers/start-app', ['exports', 'ember-larafum/app', 'ember-larafum/config/environment'], function (exports, _app, _environment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = startApp;
  function startApp(attrs) {
    var attributes = Ember.merge({}, _environment.default.APP);
    attributes = Ember.merge(attributes, attrs); // use defaults, but you can override;

    return Ember.run(function () {
      var application = _app.default.create(attributes);
      application.setupForTesting();
      application.injectTestHelpers();
      return application;
    });
  }
});
define('ember-larafum/tests/integration/components/article-item/component-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleForComponent)('article-item', 'Integration | Component | article item', {
    integration: true
  });

  (0, _emberQunit.test)('it renders', function (assert) {
    // Set any properties with this.set('myProperty', 'value');
    // Handle any actions with this.on('myAction', function(val) { ... });

    this.render(Ember.HTMLBars.template({
      "id": "kBqgHR87",
      "block": "{\"symbols\":[],\"statements\":[[1,[18,\"article-item\"],false]],\"hasEval\":false}",
      "meta": {}
    }));

    assert.equal(this.$().text().trim(), '');

    // Template block usage:
    this.render(Ember.HTMLBars.template({
      "id": "FwPwMS2S",
      "block": "{\"symbols\":[],\"statements\":[[0,\"\\n\"],[4,\"article-item\",null,null,{\"statements\":[[0,\"      template block text\\n\"]],\"parameters\":[]},null],[0,\"  \"]],\"hasEval\":false}",
      "meta": {}
    }));

    assert.equal(this.$().text().trim(), 'template block text');
  });
});
define('ember-larafum/tests/integration/components/feed-item/component-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleForComponent)('feed-item', 'Integration | Component | feed item', {
    integration: true
  });

  (0, _emberQunit.test)('it renders', function (assert) {
    // Set any properties with this.set('myProperty', 'value');
    // Handle any actions with this.on('myAction', function(val) { ... });

    this.render(Ember.HTMLBars.template({
      "id": "PjpfwjTm",
      "block": "{\"symbols\":[],\"statements\":[[1,[18,\"feed-item\"],false]],\"hasEval\":false}",
      "meta": {}
    }));

    assert.equal(this.$().text().trim(), '');

    // Template block usage:
    this.render(Ember.HTMLBars.template({
      "id": "d9HBSQ/N",
      "block": "{\"symbols\":[],\"statements\":[[0,\"\\n\"],[4,\"feed-item\",null,null,{\"statements\":[[0,\"      template block text\\n\"]],\"parameters\":[]},null],[0,\"  \"]],\"hasEval\":false}",
      "meta": {}
    }));

    assert.equal(this.$().text().trim(), 'template block text');
  });
});
define('ember-larafum/tests/integration/components/folder-item/component-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleForComponent)('folder-item', 'Integration | Component | folder item', {
    integration: true
  });

  (0, _emberQunit.test)('it renders', function (assert) {
    // Set any properties with this.set('myProperty', 'value');
    // Handle any actions with this.on('myAction', function(val) { ... });

    this.render(Ember.HTMLBars.template({
      "id": "TAl5v2hX",
      "block": "{\"symbols\":[],\"statements\":[[1,[18,\"folder-item\"],false]],\"hasEval\":false}",
      "meta": {}
    }));

    assert.equal(this.$().text().trim(), '');

    // Template block usage:
    this.render(Ember.HTMLBars.template({
      "id": "VZRiaLtE",
      "block": "{\"symbols\":[],\"statements\":[[0,\"\\n\"],[4,\"folder-item\",null,null,{\"statements\":[[0,\"      template block text\\n\"]],\"parameters\":[]},null],[0,\"  \"]],\"hasEval\":false}",
      "meta": {}
    }));

    assert.equal(this.$().text().trim(), 'template block text');
  });
});
define('ember-larafum/tests/test-helper', ['ember-larafum/tests/helpers/resolver', 'ember-qunit', 'ember-cli-qunit'], function (_resolver, _emberQunit, _emberCliQunit) {
  'use strict';

  (0, _emberQunit.setResolver)(_resolver.default);
  (0, _emberCliQunit.start)();
});
define('ember-larafum/tests/tests.lint-test', [], function () {
  'use strict';

  QUnit.module('ESLint | tests');

  QUnit.test('helpers/destroy-app.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'helpers/destroy-app.js should pass ESLint\n\n');
  });

  QUnit.test('helpers/module-for-acceptance.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'helpers/module-for-acceptance.js should pass ESLint\n\n');
  });

  QUnit.test('helpers/resolver.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'helpers/resolver.js should pass ESLint\n\n');
  });

  QUnit.test('helpers/start-app.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'helpers/start-app.js should pass ESLint\n\n');
  });

  QUnit.test('integration/components/article-item/component-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'integration/components/article-item/component-test.js should pass ESLint\n\n');
  });

  QUnit.test('integration/components/feed-item/component-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'integration/components/feed-item/component-test.js should pass ESLint\n\n');
  });

  QUnit.test('integration/components/folder-item/component-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'integration/components/folder-item/component-test.js should pass ESLint\n\n');
  });

  QUnit.test('test-helper.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'test-helper.js should pass ESLint\n\n');
  });

  QUnit.test('unit/application/adapter-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/application/adapter-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/application/route-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/application/route-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/feeds/feed/articles/article/route-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/feeds/feed/articles/article/route-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/feeds/feed/articles/controller-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/feeds/feed/articles/controller-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/feeds/feed/articles/route-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/feeds/feed/articles/route-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/feeds/feed/route-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/feeds/feed/route-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/feeds/route-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/feeds/route-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/index/route-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/index/route-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/initializers/current-user-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/initializers/current-user-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/login/controller-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/login/controller-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/login/route-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/login/route-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/models/article-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/models/article-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/models/feed-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/models/feed-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/models/folder-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/models/folder-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/models/setting-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/models/setting-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/models/user-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/models/user-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/services/current-user-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/services/current-user-test.js should pass ESLint\n\n');
  });

  QUnit.test('unit/settings/route-test.js', function (assert) {
    assert.expect(1);
    assert.ok(true, 'unit/settings/route-test.js should pass ESLint\n\n');
  });
});
define('ember-larafum/tests/unit/application/adapter-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('adapter:application', 'Unit | Adapter | application', {
    // Specify the other units that are required for this test.
    // needs: ['serializer:foo']
  });

  // Replace this with your real tests.
  (0, _emberQunit.test)('it exists', function (assert) {
    var adapter = this.subject();
    assert.ok(adapter);
  });
});
define('ember-larafum/tests/unit/application/route-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('route:application', 'Unit | Route | application', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var route = this.subject();
    assert.ok(route);
  });
});
define('ember-larafum/tests/unit/feeds/feed/articles/article/route-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('route:feeds/feed/articles/article', 'Unit | Route | feeds/feed/articles/article', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var route = this.subject();
    assert.ok(route);
  });
});
define('ember-larafum/tests/unit/feeds/feed/articles/controller-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('controller:feeds/feed/articles', 'Unit | Controller | feeds/feed/articles', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  // Replace this with your real tests.
  (0, _emberQunit.test)('it exists', function (assert) {
    var controller = this.subject();
    assert.ok(controller);
  });
});
define('ember-larafum/tests/unit/feeds/feed/articles/route-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('route:feeds/feed/articles', 'Unit | Route | feeds/feed/articles', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var route = this.subject();
    assert.ok(route);
  });
});
define('ember-larafum/tests/unit/feeds/feed/route-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('route:feeds/feed', 'Unit | Route | feeds/feed', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var route = this.subject();
    assert.ok(route);
  });
});
define('ember-larafum/tests/unit/feeds/route-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('route:feeds', 'Unit | Route | feeds', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var route = this.subject();
    assert.ok(route);
  });
});
define('ember-larafum/tests/unit/index/route-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('route:index', 'Unit | Route | index', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var route = this.subject();
    assert.ok(route);
  });
});
define('ember-larafum/tests/unit/initializers/current-user-test', ['ember-larafum/initializers/current-user', 'qunit', 'ember-larafum/tests/helpers/destroy-app'], function (_currentUser, _qunit, _destroyApp) {
  'use strict';

  (0, _qunit.module)('Unit | Initializer | current user', {
    beforeEach: function beforeEach() {
      var _this = this;

      Ember.run(function () {
        _this.application = Ember.Application.create();
        _this.application.deferReadiness();
      });
    },
    afterEach: function afterEach() {
      (0, _destroyApp.default)(this.application);
    }
  });

  // Replace this with your real tests.
  (0, _qunit.test)('it works', function (assert) {
    (0, _currentUser.initialize)(this.application);

    // you would normally confirm the results of the initializer here
    assert.ok(true);
  });
});
define('ember-larafum/tests/unit/login/controller-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('controller:login', 'Unit | Controller | login', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  // Replace this with your real tests.
  (0, _emberQunit.test)('it exists', function (assert) {
    var controller = this.subject();
    assert.ok(controller);
  });
});
define('ember-larafum/tests/unit/login/route-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('route:login', 'Unit | Route | login', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var route = this.subject();
    assert.ok(route);
  });
});
define('ember-larafum/tests/unit/models/article-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleForModel)('article', 'Unit | Model | article', {
    // Specify the other units that are required for this test.
    needs: []
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var model = this.subject();
    // let store = this.store();
    assert.ok(!!model);
  });
});
define('ember-larafum/tests/unit/models/feed-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleForModel)('feed', 'Unit | Model | feed', {
    // Specify the other units that are required for this test.
    needs: []
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var model = this.subject();
    // let store = this.store();
    assert.ok(!!model);
  });
});
define('ember-larafum/tests/unit/models/folder-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleForModel)('folder', 'Unit | Model | folder', {
    // Specify the other units that are required for this test.
    needs: []
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var model = this.subject();
    // let store = this.store();
    assert.ok(!!model);
  });
});
define('ember-larafum/tests/unit/models/setting-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleForModel)('setting', 'Unit | Model | setting', {
    // Specify the other units that are required for this test.
    needs: []
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var model = this.subject();
    // let store = this.store();
    assert.ok(!!model);
  });
});
define('ember-larafum/tests/unit/models/user-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleForModel)('user', 'Unit | Model | user', {
    // Specify the other units that are required for this test.
    needs: []
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var model = this.subject();
    // let store = this.store();
    assert.ok(!!model);
  });
});
define('ember-larafum/tests/unit/services/current-user-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('service:current-user', 'Unit | Service | current user', {
    // Specify the other units that are required for this test.
    // needs: ['service:foo']
  });

  // Replace this with your real tests.
  (0, _emberQunit.test)('it exists', function (assert) {
    var service = this.subject();
    assert.ok(service);
  });
});
define('ember-larafum/tests/unit/settings/route-test', ['ember-qunit'], function (_emberQunit) {
  'use strict';

  (0, _emberQunit.moduleFor)('route:settings', 'Unit | Route | settings', {
    // Specify the other units that are required for this test.
    // needs: ['controller:foo']
  });

  (0, _emberQunit.test)('it exists', function (assert) {
    var route = this.subject();
    assert.ok(route);
  });
});
require('ember-larafum/tests/test-helper');
EmberENV.TESTS_FILE_LOADED = true;
//# sourceMappingURL=tests.map
