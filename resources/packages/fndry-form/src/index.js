import FormState from './components/FormState';
import FormSchema from './components/FormSchema';
import FormComponent from './components/FormComponent';
import FormGroup from './components/FormGroup';
import FormField from './components/FormField';
import FormRequest from './components/FormRequest';

import FormValidator from './directives/form-validator';

import extend from 'extend';
import { config } from './config';
import { fndryFormConfig } from './utils/providers';

function FndryFormBase (options) {
    const c = extend(true, {}, config, options);
    this.provide = () => ({
        [fndryFormConfig]: c
    });
    this.components = {
        FormState,
        FormField,
        FormSchema,
        FormComponent
    };
    this.directives = { FormValidator };
}

export {
    FormState,
    FormComponent,
    FormSchema,
    FormField,
    FormRequest,
    FormGroup
};

export default class FndryForm extends FndryFormBase {
    static install(Vue, options) {
        Vue.mixin(new this(options));
    }
    static get installed() {
        return !!this.install.done;
    }
    static set installed(val) {
        this.install.done = val;
    }
}

FndryFormBase.call(FndryForm);
// temp fix for vue 2.3.0
FndryForm.options = new FndryForm();

