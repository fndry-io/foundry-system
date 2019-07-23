import { slugifyFormID } from "../utils/schema";
import { get as objGet, forEach, isNil, isArray, isString, isFunction } from "lodash";

// import FormView from '../components/FormView';

// let modals = [];

// const Modal = Vue.extend(FormModal);

// function updateModalPositions() {
//     modals.forEach((modal, index) => {
//         modals[index].index = index;
//     });
// }

// export const HasFormModalRequest = {
// 	components: {
//         FormModal,
//         FormRender
// 	},
// 	methods: {
//         displayFormRequest(request, params = {}, title, center) {
//
// 			let form = new Form({
// 				propsData: {
//                     url: route(getViewRequestUri(request), params)
// 				}
// 			});
// 			form.$mount();
//
// 			let modal = new Modal({
//                 index: 0,
//                 title: title,
//                 center: center,
// 			});
//             modal.$mount();
// 			modals.unshift(modal);
//
// 			this.$root.$el.appendChild(modal.$el);
//             modal.$el.getElementsByClassName('modal-body')[0].appendChild(form.$el);
//
// 			modal.$on('hidden', (res) => {
//                 modal.$el.remove();
//                 form.$el.remove();
// 				modal.$destroy();
// 				form.$destroy();
// 				updateModalPositions();
// 			});
// 			modal.$on('hide', (res) => {
// 				modals.splice(modal.index, 1);
// 				updateModalPositions();
// 			});
// 			modal.$on('shown', (res) => {
// 				updateModalPositions();
// 			});
//
// 			return new Promise(function (resolve, reject) {
// 				form.$on('success', (res) => {
// 					modal.remove();
// 					resolve(res);
// 				});
// 				form.$on('cancel', (res) => {
// 					modal.remove();
// 					reject(res);
// 				});
//
// 			});
//
// 		}
// 	}
// };

export default {
    props: {
        schema: {
            type: Object,
            required: true
        },
        model: {
            type: Object,
            required: false
        },
        errors: {
            type: Object,
            required: false
        }
    },
	methods: {
		// Get style classes of field
		fieldRowClasses(field) {
			const hasErrors = this.fieldErrors(field).length > 0;
			let baseClasses = {
				[objGet(this.options, "validationErrorClass", "validated")]: hasErrors,
				[objGet(this.options, "validationSuccessClass", "valid")]: !hasErrors,
				disabled: this.fieldDisabled(field),
				readonly: this.fieldReadonly(field),
				featured: this.fieldFeatured(field),
				required: this.fieldRequired(field)
			};

			if (isArray(field.class)) {
				forEach(field.class, c => (baseClasses[c] = true));
			} else if (isString(field.class)) {
				baseClasses[field.class] = true;
			}

			if (!isNil(field.type)) {
				baseClasses["field-" + field.type] = true;
			}

			return baseClasses;
		},

        fieldId(field) {
            const idPrefix = objGet(this.options, "fieldIdPrefix", "");
            return slugifyFormID(field, idPrefix);
        },
        fieldName(field) {
            return `${field.name}`
        },

        // Get type of field 'field-xxx'. It'll be the name of HTML element
        fieldType(field) {

            let type = "input";

            switch (field.type) {
                case 'autocomplete':
                    type = "auto-complete";
                    break;
                case "select":
                    type = "select";
                    break;
                case 'file':
                    type = "upload";
                    break;
                case 'submit':
                    type = "submit";
                    break;
                case 'reference':
                    type = 'reference';
                    break;
                case 'switch':
                case 'checkbox':
                    type = 'checkbox';
                    break;
                case 'checkboxes':
                    type = 'checkboxes';
                    break;
                case 'radio':
                    type = 'radios';
                    break;
                case 'tag':
                    type = 'tag';
                    break;
                case 'hidden':
                    type = 'hidden';
                    break;
                case 'tel':
                    type = 'telephone';
                    break;
                case 'date':
                case "datetime":
                    type = 'date';
                    break;
            }

            type = (type === 'input')? (field.multiline)? 'textarea': type : type;

            return "fndry-field-" + type;
        },

		fieldErrors(field) {
			let res = this.errors.filter(e => e.field === field);
			return res.map(item => item.error);
		},
        // fieldErrors(field) {
        // 	return this.errors.filter((e) => e.field === field).map((item) => item.error);
        // },

        // Get disabled attr of field
		fieldDisabled(field) {
			if (isFunction(field.readonly)) return field.readonly.call(this, this.model, field, this);

			if (isNil(field.readonly)) return false;

			return field.disabled;
		},
		// Get readonly prop of field
		fieldReadonly(field) {
			if (isFunction(field.readonly)) return field.readonly.call(this, this.model, field, this);

			if (isNil(field.readonly)) return false;

			return field.readonly;
		},
		// Get featured prop of field
		fieldFeatured(field) {
			if (isFunction(field.featured)) return field.featured.call(this, this.model, field, this);

			if (isNil(field.featured)) return false;

			return field.featured;
		},
		// Get required prop of field
		fieldRequired(field) {
			if (isFunction(field.required)) return field.required.call(this, this.model, field, this);

			if (isNil(field.required)) return false;

			return field.required;
		},
        // Get current hint.
        fieldHint(field) {
            if (isFunction(field.hint)) return field.hint.call(this, this.model, field, this);

            return field.hint;
        },

        // Should field type have a label?
        fieldTypeHasLabel(field) {
            if (isNil(field.label) || field.type === 'hidden') return false;

            let relevantType = "";
            if (field.type === "input") {
                relevantType = field.inputType;
            } else {
                relevantType = field.type;
            }

            switch (relevantType) {
                case "button":
                case "submit":
                case "reset":
                    return false;
                default:
                    return true;
            }
        },
	}
};
