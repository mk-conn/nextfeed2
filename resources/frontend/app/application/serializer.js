// file: app/serializers/user.js
import DS from 'ember-data';
//import keepOnlyChanged from 'ember-data-change-tracker/mixins/keep-only-changed';
// currently a bug: https://github.com/danielspaniel/ember-data-change-tracker/issues/54

export default DS.JSONAPISerializer.extend();
