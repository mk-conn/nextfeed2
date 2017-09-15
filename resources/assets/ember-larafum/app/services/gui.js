import Ember from "ember";

const {
  Service,
  $,
  A,
  run
} = Ember;

/**
 * Cares for all the open and close parts of the
 * application
 */
export default Service.extend({

  active: new A(),
  /**
   *
   * @param layoutComponent
   */
  activate(layoutComponent) {
    Ember.debug(`service: gui::activate(${layoutComponent})`);

    run.scheduleOnce('afterRender', this, () => {
      let component = `#${layoutComponent}`;
      let isActivated = $(component).hasClass('activated');
      Ember.debug(`service: gui::activate() ${component}.isActivated=${isActivated}`);

      if (isActivated === false) {
        Ember.debug(`service: gui::activate() activate ${component}`);
        $(component).addClass('activated');
      }

      isActivated = $(component).hasClass('activated');
      Ember.debug(`service: gui::activate() ${component}.isActivated=${isActivated}`);
    });
  },
  /**
   *
   * @param layoutComponent
   */
  deactivate(layoutComponent) {
    Ember.debug(`service: activate-deactivate::deactivate(${layoutComponent}) `);

    let component = `#${layoutComponent}`;
    $(component).removeClass('activated');
  }

});
