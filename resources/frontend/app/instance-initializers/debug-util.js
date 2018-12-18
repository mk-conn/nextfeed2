import debugUtil from "../utils/debug-util";

export function initialize(instance) {

  let registry = instance.register ? instance : instance.registry;
  let inject = registry.inject || registry.injection;

  registry.register('debug-util:main', debugUtil(), { instantiate: false });

  ['route', 'component', 'controller', 'service', 'adapter'].forEach(function(type) {
    inject.call(registry, type, 'debug', 'debug-util:main');
  });
}

export default {
  name: 'debug-util',
  initialize
};
