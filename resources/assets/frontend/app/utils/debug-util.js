import Ember from "ember";

const {
  debug,
  inspect
} = Ember;

function logDebugMessage() {
  let msg = arguments[ 0 ];

  if (arguments.length > 1) {
    for (var i = 1; i < arguments.length; i++) {
      try {
        msg = msg.replace(/%s/, inspect(arguments[ i ]));
      } catch (e) {

      }
    }
  }

  debug(msg);
}


export default function debugUtil() {
  return instanceLogger;
}

const LOGGER = '_debugLoggerInstance';

export function instanceLogger() {
  let logger = this && this[ LOGGER ];

  if (!logger) {

    logger = logDebugMessage;
    Object.defineProperty(this, LOGGER, {value: logger});
  }

  return logger.apply(this, arguments);
}
