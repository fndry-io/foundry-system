import component from "./formGenerator.vue";
import schema from "./utils/schema.js";
import validators from "./utils/validators.js";
import fieldComponents from "./utils/fieldsLoader";
import abstractField from "./fields/abstractField";
import render from './formComponent';
import view from './formRenderer';
import modal from './modal/formModal';


import {forEach, kebabCase, last} from 'lodash';

let modals = [];

let formModal;
let formView;

function updateModalPositions() {
    modals.forEach((modal, index) => {
        modals[index].index = index;
    });
}


const install = (Vue, options) => {
	Vue.component("form-generator", component);
    Vue.component('form-component', render);
    Vue.component('form-modal', modal);
    Vue.component('form-view', view);

    formModal = Vue.extend(modal);
    formView = Vue.extend(view);

    if (options && options.validators) {
		for (let key in options.validators) {
			if ({}.hasOwnProperty.call(options.validators, key)) {
				validators[key] = options.validators[key];
			}
		}
	}

    //Register form inputs components
    const files = require.context('./fields/core/', true, /\.vue$/i);
    files.keys().map(key => {
        let compName = _.last(key.split('/')).split('.')[0];
        Vue.component(kebabCase(compName), files(key).default);
    });

    Vue.formModal = function (url, title, center) {

        let form = new formView();
        form.url = url;
        form.$root = this;
        let formNode = form.$mount();

        let modal = new formModal();
        modal.index = 0;
        modals.unshift(modal);
        modal.title = title;
        modal.center = center;
        modal.$root = this;
        let modalNode = modal.$mount();

        document.getElementById('app').appendChild(modalNode.$el);
        modalNode.$el.getElementsByClassName('modal-body')[0].appendChild(formNode.$el);

        modal.$on('hidden', (res) => {
            modalNode.$el.remove();
            formNode.$el.remove();
            modal.$destroy();
            form.$destroy();
            updateModalPositions();
        });
        modal.$on('hide', (res) => {
            modals.splice(modal.index, 1);
            updateModalPositions();
        });
        modal.$on('shown', (res) => {
            updateModalPositions();
        });

        return new Promise(function (resolve, reject) {
            form.$on('success', (res) => {
                modal.remove();
                resolve(res);
            });
            form.$on('cancel', (res) => {
                modal.remove();
                reject(res);
            });

        });

    };
};

export default {
	component,
    render,
	schema,
	validators,
	abstractField,
	fieldComponents,
	install
};
