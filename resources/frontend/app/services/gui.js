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
  enable(layoutComponent) {
    Ember.debug(`service: gui::enable(${layoutComponent})`);

    run.scheduleOnce('afterRender', this, () => {
      let component = `${layoutComponent}`;
      let isEnabled = $(component).hasClass('enabled');
      Ember.debug(`service: gui::activate() ${component}.isActivated=${isEnabled}`);

      if (isEnabled === false) {
        Ember.debug(`\tenable: ${component}`);
        $(component).addClass('enabled');
      }

      isEnabled = $(component).hasClass('enabled');
      Ember.debug(`\t${component}.isEnabled=${isEnabled}`);
    });
  },
  /**
   *
   * @param layoutComponent
   */
  disable(layoutComponent) {
    Ember.debug(`service: gui::disable(${layoutComponent}) `);

    let component = `${layoutComponent}`;
    $(component).removeClass('enabled');
  }

});
