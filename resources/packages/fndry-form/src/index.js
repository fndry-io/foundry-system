import FndryFormSchema from './components/FormSchema';
import FndryFormType from './components/FormType';
import FndryFormGroup from './components/FormGroup';
import FndryFormButtons from './components/FormButtons';

import abstractWrapper from './types/abstractWrapper';
import abstractInput from './types/abstractInput';

import VeeValidate, { Validator } from 'vee-validate';

Validator.extend('nullable', {
    getMessage: field => 'The ' + field + ' value can\'t be empty.',
    validate: value => true

});

Validator.extend('exists', () => true);
Validator.extend('unique', () => true);

Validator.extend('in', {
    getMessage: field => 'The ' + field + ' value is not in the allowed values.',
    validate: (value, args) => {
        for (let i in args) {
            //todo figure out a better way than eval
            if (value == eval(args[i])) {
                return true;
            }
        }
        return false;
    }
});

Validator.extend('numeric', {
    getMessage: field => 'The ' + field + ' value must be a valid number.',
    validate: (value, args) => {
        let regex = new RegExp('^([\-0-9\.]+)$');
        return regex.test(value);
    }
});

Validator.extend('telephone', {
    getMessage: field => 'The ' + field + ' value must be a phone number.',
    validate: (value, args) => {
        let regex = new RegExp('^\\+[0-9]{1,15}$');
        return regex.test(value);
    }
});


Validator.extend('username', {
    getMessage: field => 'The ' + field + ' value must only contain alpha numeric characters, numbers or _.',
    validate: (value, args) => {
        let regex = new RegExp('^[A-Za-z0-9_]+$');
        return regex.test(value);
    }
});

Validator.extend('date', {
    getMessage: field => 'The ' + field + ' value must be a valid date.',
    validate: (value, args) => {
        //todo write this!!!
        return true;
    }
});
Validator.extend('date_format', {
    getMessage: field => 'The ' + field + ' value must be a valid date.',
    validate: (value, args) => {
        //todo write this!!!
        return true;
    }
});
Validator.extend('valid_date', {
    getMessage: field => 'The ' + field + ' value must be a valid date.',
    validate: (value, args) => {
        //todo write this!!!
        return true;
    }
});

export {
    FndryFormSchema,
    FndryFormGroup,
    FndryFormType,
    FndryFormButtons,
    abstractWrapper,
    abstractInput
};

/**
 * FndryForm
 * (c) 2019
 * @license MIT
 */

const Plugin = {};

/**
 * Plugin API
 */
Plugin.install = function (Vue, options) {
    Vue.component('fndry-form-schema', FndryFormSchema);
    Vue.component('fndry-form-type', FndryFormType);
    Vue.component('fndry-form-group', FndryFormGroup);

    Vue.use(VeeValidate, {
        // This is the default
        inject: true,
        // Important to name this something other than 'fields'
        fieldsBagName: 'veeFields',
        // This is not required but avoids possible naming conflicts
        errorBagName: 'veeErrors'
    });

};

//import './scss/style.scss';

/**
 * Auto install
 */
if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(Plugin)
}

export default Plugin
