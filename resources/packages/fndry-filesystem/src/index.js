
/**
 * FndryFilesystem
 * (c) 2019
 * @license MIT
 */

const Plugin = {};

/**
 * Plugin API
 */
Plugin.install = function (Vue, options) {

};

/**
 * Auto install
 */
if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(Plugin)
}

export default Plugin
