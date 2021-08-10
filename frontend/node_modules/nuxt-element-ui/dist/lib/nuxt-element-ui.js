"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var lodash_kebabcase_1 = require("lodash.kebabcase");
var path_1 = require("path");
var meta = require("../package.json");
function nuxtElementUiModule(moduleOptions) {
    var options = Object.assign({}, this.options.elementUI || moduleOptions);
    options.components = (options.components || []).map(function (className) { return ({ className: className, fileName: lodash_kebabcase_1.default(className) }); });
    if (!options.locale)
        options.locale = 'en';
    this.addPlugin({ src: path_1.join(__dirname, './lib/templates/plugin.js'), options: options });
}
nuxtElementUiModule['meta'] = meta;
exports.default = nuxtElementUiModule;
//# sourceMappingURL=nuxt-element-ui.js.map