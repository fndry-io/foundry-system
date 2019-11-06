import { slugifyFormID } from "../utils/schema";
import { get as objGet, forEach, isNil, isArray, isString, isFunction, map, isEmpty, find, merge } from "lodash";

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

export const uploadInput = {
    data(){
        return {
            files: (this.schema.files) ? this.schema.files : [],
            upload: [],
            uploading: [],
            fileModel: null,
            uploadable: !this.schema.disabled,
            placeholder: this.schema.placeholder
        }
    },
    methods: {
        showInput() {
            if (this.schema.multiple) {
                return true;
            }
            if (this.files.length === 0) {
                return true;
            }
            // if (!this.schema.deleteUrl) {
            //     return true;
            // }
            return false;
        },
        formatNames() {
            if (this.files.length) {
                return `${this.files.length} files uploaded`
            } else {
                return this.schema.placeholder
            }
        },
        handleFileInput(files){
            if (!isEmpty(files)) {
                let setFile = (file) => {
                    this.upload.push({
                        progress: 0,
                        uploading: true,
                        uploaded: false,
                        file: file
                    });
                };

                if (isArray(files)) {
                    forEach(files, setFile);
                } else {
                    setFile(files);
                }

                this.fileModel = null;
                this.$refs['file'].reset();
                this.processUploads();
            }
        },
        handleFileChange(evt){
            forEach(evt.target.files, (file) => {
                this.upload.push({
                    progress: 0,
                    uploading: true,
                    uploaded: false,
                    file
                });
            });
            this.fileModel = null;
            this.$refs['file'].reset();
            this.processUploads();
        },
        removeModelFile(index) {
            this.files.splice(index, 1);
            this.onChange();
            this.canUpload();
            this.placeholder = this.formatNames();
        },
        processUploads(){
            let upload = this.upload.shift();
            if (upload !== undefined) {
                this.processFileUpload(upload);
            }
        },
        processFileUpload(upload){

            let length = this.uploading.push(upload);
            let index = length - 1;

            let file = upload.file;

            this.$fndryApiService.upload(this.$fndryApiService.getHandleUrl(this.schema.action, this.schema.params), file,
                (progressEvent) => {
                    this.uploading[index].progress = Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total );
                })
                .then((response) => {
                    this.uploading[index].progress = 100;
                    this.uploading[index].uploading = false;
                    this.uploading[index].uploaded = true;
                    this.uploading[index].failed = false;
                    this.files.push(merge({}, response.data, {file}));
                })
                .catch((response) => {
                    this.uploading[index].id = null;
                    this.uploading[index].progress = 0;
                    this.uploading[index].uploading = false;
                    this.uploading[index].failed = true;
                    this.uploading[index].error = (response.error) ? response.error : 'Unable to upload file';
                    this.uploading[index].validation = [];
                    if (response.code === 422 && response.data) {
                        forEach(response.data, (errors) => {
                            forEach(errors, (error) => {
                                this.uploading[index].validation.push(error);
                            });
                        });
                    }

                })
                .finally(() => {
                    this.onChange();
                    this.placeholder = this.formatNames();
                    this.processUploads();
                })
            ;
        },
        onChange(){
            this.$emit('input', this.getValue());
        },
        getValue(){
            let value = null;
            if (this.schema.multiple && this.files.length > 0) {
                value = [];
                forEach(this.files, (file) => {
                    if (file.id) {
                        value.push(file.id);
                    }
                });
            } else if (this.files[0]) {
                value = this.files[0].id;
            }
            return value;
        },
        getAcceptAttributes(){

            let rules = this.schema.rules? this.schema.rules.split('|'): [];
            let mime = null;

            for(let key in rules){
                if(rules.hasOwnProperty(key)){
                    if(rules[key].indexOf('mimes:') > -1){
                        if(mime){
                            mime = rules[key].split(':');
                        }
                    }
                }
            }

            if(mime){
                return mime[1];
            }else{
                return '';
            }

        },
        canUpload(){
            if (this.schema.disabled) {
                this.uploadable = false;
                return;
            }
            if (this.schema.multiple) {
                if (this.schema.max && this.files.length >= this.schema.max) {
                    this.uploadable = false;
                    return;
                }
            } else {
                if (this.files.length >= 1) {
                    this.uploadable = false;
                    return;
                }
            }
            this.uploadable = true;
        }
    },
    computed: {
        filesClasses() {
            let cls = [
                `file-attached file-layout`
            ];
            if (this.schema.layout) {
                cls.push(`file-layout-${this.schema.layout}`);
            }
            return cls;
        }
    }
};


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
            type: [Object, Array],
            required: false
        },
        noLabel: {
            type: Boolean,
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
                    if (field.multiple) {
                        type = "multi-select";
                    } else {
                        type = "select";
                    }
                    break;
                case 'file':
                case 'image':
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
                case 'money':
                    type = 'money';
                    break;
                case 'date':
                case "datetime":
                    type = 'date';
                    break;
                case 'repeat':
                    type = 'repeat';
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


