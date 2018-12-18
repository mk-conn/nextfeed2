import Ember from 'ember';
import GuiMixin from 'frontend/mixins/gui';
import { module, test } from 'qunit';

module('Unit | Mixin | gui');

// Replace this with your real tests.
test('it works', function(assert) {
  let GuiObject = Ember.Object.extend(GuiMixin);
  let subject = GuiObject.create();
  assert.ok(subject);
});
