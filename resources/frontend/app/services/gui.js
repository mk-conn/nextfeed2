import Service from '@ember/service';
import $ from 'jquery';
import { run } from '@ember/runloop';
import { debug } from '@ember/debug';
import { A } from '@ember/array';

const ENABLED_CLASS = 'enabled';

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
    debug(`gui::enable(${layoutComponent})`);

    run.scheduleOnce('afterRender', this, () => {

      let toEnable = document.getElementById(layoutComponent);
      if (toEnable && toEnable.classList.contains(ENABLED_CLASS)) {
        return;
      }

      let enabled = document.getElementsByClassName(ENABLED_CLASS);
      if (enabled.length) {
        for (let i = 0; i < enabled.length; i++) {
          enabled.item(i).classList.remove(ENABLED_CLASS);
        }
      }
      toEnable.classList.add('enabled');

      debug(`gui: ${layoutComponent} enabled`);
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
