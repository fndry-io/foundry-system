import FndryFormSchema from './components/FormSchema';
import FndryFormType from './components/FormType';
import FndryFormGroup from './components/FormGroup';
import FndryFormButtons from './components/FormButtons'

import VeeValidate, { Validator } from 'vee-validate';

Validator.extend('nullable', {
    getMessage: field => 'The ' + field + ' value is not truthy.',
    validate: value => !! value
});

export {
    FndryFormSchema,
    FndryFormGroup,
    FndryFormType,
    FndryFormButtons,
};

/**
 * FndryForm
 * (c) 2019
 * @license MIT
 */

const FndryForm = {};

/**
 * Plugin API
 */
FndryForm.install = function (Vue, options) {
    Vue.component('fndry-form-schema', FndryFormSchema);
    Vue.component('fndry-form-type', FndryFormType);

    Vue.use(VeeValidate, {
        // This is the default
        inject: true,
        // Important to name this something other than 'fields'
        fieldsBagName: 'veeFields',
        // This is not required but avoids possible naming conflicts
        errorBagName: 'veeErrors'
    });

};


/**
 * Auto install
 */
if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(FndryForm)
}

export default FndryForm
