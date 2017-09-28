"use strict";



define('ember-larafum/app', ['exports', 'ember-larafum/resolver', 'ember-load-initializers', 'ember-larafum/config/environment'], function (exports, _resolver, _emberLoadInitializers, _environment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });


  var App = Ember.Application.extend({
    modulePrefix: _environment.default.modulePrefix,
    podModulePrefix: _environment.default.podModulePrefix,
    Resolver: _resolver.default
  });

  (0, _emberLoadInitializers.default)(App, _environment.default.modulePrefix);

  exports.default = App;
});
define('ember-larafum/application/adapter', ['exports', 'ember-data', 'ember-simple-auth/mixins/data-adapter-mixin'], function (exports, _emberData, _dataAdapterMixin) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _emberData.default.JSONAPIAdapter.extend(_dataAdapterMixin.default, {
    authorizer: 'authorizer:token',

    namespace: 'api/v1'
  });
});
define('ember-larafum/application/route', ['exports', 'ember-simple-auth/mixins/application-route-mixin'], function (exports, _applicationRouteMixin) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Route = Ember.Route,
      service = Ember.inject.service;
  exports.default = Route.extend(_applicationRouteMixin.default, {
    currentUser: service('current-user'),

    session: service('session'),

    beforeModel: function beforeModel() {
      return this._loadCurrentUser();
    },
    sessionAuthenticated: function sessionAuthenticated() {
      this._super.apply(this, arguments);
      this._loadCurrentUser();
    },
    _loadCurrentUser: function _loadCurrentUser() {
      var _this = this;

      return this.get('currentUser').load().catch(function () {
        return _this.get('session').invalidate();
      });
    },
    setupController: function setupController(controller, model) {
      this._super(controller, model);
      controller.set('currentUser', this.get('currentUser'));
      controller.set('session', this.get('session'));
    },


    actions: {
      updateAllFeeds: function updateAllFeeds() {},
      invalidateSession: function invalidateSession() {
        this.get('session').invalidate();
      }
    }
  });
});
define('ember-larafum/application/serializer', ['exports', 'ember-data', 'ember-data-change-tracker/mixins/keep-only-changed'], function (exports, _emberData, _keepOnlyChanged) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _emberData.default.JSONAPISerializer.extend(_keepOnlyChanged.default);
});
define("ember-larafum/application/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "E0PWXFMu", "block": "{\"symbols\":[],\"statements\":[[6,\"header\"],[7],[0,\"\\n  \"],[6,\"div\"],[9,\"class\",\"top nav left\"],[7],[0,\"\\n    \"],[6,\"a\"],[9,\"class\",\"navbar-brand\"],[9,\"href\",\"/\"],[7],[0,\"Nextfeed\"],[8],[0,\"\\n\\n\"],[4,\"link-to\",[\"feeds\"],[[\"tagName\",\"classNames\",\"activeClass\"],[\"div\",\"action\",\"active\"]],{\"statements\":[[0,\"      \"],[6,\"a\"],[9,\"class\",\"nav-link\"],[9,\"href\",\"#\"],[7],[0,\"Feeds\"],[8],[0,\"\\n\"]],\"parameters\":[]},null],[0,\"\\n    \"],[1,[25,\"outlet\",[\"top-bar-left\"],null],false],[0,\"\\n  \"],[8],[0,\"\\n\\n  \"],[6,\"div\"],[9,\"class\",\"top nav right\"],[7],[0,\"\\n    \"],[6,\"a\"],[9,\"class\",\"nav-link\"],[9,\"href\",\"#\"],[3,\"action\",[[19,0,[]],[25,\"route-action\",[\"updateAllFeeds\"],null]]],[7],[0,\"\\n      \"],[6,\"i\"],[9,\"class\",\"fa fa-refresh\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n    \"],[8],[0,\"\\n\"],[4,\"link-to\",[\"settings\"],[[\"tagName\",\"classNames\"],[\"li\",\"nav-item\"]],{\"statements\":[[0,\"      \"],[6,\"a\"],[9,\"class\",\"nav-link\"],[9,\"href\",\"#\"],[7],[6,\"i\"],[9,\"class\",\"fa fa-cogs\"],[9,\"aria-hidden\",\"true\"],[7],[8],[8],[0,\"\\n\"]],\"parameters\":[]},null],[0,\"    \"],[6,\"a\"],[9,\"class\",\"nav-link\"],[9,\"href\",\"#\"],[3,\"action\",[[19,0,[]],[25,\"route-action\",[\"invalidateSession\"],null]]],[7],[0,\"\\n      \"],[6,\"i\"],[9,\"class\",\"fa fa-sign-out\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n    \"],[8],[0,\"\\n    \"],[1,[25,\"outlet\",[\"top-bar-right\"],null],false],[0,\"\\n  \"],[8],[0,\"\\n\\n\"],[8],[0,\"\\n\\n\"],[6,\"div\"],[9,\"id\",\"content\"],[7],[0,\"\\n  \"],[1,[18,\"outlet\"],false],[0,\"\\n\"],[8],[0,\"\\n\\n\"],[6,\"div\"],[9,\"id\",\"side-bar\"],[7],[0,\"\\n  \"],[1,[25,\"outlet\",[\"side-bar\"],null],false],[0,\"\\n\"],[8],[0,\"\\n\\n\"],[6,\"div\"],[9,\"id\",\"column-one\"],[7],[0,\"\\n  \"],[1,[25,\"outlet\",[\"column-one\"],null],false],[0,\"\\n\"],[8],[0,\"\\n\\n\"],[6,\"div\"],[9,\"id\",\"column-two\"],[7],[0,\"\\n  \"],[1,[25,\"outlet\",[\"column-two\"],null],false],[0,\"\\n\"],[8],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/application/template.hbs" } });
});
define('ember-larafum/components/article-list-item/component', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Component = Ember.Component,
      computed = Ember.computed,
      get = Ember.get,
      String = Ember.String,
      $ = Ember.$;
  exports.default = Component.extend({
    classNameBindings: ['isRead:read:unread'],

    isRead: computed('article.read', function () {
      return get(this, 'article.read');
    }),

    articleDescription: computed('article.description', function () {
      var stripAt = 240;
      // let description = get(this, 'article.description');
      // description = description.replace(/<a.*?a>/gm, "");
      var description = '';
      try {
        description = $(get(this, 'article.description')).text();
        if (description.length > stripAt) {
          description = description.slice(0, stripAt) + ' ...';
        }
      } catch (e) {}
      return String.htmlSafe(description);
    }),

    didRender: function didRender() {
      var component = this.$();

      $('img', component).each(function () {
        $(this).addClass('img-fluid');
      });
    }
  });
});
define("ember-larafum/components/article-list-item/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "FSuFpjI3", "block": "{\"symbols\":[],\"statements\":[[6,\"div\"],[9,\"class\",\"d-flex w-100 justify-content-between\"],[7],[0,\"\\n  \"],[6,\"h5\"],[9,\"class\",\"article-title mb-1\"],[7],[1,[20,[\"article\",\"title\"]],false],[8],[0,\"\\n  \"],[6,\"small\"],[7],[1,[25,\"moment-format\",[[19,0,[\"article\",\"updatedDate\"]],\"Y-MM-DD HH:mm\"],null],false],[8],[0,\"\\n\"],[8],[0,\"\\n\"],[6,\"p\"],[7],[1,[18,\"articleDescription\"],false],[8],[0,\"\\n\"],[6,\"div\"],[9,\"class\",\"actions\"],[7],[0,\"\\n\"],[4,\"link-to\",[\"feeds.feed.articles.article\",[19,0,[\"article\",\"id\"]]],[[\"classNames\"],[\"btn btn-outline-secondary\"]],{\"statements\":[[0,\"    \"],[6,\"i\"],[9,\"class\",\"fa fa-expand\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]},null],[0,\"  \"],[6,\"a\"],[9,\"class\",\"btn btn-outline-secondary\"],[10,\"href\",[20,[\"article\",\"url\"]],null],[9,\"target\",\"_blank\"],[9,\"data-content\",\"Open article in new tab\"],[3,\"action\",[[19,0,[]],\"originalArticle\"],[[\"preventDefault\"],[false]]],[7],[0,\"\\n    \"],[6,\"i\"],[9,\"class\",\"fa fa-external-link\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n  \"],[8],[0,\"\\n  \"],[6,\"a\"],[9,\"class\",\"btn btn-outline-secondary\"],[10,\"href\",[26,[\"mailto:?subject=Article: \",[20,[\"article\",\"title\"]],\"&body=\",[20,[\"article\",\"title\"]],\"%0A%0A\",[18,\"articleDescription\"],\"%0A%0A\",[20,[\"article\",\"url\"]]]]],[7],[0,\"\\n    \"],[6,\"i\"],[9,\"class\",\"fa fa-share\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n  \"],[8],[0,\"\\n  \"],[6,\"a\"],[9,\"class\",\"btn btn-outline-secondary\"],[10,\"data-content\",[26,[[18,\"readUnreadHint\"]]]],[3,\"action\",[[19,0,[]],[25,\"route-action\",[\"read\",[19,0,[\"article\"]]],null]]],[7],[0,\"\\n\"],[4,\"if\",[[19,0,[\"article\",\"read\"]]],null,{\"statements\":[[0,\"      \"],[6,\"i\"],[9,\"class\",\"fa fa-check-circle-o\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]},{\"statements\":[[0,\"      \"],[6,\"i\"],[9,\"class\",\"fa fa-circle-o\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]}],[0,\"  \"],[8],[0,\"\\n  \"],[6,\"a\"],[9,\"class\",\"btn btn-outline-secondary\"],[3,\"action\",[[19,0,[]],[25,\"route-action\",[\"keep\",[19,0,[\"article\"]]],null]]],[7],[0,\"\\n\"],[4,\"if\",[[19,0,[\"article\",\"keep\"]]],null,{\"statements\":[[0,\"      \"],[6,\"i\"],[9,\"class\",\"fa fa-star\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]},{\"statements\":[[0,\"      \"],[6,\"i\"],[9,\"class\",\"fa fa-star-o\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]}],[0,\"  \"],[8],[0,\"\\n\"],[8],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/components/article-list-item/template.hbs" } });
});
define('ember-larafum/components/bs-accordion', ['exports', 'ember-bootstrap/components/bs-accordion'], function (exports, _bsAccordion) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsAccordion.default;
    }
  });
});
define('ember-larafum/components/bs-accordion/item', ['exports', 'ember-bootstrap/components/bs-accordion/item'], function (exports, _item) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _item.default;
    }
  });
});
define('ember-larafum/components/bs-accordion/item/body', ['exports', 'ember-bootstrap/components/bs-accordion/item/body'], function (exports, _body) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _body.default;
    }
  });
});
define('ember-larafum/components/bs-accordion/item/title', ['exports', 'ember-bootstrap/components/bs-accordion/item/title'], function (exports, _title) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _title.default;
    }
  });
});
define('ember-larafum/components/bs-alert', ['exports', 'ember-bootstrap/components/bs-alert'], function (exports, _bsAlert) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsAlert.default;
    }
  });
});
define('ember-larafum/components/bs-button-group', ['exports', 'ember-bootstrap/components/bs-button-group'], function (exports, _bsButtonGroup) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsButtonGroup.default;
    }
  });
});
define('ember-larafum/components/bs-button-group/button', ['exports', 'ember-bootstrap/components/bs-button-group/button'], function (exports, _button) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _button.default;
    }
  });
});
define('ember-larafum/components/bs-button', ['exports', 'ember-bootstrap/components/bs-button'], function (exports, _bsButton) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsButton.default;
    }
  });
});
define('ember-larafum/components/bs-collapse', ['exports', 'ember-bootstrap/components/bs-collapse'], function (exports, _bsCollapse) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsCollapse.default;
    }
  });
});
define('ember-larafum/components/bs-dropdown', ['exports', 'ember-bootstrap/components/bs-dropdown'], function (exports, _bsDropdown) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsDropdown.default;
    }
  });
});
define('ember-larafum/components/bs-dropdown/button', ['exports', 'ember-bootstrap/components/bs-dropdown/button'], function (exports, _button) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _button.default;
    }
  });
});
define('ember-larafum/components/bs-dropdown/menu', ['exports', 'ember-bootstrap/components/bs-dropdown/menu'], function (exports, _menu) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _menu.default;
    }
  });
});
define('ember-larafum/components/bs-dropdown/menu/divider', ['exports', 'ember-bootstrap/components/bs-dropdown/menu/divider'], function (exports, _divider) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _divider.default;
    }
  });
});
define('ember-larafum/components/bs-dropdown/menu/item', ['exports', 'ember-bootstrap/components/bs-dropdown/menu/item'], function (exports, _item) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _item.default;
    }
  });
});
define('ember-larafum/components/bs-dropdown/menu/link-to', ['exports', 'ember-bootstrap/components/bs-dropdown/menu/link-to'], function (exports, _linkTo) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _linkTo.default;
    }
  });
});
define('ember-larafum/components/bs-dropdown/toggle', ['exports', 'ember-bootstrap/components/bs-dropdown/toggle'], function (exports, _toggle) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _toggle.default;
    }
  });
});
define('ember-larafum/components/bs-form', ['exports', 'ember-bootstrap/components/bs-form'], function (exports, _bsForm) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsForm.default;
    }
  });
});
define('ember-larafum/components/bs-form/element', ['exports', 'ember-bootstrap/components/bs-form/element'], function (exports, _element) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _element.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/control', ['exports', 'ember-bootstrap/components/bs-form/element/control'], function (exports, _control) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _control.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/control/checkbox', ['exports', 'ember-bootstrap/components/bs-form/element/control/checkbox'], function (exports, _checkbox) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _checkbox.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/control/input', ['exports', 'ember-bootstrap/components/bs-form/element/control/input'], function (exports, _input) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _input.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/control/textarea', ['exports', 'ember-bootstrap/components/bs-form/element/control/textarea'], function (exports, _textarea) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _textarea.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/errors', ['exports', 'ember-bootstrap/components/bs-form/element/errors'], function (exports, _errors) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _errors.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/feedback-icon', ['exports', 'ember-bootstrap/components/bs-form/element/feedback-icon'], function (exports, _feedbackIcon) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _feedbackIcon.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/help-text', ['exports', 'ember-bootstrap/components/bs-form/element/help-text'], function (exports, _helpText) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _helpText.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/label', ['exports', 'ember-bootstrap/components/bs-form/element/label'], function (exports, _label) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _label.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/layout/horizontal', ['exports', 'ember-bootstrap/components/bs-form/element/layout/horizontal'], function (exports, _horizontal) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _horizontal.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/layout/horizontal/checkbox', ['exports', 'ember-bootstrap/components/bs-form/element/layout/horizontal/checkbox'], function (exports, _checkbox) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _checkbox.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/layout/inline', ['exports', 'ember-bootstrap/components/bs-form/element/layout/inline'], function (exports, _inline) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _inline.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/layout/inline/checkbox', ['exports', 'ember-bootstrap/components/bs-form/element/layout/inline/checkbox'], function (exports, _checkbox) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _checkbox.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/layout/vertical', ['exports', 'ember-bootstrap/components/bs-form/element/layout/vertical'], function (exports, _vertical) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _vertical.default;
    }
  });
});
define('ember-larafum/components/bs-form/element/layout/vertical/checkbox', ['exports', 'ember-bootstrap/components/bs-form/element/layout/vertical/checkbox'], function (exports, _checkbox) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _checkbox.default;
    }
  });
});
define('ember-larafum/components/bs-form/group', ['exports', 'ember-bootstrap/components/bs-form/group'], function (exports, _group) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _group.default;
    }
  });
});
define('ember-larafum/components/bs-modal-simple', ['exports', 'ember-bootstrap/components/bs-modal-simple'], function (exports, _bsModalSimple) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsModalSimple.default;
    }
  });
});
define('ember-larafum/components/bs-modal', ['exports', 'ember-bootstrap/components/bs-modal'], function (exports, _bsModal) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsModal.default;
    }
  });
});
define('ember-larafum/components/bs-modal/body', ['exports', 'ember-bootstrap/components/bs-modal/body'], function (exports, _body) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _body.default;
    }
  });
});
define('ember-larafum/components/bs-modal/dialog', ['exports', 'ember-bootstrap/components/bs-modal/dialog'], function (exports, _dialog) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _dialog.default;
    }
  });
});
define('ember-larafum/components/bs-modal/footer', ['exports', 'ember-bootstrap/components/bs-modal/footer'], function (exports, _footer) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _footer.default;
    }
  });
});
define('ember-larafum/components/bs-modal/header', ['exports', 'ember-bootstrap/components/bs-modal/header'], function (exports, _header) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _header.default;
    }
  });
});
define('ember-larafum/components/bs-modal/header/close', ['exports', 'ember-bootstrap/components/bs-modal/header/close'], function (exports, _close) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _close.default;
    }
  });
});
define('ember-larafum/components/bs-modal/header/title', ['exports', 'ember-bootstrap/components/bs-modal/header/title'], function (exports, _title) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _title.default;
    }
  });
});
define('ember-larafum/components/bs-nav', ['exports', 'ember-bootstrap/components/bs-nav'], function (exports, _bsNav) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsNav.default;
    }
  });
});
define('ember-larafum/components/bs-nav/item', ['exports', 'ember-bootstrap/components/bs-nav/item'], function (exports, _item) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _item.default;
    }
  });
});
define('ember-larafum/components/bs-nav/link-to', ['exports', 'ember-bootstrap/components/bs-nav/link-to'], function (exports, _linkTo) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _linkTo.default;
    }
  });
});
define('ember-larafum/components/bs-navbar', ['exports', 'ember-bootstrap/components/bs-navbar'], function (exports, _bsNavbar) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsNavbar.default;
    }
  });
});
define('ember-larafum/components/bs-navbar/content', ['exports', 'ember-bootstrap/components/bs-navbar/content'], function (exports, _content) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _content.default;
    }
  });
});
define('ember-larafum/components/bs-navbar/link-to', ['exports', 'ember-bootstrap/components/bs-navbar/link-to'], function (exports, _linkTo) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _linkTo.default;
    }
  });
});
define('ember-larafum/components/bs-navbar/nav', ['exports', 'ember-bootstrap/components/bs-navbar/nav'], function (exports, _nav) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _nav.default;
    }
  });
});
define('ember-larafum/components/bs-navbar/toggle', ['exports', 'ember-bootstrap/components/bs-navbar/toggle'], function (exports, _toggle) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _toggle.default;
    }
  });
});
define('ember-larafum/components/bs-popover', ['exports', 'ember-bootstrap/components/bs-popover'], function (exports, _bsPopover) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsPopover.default;
    }
  });
});
define('ember-larafum/components/bs-popover/element', ['exports', 'ember-bootstrap/components/bs-popover/element'], function (exports, _element) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _element.default;
    }
  });
});
define('ember-larafum/components/bs-progress', ['exports', 'ember-bootstrap/components/bs-progress'], function (exports, _bsProgress) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsProgress.default;
    }
  });
});
define('ember-larafum/components/bs-progress/bar', ['exports', 'ember-bootstrap/components/bs-progress/bar'], function (exports, _bar) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bar.default;
    }
  });
});
define('ember-larafum/components/bs-tab', ['exports', 'ember-bootstrap/components/bs-tab'], function (exports, _bsTab) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsTab.default;
    }
  });
});
define('ember-larafum/components/bs-tab/pane', ['exports', 'ember-bootstrap/components/bs-tab/pane'], function (exports, _pane) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _pane.default;
    }
  });
});
define('ember-larafum/components/bs-tooltip', ['exports', 'ember-bootstrap/components/bs-tooltip'], function (exports, _bsTooltip) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsTooltip.default;
    }
  });
});
define('ember-larafum/components/bs-tooltip/element', ['exports', 'ember-bootstrap/components/bs-tooltip/element'], function (exports, _element) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _element.default;
    }
  });
});
define('ember-larafum/components/ember-wormhole', ['exports', 'ember-wormhole/components/ember-wormhole'], function (exports, _emberWormhole) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _emberWormhole.default;
    }
  });
});
define('ember-larafum/components/feed-item/component', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.Component.extend({
    classNames: ['list-group-item feed-item']
  });
});
define("ember-larafum/components/feed-item/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "vhUTdDCB", "block": "{\"symbols\":[],\"statements\":[[4,\"link-to\",[\"feeds.feed.articles\",[19,0,[\"feed\",\"id\"]]],null,{\"statements\":[[0,\"  \"],[6,\"div\"],[9,\"class\",\"d-flex justify-content-between\"],[7],[0,\"\\n    \"],[6,\"div\"],[7],[0,\"\\n      \"],[6,\"span\"],[9,\"class\",\"feed-icon\"],[7],[0,\"\\n\"],[4,\"if\",[[19,0,[\"feed\",\"icon\"]]],null,{\"statements\":[[0,\"          \"],[6,\"img\"],[10,\"src\",[26,[[20,[\"feed\",\"icon\"]]]]],[9,\"alt\",\"feed icon\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]},{\"statements\":[[0,\"          \"],[6,\"img\"],[9,\"src\",\"/ember-frontend/img/feed-icon.png\"],[9,\"alt\",\"feed icon\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]}],[0,\"      \"],[8],[0,\"\\n      \"],[1,[20,[\"feed\",\"name\"]],false],[0,\"\\n    \"],[8],[0,\"\\n    \"],[6,\"span\"],[9,\"class\",\"badge badge-secondary\"],[7],[1,[20,[\"feed\",\"meta\",\"articles-count\"]],false],[8],[0,\"\\n  \"],[8],[0,\"\\n\"]],\"parameters\":[]},null],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/components/feed-item/template.hbs" } });
});
define('ember-larafum/components/folder-item/component', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.Component.extend({
    classNames: ['list-group-item', 'folder-item']
  });
});
define("ember-larafum/components/folder-item/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "eWbloG9i", "block": "{\"symbols\":[\"feed\"],\"statements\":[[6,\"div\"],[9,\"class\",\"folder\"],[7],[0,\"\\n\"],[4,\"if\",[[19,0,[\"folder\",\"open\"]]],null,{\"statements\":[[0,\"    \"],[6,\"span\"],[9,\"class\",\"icon action\"],[3,\"action\",[[19,0,[]],[25,\"route-action\",[\"closeFolder\",[19,0,[\"folder\"]]],null]]],[7],[0,\"\\n      \"],[6,\"i\"],[9,\"class\",\"fa fa-folder-open-o\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n    \"],[8],[0,\"\\n\"]],\"parameters\":[]},{\"statements\":[[0,\"    \"],[6,\"span\"],[9,\"class\",\"icon action\"],[3,\"action\",[[19,0,[]],[25,\"route-action\",[\"openFolder\",[19,0,[\"folder\"]]],null]]],[7],[0,\"\\n      \"],[6,\"i\"],[9,\"class\",\"fa fa-folder-o\"],[9,\"aria-hidden\",\"true\"],[7],[8],[0,\"\\n    \"],[8],[0,\"\\n\"]],\"parameters\":[]}],[0,\"  \"],[1,[20,[\"folder\",\"name\"]],false],[0,\"\\n\"],[8],[0,\"\\n\\n\"],[4,\"if\",[[19,0,[\"folder\",\"open\"]]],null,{\"statements\":[[0,\"  \"],[6,\"div\"],[9,\"class\",\"list-group\"],[7],[0,\"\\n\"],[4,\"each\",[[19,0,[\"folder\",\"feeds\"]]],null,{\"statements\":[[0,\"      \"],[1,[25,\"feed-item\",null,[[\"feed\"],[[19,1,[]]]]],false],[0,\"\\n\"]],\"parameters\":[1]},null],[0,\"  \"],[8],[0,\"\\n\"]],\"parameters\":[]},null],[0,\"\\n\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/components/folder-item/template.hbs" } });
});
define('ember-larafum/components/full-article/component', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Component = Ember.Component,
      computed = Ember.computed,
      get = Ember.get,
      String = Ember.String,
      $ = Ember.$,
      typeOf = Ember.typeOf;
  exports.default = Component.extend({

    classNames: ['full-article'],

    content: computed('article.content', function () {

      var article = get(this, 'article');
      var content = get(article, 'content');

      return String.htmlSafe(content);
    }),

    didRender: function didRender() {
      var _self = this;
      this.$('img', 'video').each(function () {
        $(this).addClass('img-fluid');
      });

      this.$('iframe').each(function () {
        var origWidth = typeOf($(this).attr('width')) !== 'undefined' ? $(this).attr('width') : $(this).width();
        var origHeight = typeOf($(this).attr('height')) !== 'undefined' ? $(this).attr('height') : $(this).height();
        var currentWidth = _self.$('.article-content').width();

        if (origWidth > currentWidth) {
          var factor = origWidth / currentWidth;
          var scaledWidth = currentWidth;
          var scaledHeight = Math.round(origHeight / factor);

          $(this).attr('width', scaledWidth);
          $(this).attr('height', scaledHeight);
        }

        $(this).attr('sandbox', '');
        $(this).attr('sandbox', 'allow-same-origin allow-scripts allow-presentation');
        // if (articleSettings && get(articleSettings, 'allowEmbedded') === true) {
        //   $(this).attr('sandbox', 'allow-same-origin allow-scripts');
        // } else {
        //   const hint = 'Embedded content disabled: <a href="/settings">Enable in settings</a>';
        //   $(this).after('<div class="text-muted">' + hint + '</div>');
        // }
      });
    }
  });
});
define("ember-larafum/components/full-article/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "mdRBHsCU", "block": "{\"symbols\":[],\"statements\":[[4,\"link-to\",[[19,0,[\"onCloseRoute\"]]],null,{\"statements\":[[0,\"  \"],[6,\"i\"],[9,\"class\",\"fa fa-close\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]},null],[0,\"\\n\"],[6,\"h3\"],[9,\"class\",\"article-header\"],[7],[0,\"\\n  \"],[1,[20,[\"article\",\"title\"]],false],[0,\"\\n\"],[8],[0,\"\\n\\n\"],[6,\"div\"],[9,\"class\",\"article-content\"],[7],[0,\"\\n  \"],[1,[18,\"content\"],false],[0,\"\\n\"],[8],[0,\"\\n\\n\"],[6,\"div\"],[9,\"class\",\"ui divider\"],[7],[8],[0,\"\\n\"],[6,\"a\"],[9,\"class\",\"btn btn-outline-secondary\"],[10,\"href\",[26,[[20,[\"article\",\"url\"]]]]],[9,\"target\",\"_blank\"],[9,\"data-content\",\"Open article in new tab\"],[3,\"action\",[[19,0,[]],[25,\"route-action\",[\"originalArticle\"],null]],[[\"preventDefault\"],[false]]],[7],[0,\"\\n  \"],[6,\"i\"],[9,\"class\",\"external icon\"],[7],[8],[0,\"\\n  Open Original Article\\n\"],[8],[0,\"\\n\\n\"],[6,\"div\"],[9,\"class\",\"ui divider\"],[7],[8],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/components/full-article/template.hbs" } });
});
define('ember-larafum/components/infinity-loader', ['exports', 'ember-infinity/components/infinity-loader'], function (exports, _infinityLoader) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _infinityLoader.default;
});
define('ember-larafum/components/welcome-page', ['exports', 'ember-welcome-page/components/welcome-page'], function (exports, _welcomePage) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _welcomePage.default;
    }
  });
});
define('ember-larafum/feeds/feed/articles/article/route', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Route = Ember.Route,
      inject = Ember.inject;
  exports.default = Route.extend({

    gui: inject.service(),

    model: function model(params) {
      return this.get('store').findRecord('article', params.article_id);
    },
    afterModel: function afterModel(model) {
      if (!model.get('read')) {
        model.set('read', true);
        model.save();
      }
    },
    renderTemplate: function renderTemplate() {
      this.render('feeds/feed/articles/article', {
        into: 'application',
        outlet: 'column-two'
      });
    },


    actions: {
      originalArticle: function originalArticle() {}
    }
  });
});
define("ember-larafum/feeds/feed/articles/article/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "CkAQaRHu", "block": "{\"symbols\":[],\"statements\":[[1,[25,\"full-article\",null,[[\"article\",\"onCloseRoute\"],[[19,0,[\"model\"]],\"feeds.feed.articles\"]]],false],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/feeds/feed/articles/article/template.hbs" } });
});
define('ember-larafum/feeds/feed/articles/controller', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Controller = Ember.Controller;
  exports.default = Controller.extend({
    queryParams: ['sort', 'filterUnread'],
    filterUnread: false,
    sort: '-updated-date,-id'
  });
});
define('ember-larafum/feeds/feed/articles/index/route', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.Route.extend({
    renderTemplate: function renderTemplate() {
      this.render('feeds/feed/articles/index', {
        into: 'application',
        outlet: 'column-two'
      });
    },
    setupController: function setupController(controller, model) {
      this._super(controller, model);

      controller.set('feed', this.modelFor('feeds.feed'));
    },


    actions: {
      cleanup: function cleanup() {}
    }

  });
});
define("ember-larafum/feeds/feed/articles/index/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "iL5QGu6Z", "block": "{\"symbols\":[],\"statements\":[[6,\"div\"],[9,\"class\",\"article-placeholder\"],[7],[0,\"\\n\"],[4,\"if\",[[19,0,[\"feed\",\"logo\"]]],null,{\"statements\":[[0,\"    \"],[6,\"img\"],[10,\"src\",[26,[[20,[\"feed\",\"logo\"]]]]],[9,\"alt\",\"\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]},{\"statements\":[[0,\"    \"],[6,\"img\"],[9,\"src\",\"/ember-frontend/img/feed-icon.png\"],[9,\"class\",\"img-fluid\"],[9,\"alt\",\"\"],[7],[8],[0,\"\\n\"]],\"parameters\":[]}],[0,\"  \"],[6,\"span\"],[9,\"class\",\"text-muted\"],[7],[0,\"Select an article on the left to start reading.\"],[8],[0,\"\\n  \"],[6,\"div\"],[9,\"class\",\"btn-group\"],[9,\"role\",\"group\"],[7],[0,\"\\n    \"],[6,\"button\"],[9,\"type\",\"button\"],[9,\"class\",\"btn btn-secondary\"],[3,\"action\",[[19,0,[]],[25,\"route-action\",[\"cleanup\"],null]]],[7],[0,\"Cleanup\"],[8],[0,\"\\n    \"],[6,\"button\"],[9,\"type\",\"button\"],[9,\"class\",\"btn btn-secondary\"],[7],[0,\"Settings\"],[8],[0,\"\\n  \"],[8],[0,\"\\n\"],[8],[0,\"\\n\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/feeds/feed/articles/index/template.hbs" } });
});
define('ember-larafum/feeds/feed/articles/route', ['exports', 'ember-infinity/mixins/route'], function (exports, _route) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Route = Ember.Route,
      RSVP = Ember.RSVP,
      set = Ember.set,
      $ = Ember.$;
  exports.default = Route.extend(_route.default, {

    perPageParam: 'page[size]',

    pageParam: 'page[number]',

    totalPagesParam: 'meta.page.last-page',

    queryParams: {
      sort: {
        refreshModel: true
      },
      filterUnread: {
        refreshModel: true
      }
    },

    beforeModel: function beforeModel() {
      $('#article-list-items').animate({ scrollTop: 0, duration: 400 });
    },
    renderTemplate: function renderTemplate() {
      this.render('feeds.feed.articles', {
        into: 'application',
        outlet: 'column-one'
      });
    },
    model: function model(params) {
      var feed = this.modelFor('feeds.feed');

      var filter = {
        feed: feed.id
      };

      if (params.filterUnread === true) {
        filter['read'] = false;
      }

      delete params.filterUnread;

      params['filter'] = filter;
      params['fields'] = { articles: 'title,description,author,keep,read,url,updated-date,categories' };
      params['page'] = { size: 10 };

      return this.infinityModel('article', params);

      // return RSVP.hash({
      //   articles: this.get('store').query('article', params),
      //   feed: feed
      // });
    },
    setupController: function setupController(controller, model) {
      this._super(controller, model);

      controller.set('feed', this.modelFor('feeds.feed'));
    },


    actions: {
      read: function read(article) {
        article.toggleProperty('read');
        article.save();
      },
      keep: function keep(article) {
        article.toggleProperty('keep');
        article.save();
      },
      readAll: function readAll() {}
    }
  });
});
define("ember-larafum/feeds/feed/articles/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "0WNjo0dS", "block": "{\"symbols\":[\"article\"],\"statements\":[[6,\"div\"],[9,\"class\",\"articles\"],[7],[0,\"\\n  \"],[6,\"div\"],[9,\"class\",\"feed-header\"],[9,\"id\",\"articles-header\"],[7],[0,\"\\n    \"],[6,\"h4\"],[7],[0,\"\\n      \"],[6,\"a\"],[10,\"href\",[26,[[20,[\"feed\",\"siteUrl\"]]]]],[9,\"target\",\"_blank\"],[7],[1,[20,[\"feed\",\"name\"]],false],[8],[0,\"\\n    \"],[8],[0,\"\\n    \"],[6,\"p\"],[9,\"class\",\"card-text\"],[7],[1,[20,[\"feed\",\"description\"]],false],[8],[0,\"\\n\\n    \"],[6,\"label\"],[9,\"for\",\"\"],[7],[0,\"\\n      \"],[1,[25,\"input\",null,[[\"type\",\"checked\"],[\"checkbox\",[19,0,[\"filterUnread\"]]]]],false],[0,\"\\n      Show only unread\\n    \"],[8],[0,\"\\n\\n  \"],[8],[0,\"\\n\\n  \"],[6,\"div\"],[9,\"id\",\"article-list-items\"],[7],[0,\"\\n\"],[4,\"each\",[[19,0,[\"model\"]]],null,{\"statements\":[[0,\"      \"],[1,[25,\"article-list-item\",null,[[\"article\",\"feed\",\"classNames\"],[[19,1,[]],[19,0,[\"model\",\"feed\"]],\"article-list-item\"]]],false],[0,\"\\n\"]],\"parameters\":[1]},null],[0,\"    \"],[1,[25,\"infinity-loader\",null,[[\"infinityModel\",\"loadingText\",\"scrollable\"],[[19,0,[\"model\"]],\"Loading...\",\"#article-list-items\"]]],false],[0,\"\\n  \"],[8],[0,\"\\n\"],[8],[0,\"\\n\\n\\n\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/feeds/feed/articles/template.hbs" } });
});
define('ember-larafum/feeds/feed/route', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Route = Ember.Route;
  exports.default = Route.extend({
    model: function model(params) {
      return this.store.findRecord('feed', params.feed_id);
    }
  });
});
define('ember-larafum/feeds/feed/settings/route', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.Route.extend({});
});
define("ember-larafum/feeds/feed/settings/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "7wRXaos/", "block": "{\"symbols\":[],\"statements\":[[1,[18,\"outlet\"],false]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/feeds/feed/settings/template.hbs" } });
});
define("ember-larafum/feeds/feed/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "ctS/gbOn", "block": "{\"symbols\":[],\"statements\":[],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/feeds/feed/template.hbs" } });
});
define('ember-larafum/feeds/index/route', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.Route.extend({
    renderTemplate: function renderTemplate() {
      this.render('feeds/index', {
        into: 'application',
        outlet: 'column-one'
      });
    }
  });
});
define("ember-larafum/feeds/index/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "QLmrSxX9", "block": "{\"symbols\":[],\"statements\":[[6,\"div\"],[9,\"class\",\"align-self-center justify-self-center\"],[7],[0,\"\\n  \"],[6,\"span\"],[9,\"class\",\"text-muted\"],[7],[0,\"Select a feed on the left to get started\"],[8],[0,\"\\n\"],[8],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/feeds/index/template.hbs" } });
});
define('ember-larafum/feeds/route', ['exports', 'ember-simple-auth/mixins/authenticated-route-mixin'], function (exports, _authenticatedRouteMixin) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Route = Ember.Route,
      RSVP = Ember.RSVP;
  exports.default = Route.extend(_authenticatedRouteMixin.default, {
    model: function model() {

      return RSVP.hash({
        feeds: this.get('store').query('feed', {
          sort: 'order'
        }),
        folders: this.get('store').query('folder', {
          sort: 'order',
          include: 'feeds'
        })
      });
    },
    renderTemplate: function renderTemplate() {
      this.render('feeds', {
        into: 'application',
        outlet: 'side-bar'
      });
    },


    actions: {
      openFolder: function openFolder(folder) {
        folder.set('open', true);
        folder.save();
      },
      closeFolder: function closeFolder(folder) {
        folder.set('open', false);
        folder.save();
      }
    }

  });
});
define("ember-larafum/feeds/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "GiDs0UqB", "block": "{\"symbols\":[\"feed\",\"folder\"],\"statements\":[[0,\"Folders\\n\"],[6,\"div\"],[9,\"class\",\"list-group folder-list\"],[7],[0,\"\\n\"],[4,\"each\",[[19,0,[\"model\",\"folders\"]]],null,{\"statements\":[[0,\"    \"],[1,[25,\"folder-item\",null,[[\"folder\",\"classNames\"],[[19,2,[]],\"folder-item list-group-item\"]]],false],[0,\"\\n\"]],\"parameters\":[2]},null],[8],[0,\"\\n\"],[6,\"div\"],[9,\"class\",\"folder-item\"],[7],[0,\"\\n  \"],[6,\"div\"],[9,\"class\",\"folder\"],[7],[0,\"\\n    \"],[6,\"span\"],[9,\"class\",\"border border-left-0 border-right-0 border-top-0\"],[7],[0,\"Feeds\"],[8],[0,\"\\n  \"],[8],[0,\"\\n  \"],[6,\"div\"],[9,\"class\",\"list-group feed-list\"],[7],[0,\"\\n\"],[4,\"each\",[[19,0,[\"model\",\"feeds\"]]],null,{\"statements\":[[0,\"      \"],[1,[25,\"feed-item\",null,[[\"feed\",\"classNames\"],[[19,1,[]],\"list-group-item feed-item\"]]],false],[0,\"\\n\"]],\"parameters\":[1]},null],[0,\"  \"],[8],[0,\"\\n\"],[8],[0,\"\\n\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/feeds/template.hbs" } });
});
define('ember-larafum/helpers/app-version', ['exports', 'ember-larafum/config/environment', 'ember-cli-app-version/utils/regexp'], function (exports, _environment, _regexp) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.appVersion = appVersion;
  var version = _environment.default.APP.version;
  function appVersion(_) {
    var hash = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    if (hash.hideSha) {
      return version.match(_regexp.versionRegExp)[0];
    }

    if (hash.hideVersion) {
      return version.match(_regexp.shaRegExp)[0];
    }

    return version;
  }

  exports.default = Ember.Helper.helper(appVersion);
});
define('ember-larafum/helpers/bs-contains', ['exports', 'ember-bootstrap/helpers/bs-contains'], function (exports, _bsContains) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsContains.default;
    }
  });
  Object.defineProperty(exports, 'bsContains', {
    enumerable: true,
    get: function () {
      return _bsContains.bsContains;
    }
  });
});
define('ember-larafum/helpers/bs-eq', ['exports', 'ember-bootstrap/helpers/bs-eq'], function (exports, _bsEq) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _bsEq.default;
    }
  });
  Object.defineProperty(exports, 'eq', {
    enumerable: true,
    get: function () {
      return _bsEq.eq;
    }
  });
});
define('ember-larafum/helpers/is-after', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/is-after'], function (exports, _environment, _isAfter) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _isAfter.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/is-before', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/is-before'], function (exports, _environment, _isBefore) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _isBefore.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/is-between', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/is-between'], function (exports, _environment, _isBetween) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _isBetween.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/is-same-or-after', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/is-same-or-after'], function (exports, _environment, _isSameOrAfter) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _isSameOrAfter.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/is-same-or-before', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/is-same-or-before'], function (exports, _environment, _isSameOrBefore) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _isSameOrBefore.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/is-same', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/is-same'], function (exports, _environment, _isSame) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _isSame.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-add', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-add'], function (exports, _environment, _momentAdd) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentAdd.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-calendar', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-calendar'], function (exports, _environment, _momentCalendar) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentCalendar.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-diff', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-diff'], function (exports, _environment, _momentDiff) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentDiff.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-duration', ['exports', 'ember-moment/helpers/moment-duration'], function (exports, _momentDuration) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _momentDuration.default;
    }
  });
});
define('ember-larafum/helpers/moment-format', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-format'], function (exports, _environment, _momentFormat) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentFormat.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-from-now', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-from-now'], function (exports, _environment, _momentFromNow) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentFromNow.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-from', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-from'], function (exports, _environment, _momentFrom) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentFrom.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-subtract', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-subtract'], function (exports, _environment, _momentSubtract) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentSubtract.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-to-date', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-to-date'], function (exports, _environment, _momentToDate) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentToDate.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-to-now', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-to-now'], function (exports, _environment, _momentToNow) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentToNow.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-to', ['exports', 'ember-larafum/config/environment', 'ember-moment/helpers/moment-to'], function (exports, _environment, _momentTo) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _momentTo.default.extend({
    globalAllowEmpty: !!Ember.get(_environment.default, 'moment.allowEmpty')
  });
});
define('ember-larafum/helpers/moment-unix', ['exports', 'ember-moment/helpers/unix'], function (exports, _unix) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _unix.default;
    }
  });
  Object.defineProperty(exports, 'unix', {
    enumerable: true,
    get: function () {
      return _unix.unix;
    }
  });
});
define('ember-larafum/helpers/moment', ['exports', 'ember-moment/helpers/moment'], function (exports, _moment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _moment.default;
    }
  });
});
define('ember-larafum/helpers/now', ['exports', 'ember-moment/helpers/now'], function (exports, _now) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _now.default;
    }
  });
});
define('ember-larafum/helpers/pluralize', ['exports', 'ember-inflector/lib/helpers/pluralize'], function (exports, _pluralize) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _pluralize.default;
});
define('ember-larafum/helpers/route-action', ['exports', 'ember-route-action-helper/helpers/route-action'], function (exports, _routeAction) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _routeAction.default;
    }
  });
});
define('ember-larafum/helpers/singularize', ['exports', 'ember-inflector/lib/helpers/singularize'], function (exports, _singularize) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _singularize.default;
});
define('ember-larafum/helpers/unix', ['exports', 'ember-moment/helpers/unix'], function (exports, _unix) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _unix.default;
    }
  });
  Object.defineProperty(exports, 'unix', {
    enumerable: true,
    get: function () {
      return _unix.unix;
    }
  });
});
define('ember-larafum/index/route', ['exports', 'ember-simple-auth/mixins/authenticated-route-mixin'], function (exports, _authenticatedRouteMixin) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Route = Ember.Route;
  exports.default = Route.extend(_authenticatedRouteMixin.default, {
    beforeModel: function beforeModel() {
      return this.transitionTo('feeds');
    }
  });
});
define('ember-larafum/initializers/app-version', ['exports', 'ember-cli-app-version/initializer-factory', 'ember-larafum/config/environment'], function (exports, _initializerFactory, _environment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var _config$APP = _environment.default.APP,
      name = _config$APP.name,
      version = _config$APP.version;
  exports.default = {
    name: 'App Version',
    initialize: (0, _initializerFactory.default)(name, version)
  };
});
define('ember-larafum/initializers/container-debug-adapter', ['exports', 'ember-resolver/resolvers/classic/container-debug-adapter'], function (exports, _containerDebugAdapter) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'container-debug-adapter',

    initialize: function initialize() {
      var app = arguments[1] || arguments[0];

      app.register('container-debug-adapter:main', _containerDebugAdapter.default);
      app.inject('container-debug-adapter:main', 'namespace', 'application:main');
    }
  };
});
define('ember-larafum/initializers/current-user', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.initialize = initialize;
  function initialize(application) {
    application.inject('route', 'currentUser', 'service:current-user');
    application.inject('controller', 'currentUser', 'service:current-user');
    application.inject('component', 'currentUser', 'service:current-user');
  }

  exports.default = {
    initialize: initialize
  };
});
define('ember-larafum/initializers/data-adapter', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'data-adapter',
    before: 'store',
    initialize: function initialize() {}
  };
});
define('ember-larafum/initializers/ember-cli-fastclick', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var schedule = Ember.run.schedule;


  var EmberCliFastclickInitializer = {
    name: 'fastclick',

    initialize: function initialize() {
      schedule('afterRender', function () {
        FastClick.attach(document.body);
      });
    }
  };

  exports.default = EmberCliFastclickInitializer;
});
define('ember-larafum/initializers/ember-data-change-tracker', ['exports', 'ember-data-change-tracker'], function (exports, _emberDataChangeTracker) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'ember-data-change-tracker',
    after: 'ember-data',
    initialize: _emberDataChangeTracker.initializer
  };
});
define('ember-larafum/initializers/ember-data', ['exports', 'ember-data/setup-container', 'ember-data'], function (exports, _setupContainer) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'ember-data',
    initialize: _setupContainer.default
  };
});
define('ember-larafum/initializers/ember-simple-auth', ['exports', 'ember-larafum/config/environment', 'ember-simple-auth/configuration', 'ember-simple-auth/initializers/setup-session', 'ember-simple-auth/initializers/setup-session-service'], function (exports, _environment, _configuration, _setupSession, _setupSessionService) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'ember-simple-auth',

    initialize: function initialize(registry) {
      var config = _environment.default['ember-simple-auth'] || {};
      config.baseURL = _environment.default.rootURL || _environment.default.baseURL;
      _configuration.default.load(config);

      (0, _setupSession.default)(registry);
      (0, _setupSessionService.default)(registry);
    }
  };
});
define('ember-larafum/initializers/export-application-global', ['exports', 'ember-larafum/config/environment'], function (exports, _environment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.initialize = initialize;
  function initialize() {
    var application = arguments[1] || arguments[0];
    if (_environment.default.exportApplicationGlobal !== false) {
      var theGlobal;
      if (typeof window !== 'undefined') {
        theGlobal = window;
      } else if (typeof global !== 'undefined') {
        theGlobal = global;
      } else if (typeof self !== 'undefined') {
        theGlobal = self;
      } else {
        // no reasonable global, just bail
        return;
      }

      var value = _environment.default.exportApplicationGlobal;
      var globalName;

      if (typeof value === 'string') {
        globalName = value;
      } else {
        globalName = Ember.String.classify(_environment.default.modulePrefix);
      }

      if (!theGlobal[globalName]) {
        theGlobal[globalName] = application;

        application.reopen({
          willDestroy: function willDestroy() {
            this._super.apply(this, arguments);
            delete theGlobal[globalName];
          }
        });
      }
    }
  }

  exports.default = {
    name: 'export-application-global',

    initialize: initialize
  };
});
define('ember-larafum/initializers/injectStore', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'injectStore',
    before: 'store',
    initialize: function initialize() {}
  };
});
define('ember-larafum/initializers/load-bootstrap-config', ['exports', 'ember-larafum/config/environment', 'ember-bootstrap/config'], function (exports, _environment, _config) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.initialize = initialize;
  function initialize() /* container, application */{
    _config.default.load(_environment.default['ember-bootstrap'] || {});
  }

  exports.default = {
    name: 'load-bootstrap-config',
    initialize: initialize
  };
});
define('ember-larafum/initializers/simple-auth-token', ['exports', 'ember-simple-auth-token/authenticators/token', 'ember-simple-auth-token/authenticators/jwt', 'ember-simple-auth-token/authorizers/token', 'ember-simple-auth-token/configuration', 'ember-larafum/config/environment'], function (exports, _token, _jwt, _token2, _configuration, _environment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'ember-simple-auth-token',
    before: 'ember-simple-auth',
    initialize: function initialize(container) {
      _configuration.default.load(container, _environment.default['ember-simple-auth-token'] || {});
      container.register('authorizer:token', _token2.default);
      container.register('authenticator:token', _token.default);
      container.register('authenticator:jwt', _jwt.default);
    }
  };
});
define('ember-larafum/initializers/store', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'store',
    after: 'ember-data',
    initialize: function initialize() {}
  };
});
define('ember-larafum/initializers/transforms', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'transforms',
    before: 'store',
    initialize: function initialize() {}
  };
});
define("ember-larafum/instance-initializers/ember-data", ["exports", "ember-data/instance-initializers/initialize-store-service"], function (exports, _initializeStoreService) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: "ember-data",
    initialize: _initializeStoreService.default
  };
});
define('ember-larafum/instance-initializers/ember-simple-auth', ['exports', 'ember-simple-auth/instance-initializers/setup-session-restoration'], function (exports, _setupSessionRestoration) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'ember-simple-auth',

    initialize: function initialize(instance) {
      (0, _setupSessionRestoration.default)(instance);
    }
  };
});
define('ember-larafum/login/controller', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Controller = Ember.Controller,
      service = Ember.inject.service;
  exports.default = Controller.extend({
    session: service('session'),

    actions: {
      authenticate: function authenticate() {

        var credentials = this.getProperties('identification', 'password'),
            authenticator = 'authenticator:jwt';

        this.get('session').authenticate(authenticator, credentials);
      }
    }
  });
});
define('ember-larafum/login/route', ['exports', 'ember-simple-auth/mixins/unauthenticated-route-mixin'], function (exports, _unauthenticatedRouteMixin) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Route = Ember.Route,
      service = Ember.inject.service;
  exports.default = Route.extend(_unauthenticatedRouteMixin.default, {
    renderTemplate: function renderTemplate() {
      this.render('login', {
        into: 'application',
        outlet: 'main'
      });
    }
  });
});
define("ember-larafum/login/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "NCKg3Rry", "block": "{\"symbols\":[],\"statements\":[[6,\"screen\"],[7],[0,\"\\n  \"],[6,\"page\"],[7],[0,\"\\n    \"],[6,\"vbox\"],[7],[0,\"\\n      \"],[6,\"centered\"],[9,\"class\",\"well\"],[7],[0,\"\\n        \"],[6,\"form\"],[9,\"class\",\"login-form\"],[3,\"action\",[[19,0,[]],\"authenticate\"],[[\"on\"],[\"submit\"]]],[7],[0,\"\\n          \"],[6,\"h1\"],[7],[0,\"Larafum - Login\"],[8],[0,\"\\n          \"],[6,\"div\"],[9,\"class\",\"required form-group\"],[7],[0,\"\\n            \"],[6,\"label\"],[9,\"for\",\"identification\"],[7],[0,\"Username\"],[8],[0,\"\\n            \"],[1,[25,\"input\",null,[[\"id\",\"placeholder\",\"value\",\"classNames\"],[\"identification\",\"Enter Login\",[19,0,[\"identification\"]],\"form-control\"]]],false],[0,\"\\n          \"],[8],[0,\"\\n          \"],[6,\"div\"],[9,\"class\",\"required form-group\"],[7],[0,\"\\n            \"],[6,\"label\"],[9,\"for\",\"password\"],[7],[0,\"Password\"],[8],[0,\"\\n            \"],[1,[25,\"input\",null,[[\"id\",\"placeholder\",\"type\",\"value\",\"classNames\"],[\"password\",\"Enter Password\",\"password\",[19,0,[\"password\"]],\"form-control\"]]],false],[0,\"\\n          \"],[8],[0,\"\\n          \"],[6,\"button\"],[9,\"class\",\"btn btn-primary\"],[9,\"type\",\"submit\"],[7],[0,\"Login\"],[8],[0,\"\\n        \"],[8],[0,\"\\n      \"],[8],[0,\"\\n    \"],[8],[0,\"\\n  \"],[8],[0,\"\\n\"],[8],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/login/template.hbs" } });
});
define('ember-larafum/mixins/adapter-fetch', ['exports', 'ember-fetch/mixins/adapter-fetch'], function (exports, _adapterFetch) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _adapterFetch.default;
    }
  });
});
define('ember-larafum/mixins/change-serializer', ['exports', 'ember-data-change-tracker/mixins/keep-only-changed'], function (exports, _keepOnlyChanged) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _keepOnlyChanged.default;
});
define('ember-larafum/models/article', ['exports', 'ember-data'], function (exports, _emberData) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Model = _emberData.default.Model,
      attr = _emberData.default.attr,
      belongsTo = _emberData.default.belongsTo;
  exports.default = Model.extend({
    title: attr('string'),
    content: attr('string'),
    description: attr('string'),
    categories: attr(),
    author: attr('string'),
    language: attr('string'),
    publishDate: attr('date'),
    updatedDate: attr('date'),
    feed: belongsTo('feed'),
    url: attr('string'),
    read: attr('boolean'),
    keep: attr('boolean')
  });
});
define('ember-larafum/models/feed', ['exports', 'ember-data'], function (exports, _emberData) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Model = _emberData.default.Model,
      attr = _emberData.default.attr,
      belongsTo = _emberData.default.belongsTo,
      hasMany = _emberData.default.hasMany;
  exports.default = _emberData.default.Model.extend({
    name: attr('string'),
    url: attr('string'),
    feedUrl: attr('string'),
    siteUrl: attr('string'),
    guid: attr('string'),
    description: attr('string'),
    icon: attr('string'),
    logo: attr('string'),
    language: attr('string'),
    etag: attr('string'),
    authUser: attr('string'),
    authPassword: attr('string'),
    order: attr('number'),
    user: belongsTo('user'),
    folder: belongsTo('folder'),
    articles: hasMany('article'),
    meta: attr()
  });
});
define('ember-larafum/models/folder', ['exports', 'ember-data'], function (exports, _emberData) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Model = _emberData.default.Model,
      attr = _emberData.default.attr,
      hasMany = _emberData.default.hasMany,
      belongsTo = _emberData.default.belongsTo;
  exports.default = Model.extend({
    name: attr('string'),
    open: attr('boolean'),
    order: attr('number'),
    feeds: hasMany('feed'),
    user: belongsTo('user')
  });
});
define('ember-larafum/models/setting', ['exports', 'ember-data'], function (exports, _emberData) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _emberData.default.Model.extend({});
});
define('ember-larafum/models/user', ['exports', 'ember-data'], function (exports, _emberData) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Model = _emberData.default.Model,
      attr = _emberData.default.attr;
  exports.default = Model.extend({
    username: attr('string'),
    fullname: attr('string'),
    email: attr('string')
  });
});
define('ember-larafum/resolver', ['exports', 'ember-resolver'], function (exports, _emberResolver) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _emberResolver.default;
});
define('ember-larafum/router', ['exports', 'ember-larafum/config/environment'], function (exports, _environment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });


  var Router = Ember.Router.extend({
    location: _environment.default.locationType,
    rootURL: _environment.default.rootURL
  });

  Router.map(function () {
    this.route('login');
    this.route('settings');

    this.route('feeds', { path: '/feeds' }, function () {
      this.route('feed', { path: '/:feed_id' }, function () {
        this.route('articles', function () {
          this.route('article', { path: '/:article_id' });
        });
        this.route('settings');
      });
    });
  });

  exports.default = Router;
});
define('ember-larafum/routes/application', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Route = Ember.Route;
  exports.default = Route.extend();
});
define('ember-larafum/serializers/feed', ['exports', 'ember-data'], function (exports, _emberData) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _emberData.default.JSONAPISerializer.extend({
    normalize: function normalize(modelClass, resourceHash) {
      if (resourceHash.meta) {
        resourceHash.attributes.meta = resourceHash.meta;

        delete resourceHash.meta;
      }

      return this._super.apply(this, arguments);
    }
  });
});
define('ember-larafum/services/ajax', ['exports', 'ember-ajax/services/ajax'], function (exports, _ajax) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  Object.defineProperty(exports, 'default', {
    enumerable: true,
    get: function () {
      return _ajax.default;
    }
  });
});
define('ember-larafum/services/cookies', ['exports', 'ember-cookies/services/cookies'], function (exports, _cookies) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _cookies.default;
});
define('ember-larafum/services/current-user', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Service = Ember.Service,
      service = Ember.inject.service,
      isEmpty = Ember.isEmpty,
      RSVP = Ember.RSVP,
      getOwner = Ember.getOwner;
  exports.default = Service.extend({
    session: service('session'),
    store: service(),

    init: function init() {
      this._super.apply(this, arguments);

      this.user = null;
    },
    load: function load() {
      var _this = this;

      var token = this.get('session.data.authenticated.token');

      if (!isEmpty(token)) {
        var authenticator = getOwner(this).lookup('authenticator:jwt');
        var session = this.get('session.session.content.authenticated');
        var tokenData = void 0;

        tokenData = authenticator.getTokenData(session.token);
        var user_id = tokenData.sub;
        return this.get('store').findRecord('user', user_id).then(function (user) {
          _this.set('user', user);
        });
      }
      return RSVP.resolve();
    }
  });
});
define('ember-larafum/services/gui', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  var Service = Ember.Service,
      $ = Ember.$,
      A = Ember.A,
      run = Ember.run;
  exports.default = Service.extend({

    active: new A(),
    /**
     *
     * @param layoutComponent
     */
    activate: function activate(layoutComponent) {
      Ember.debug('service: gui::activate(' + layoutComponent + ')');

      run.scheduleOnce('afterRender', this, function () {
        var component = '#' + layoutComponent;
        var isActivated = $(component).hasClass('activated');
        Ember.debug('service: gui::activate() ' + component + '.isActivated=' + isActivated);

        if (isActivated === false) {
          Ember.debug('service: gui::activate() activate ' + component);
          $(component).addClass('activated');
        }

        isActivated = $(component).hasClass('activated');
        Ember.debug('service: gui::activate() ' + component + '.isActivated=' + isActivated);
      });
    },

    /**
     *
     * @param layoutComponent
     */
    deactivate: function deactivate(layoutComponent) {
      Ember.debug('service: activate-deactivate::deactivate(' + layoutComponent + ') ');

      var component = '#' + layoutComponent;
      $(component).removeClass('activated');
    }
  });
});
define('ember-larafum/services/moment', ['exports', 'ember-larafum/config/environment', 'ember-moment/services/moment'], function (exports, _environment, _moment) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _moment.default.extend({
    defaultFormat: Ember.get(_environment.default, 'moment.outputFormat')
  });
});
define('ember-larafum/services/session', ['exports', 'ember-simple-auth/services/session'], function (exports, _session) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _session.default;
});
define('ember-larafum/session-stores/application', ['exports', 'ember-simple-auth/session-stores/adaptive'], function (exports, _adaptive) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _adaptive.default.extend();
});
define('ember-larafum/settings/route', ['exports'], function (exports) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.Route.extend({});
});
define("ember-larafum/settings/template", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "x+H9XaDu", "block": "{\"symbols\":[],\"statements\":[[6,\"h1\"],[7],[0,\"Settings\"],[8],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/settings/template.hbs" } });
});
define("ember-larafum/templates/application", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "H04feOxN", "block": "{\"symbols\":[],\"statements\":[[1,[18,\"outlet\"],false],[0,\"\\n\"]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/templates/application.hbs" } });
});
define("ember-larafum/templates/components/infinity-loader", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = Ember.HTMLBars.template({ "id": "c3JNbJyR", "block": "{\"symbols\":[\"&default\"],\"statements\":[[4,\"if\",[[22,1]],null,{\"statements\":[[0,\"  \"],[11,1],[0,\"\\n\"]],\"parameters\":[]},{\"statements\":[[4,\"if\",[[19,0,[\"infinityModel\",\"reachedInfinity\"]]],null,{\"statements\":[[0,\"    \"],[6,\"span\"],[7],[1,[18,\"loadedText\"],false],[8],[0,\"\\n\"]],\"parameters\":[]},{\"statements\":[[0,\"    \"],[6,\"span\"],[7],[1,[18,\"loadingText\"],false],[8],[0,\"\\n\"]],\"parameters\":[]}]],\"parameters\":[]}]],\"hasEval\":false}", "meta": { "moduleName": "ember-larafum/templates/components/infinity-loader.hbs" } });
});
define('ember-larafum/transforms/json', ['exports', 'ember-data-change-tracker/transforms/json'], function (exports, _json) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _json.default;
});
define('ember-larafum/transforms/object', ['exports', 'ember-data-change-tracker/transforms/object'], function (exports, _object) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = _object.default;
});
define("ember-larafum/transitions", ["exports"], function (exports) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  exports.default = function () {
    // Add your transitions here, like:
    //   this.transition(
    //     this.fromRoute('people.index'),
    //     this.toRoute('people.detail'),
    //     this.use('toLeft'),
    //     this.reverse('toRight')
    //   );
  };
});


define('ember-larafum/config/environment', ['ember'], function(Ember) {
  var prefix = 'ember-larafum';
try {
  var metaName = prefix + '/config/environment';
  var rawConfig = document.querySelector('meta[name="' + metaName + '"]').getAttribute('content');
  var config = JSON.parse(unescape(rawConfig));

  var exports = { 'default': config };

  Object.defineProperty(exports, '__esModule', { value: true });

  return exports;
}
catch(err) {
  throw new Error('Could not read config from meta tag with name "' + metaName + '".');
}

});

if (!runningTests) {
  require("ember-larafum/app")["default"].create({"name":"ember-larafum","version":"0.0.0+f8ae0624"});
}
//# sourceMappingURL=ember-larafum.map
