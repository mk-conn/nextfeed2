import Service from '@ember/service';
import $ from 'jquery';
import { run } from '@ember/runloop';
import { debug } from '@ember/debug';
import { A } from '@ember/array';

/**
 * Cares for all the open and close parts of the
 * application
 */
export default Service.extend({

  init() {
    this._super(...arguments);
    this.active = A();
  },
  /**
   *
   * @param layoutComponent
   */
  enable(layoutComponent) {
    debug(`service: gui::enable(${layoutComponent})`);

    run.scheduleOnce('afterRender', this, () => {

      let enabled = document.getElementsByClassName('enabled');
      if (enabled.length) {
        for (let i = 0; i < enabled.length; i++) {
          enabled.item(i).classList.remove('enabled');
        }
      }

      let toEnable = document.getElementById(layoutComponent);
      toEnable.classList.add('enabled');

      // let component = `${layoutComponent}`;
      // let isEnabled = $(component).hasClass('enabled');
      // debug(`service: gui::activate() ${component}.isActivated=${isEnabled}`);
      //
      // if (isEnabled === false) {
      //   debug(`\tenable: ${component}`);
      //   $(component).addClass('enabled');
      // }
      //
      // isEnabled = $(component).hasClass('enabled');
      debug(`\t${layoutComponent} enabled`);
    });
  },
  /**
   *
   * @param layoutComponent
   */
  disable(layoutComponent) {
    debug(`service: gui::disable(${layoutComponent}) `);

    let component = `${layoutComponent}`;
    $(component).removeClass('enabled');
  }

});
