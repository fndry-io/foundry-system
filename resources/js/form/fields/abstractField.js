import { get as objGet,isNil, forEach, isFunction, isString, isArray, debounce, uniqueId, uniq as arrayUniq, isObject } from "lodash";
import validators from "../utils/validators";
import { slugifyFormID } from "../utils/schema";

function convertValidator(validator) {
	if (isString(validator)) {
		if (validators[validator] != null) return validators[validator];
		else {
			console.warn(`'${validator}' is not a validator function!`);
			return null; // caller need to handle null
		}
	}
	return validator;
}

function attributesDirective(el, binding, vnode) {
	let attrs = objGet(vnode.context, "schema.attributes", {});
	let container = binding.value || "input";
	if (isString(container)) {
		attrs = objGet(attrs, container) || attrs;
	}
	forEach(attrs, (val, key) => {
		el.setAttribute(key, val);
	});
}

export default {
	props: ["vfg", "model", "schema", "formOptions", "disabled", "form"],

	data() {
		return {
			errors: [],
			debouncedValidateFunc: null,
			debouncedFormatFunc: null
		};
	},

	directives: {
		attributes: {
			bind: attributesDirective,
			updated: attributesDirective,
			componentUpdated: attributesDirective
		}
	},

	created() {
		this.configureField(this.schema);
	},

	computed: {
		value: {
			cache: false,
			get() {
				let val;
				if (isFunction(objGet(this.schema, "get"))) {
					val = this.schema.get(this.model);
				} else {
					val = objGet(this.model, this.schema.model);
				}
				return this.formatValueToField(val);
			},

			set(newValue) {
				let oldValue = this.value;
				newValue = this.formatValueToModel(newValue);

				if (isFunction(newValue)) {
					newValue(newValue, oldValue);
				} else {
					this.updateModelValue(newValue, oldValue);
				}
			}
		},
		display:{
            cache: false,
            get() {
                let val;
                if (isFunction(objGet(this.schema, "get"))) {
                    val = this.schema.get(this.model);
                } else {
                    val = objGet(this.model, `${this.schema.model}_display`);
                }
                return this.formatValueToField(val);
            },
		}
	},

	methods: {
		validate(calledParent) {

			this.clearValidationErrors();
			let validateAsync = objGet(this.formOptions, "validateAsync", false);

			let results = [];

			if(isArray(calledParent)){
                results.push(calledParent);
                calledParent = false;
                this.schema.invalid = true;
			}

			if (this.schema.validator && this.schema.readonly !== true && this.disabled !== true) {
				let validators = [];
				if (!isArray(this.schema.validator)) {
					validators.push(convertValidator(this.schema.validator).bind(this));
				} else {
					forEach(this.schema.validator, validator => {
						validators.push(convertValidator(validator).bind(this));
					});
				}

				forEach(validators, validator => {
					if (validateAsync) {
						results.push(validator(this.value, this.schema, this.model));
					} else {
						let result = validator(this.value, this.schema, this.model);
						if (result && isFunction(result.then)) {
							result.then(err => {
								if (err) {
									this.errors = this.errors.concat(err);
								}
								let isValid = this.errors.length === 0;
								this.$emit("validated", isValid, this.errors, this);
							});
						} else if (result) {
							results = results.concat(result);
						}
					}
				});
			}

			let handleErrors = (errors) => {
				let fieldErrors = [];
				forEach(arrayUniq(errors), err => {
					if (isArray(err) && err.length > 0) {
						fieldErrors = fieldErrors.concat(err);
					} else if (isString(err)) {
						fieldErrors.push(err);
					}
				});
				if (isFunction(this.schema.onValidated)) {
					this.schema.onValidated.call(this, this.model, fieldErrors, this.schema);
				}

				let isValid = fieldErrors.length === 0;
				if (!calledParent) {
					this.$emit("validated", isValid, fieldErrors, this);
				}
				this.errors = fieldErrors;
				return fieldErrors;
			};

			if (!validateAsync) {
				return handleErrors(results);
			}

			return Promise.all(results).then(handleErrors);
		},

		debouncedValidate() {
			if (!isFunction(this.debouncedValidateFunc)) {
				this.debouncedValidateFunc = debounce(
					this.validate.bind(this),
					objGet(this.schema, "validateDebounceTime", objGet(this.formOptions, "validateDebounceTime", 500))
				);
			}
			this.debouncedValidateFunc();
		},

		updateModelValue(newValue, oldValue) {
			let changed = false;
			if (isFunction(this.schema.set)) {
				this.schema.set(this.model, newValue);
				changed = true;
			} else if (this.schema.model) {
				this.setModelValueByPath(this.schema.model, newValue);
				changed = true;
			}

			if (changed) {
				this.$emit("model-updated", newValue, this.schema.model);

				if (isFunction(this.schema.onChanged)) {
					this.schema.onChanged.call(this, this.model, newValue, oldValue, this.schema);
				}

				if (objGet(this.formOptions, "validateAfterChanged", false) === true) {
					if (objGet(this.schema, "validateDebounceTime", objGet(this.formOptions, "validateDebounceTime", 0)) > 0) {
						this.debouncedValidate();
					} else {
						this.validate();
					}
				}
			}
		},

		initModelValue(value, id = null) {
            if (isFunction(this.schema.set)) {
                this.schema.set(this.model, value);
            } else if (this.schema.model) {
                this.setModelValueByPath(this.schema.model, value, id);
            }
		},

		clearValidationErrors() {
			this.errors.splice(0);
		},

		setModelValueByPath(path, value, id = null) {
			// convert array indexes to properties
			let s = path.replace(/\[(\w+)\]/g, ".$1");

			// strip a leading dot
			s = s.replace(/^\./, "");

			let o = this.model;
			const a = s.split(".");
			let i = 0;
			const n = a.length;
			while (i < n) {
				let k = a[i];
				if (i < n - 1)
					if (o[k] !== undefined) {
						// Found parent property. Step in
						o = o[k];
					} else {
						// Create missing property (new level)
						this.$root.$set(o, k, {});
						o = o[k];
					}
				else {
					if(id){
						if(value !== ''){
                            this.$root.$set(o, k, id);
                            this.search = value;
                            //this.$root.$set(o, `${k}_display`, value);
						} else
							this.$root.$delete(o, k);
					}else{
                        // Set final property value
                        this.$root.$set(o, k, value);
					}

					return;
				}

				++i;
			}
		},

		getFieldID(schema, unique = false) {
			const idPrefix = objGet(this.formOptions, "fieldIdPrefix", "");
			return slugifyFormID(schema, idPrefix) + (unique ? "-" + uniqueId() : "");
		},
		isInvalid(){
			if(isNil(this.schema.invalid)) return false;

			return this.schema.invalid
		},
        getFieldName(schema) {
            return `${schema.name}`
        },
        getHiddenFieldID(schema, unique = false) {
            const idPrefix = objGet(this.formOptions, "fieldIdPrefix", "");
            let id = slugifyFormID(schema, idPrefix) + (unique ? "-" + uniqueId() : "");

            return `${id}_id`;
        },
        getMainClass(field){
			return `wrapper ${field.buttons && field.buttons.length > 0 ? 'with-buttons': ''}`
		},
        getHiddenFieldName(schema) {
            return `${schema.name}_id`;
        },
		getFieldClasses() {
			return objGet(this.schema, "class", []);
		},
        getHiddenFieldDisplayID(schema){
            return `${schema.name}_display_id`;
		},
        getHiddenFieldDisplayName(schema){
            return `${schema.name}_display`;
        },
		formatValueToField(value) {
			return value;
		},
        buttonVisibility(field) {
            return (field.buttons && field.buttons.length > 0);
        },
        getButtonClasses(btn){
			if(!btn.class){
				switch (btn.type) {
					case 'edit':
						btn.class = 'primary';
						break;
					case 'add':
                    case 'form':
						btn.class = 'success';
						break;
                }
			}
            return `btn btn-${btn.class}`;
        },
        getButtonType(btn) {
            return objGet(btn, "type", "form");
        },
        getButtonVisibility(btn, value){
			let visible = true;

			switch (btn.type) {
				case 'add':
					visible = !value;
					break;
				case 'edit':
					visible = !!value;
					break;
            }

            return visible;
		},
        getWrapperClasss(schema){
            return `field-wrap ${schema.buttons && schema.buttons.length > 0 ? 'with-buttons': ''}`
        },
		formatValueToModel(value) {
			return value;
		},
        addValidation(field){

			field.validator = this.getValidations(field);

            return field;
        },
		getValidations(field){

			let validations = [];

            //todo add more validations

			switch (field.type) {
				case 'email':
                    validations.push(validators.email);
                    break;
				case 'number':
					validations.push(validators.number);
                    break;
				case 'tel':
                    validations.push(validators.numeric);
                    break;
                default:
                    validations.push(validators.string);
            }

			return validations;
		},
		configureField(field){

            field.model = field.name;
            this.addValidation(field);

            let val = field.value !== null? field.value : field.default;

			if(val){
				if(!isArray(val)){
					if(!isObject(val))
						val = val.toString();
				}else{
					let temp = [];

					for (let i = 0; i < val.length; i++){
						temp.push(val[i].toString())
					}
					val = temp;
				}
			}

			switch (field.type) {
				case 'reference':
					this.search = this.getReferenceValue(field);
					break;
				case 'switch':
					val = val == null? field.checked? 1: 0 : val;
				case 'datetime':
				case 'date':
					val = this.formatValueToField(val);
					break;

            }

            this.initModelValue(val);
		},
		getReferenceValue(field){
			return field.reference?
						field.reference.label?
							field.reference.label :
							field.reference.name : '';
		},
        groupValues(values) {

            let v = [];

            for (let key in values){
                if(typeof key !== "object" && values.hasOwnProperty(key)){
                    v.push({value: key, label: values[key]});
                }else{
                    v.push(values[key]);
                }
            }

            values = v;

            let array = [];
            let arrayElement = {};

            values.forEach(item => {
                arrayElement = null;

                if (item.group && isObject(item)) {
                    // There is in a group.

                    // Find element with this group.
                    arrayElement = find(array, i => i.group === item.group);

                    if (arrayElement) {
                        // There is such a group.

                        arrayElement.ops.push({
                            id: item.id,
                            name: item.name
                        });
                    } else {
                        // There is not such a group.

                        // Initialising.
                        arrayElement = {
                            group: "",
                            ops: []
                        };

                        // Set group.
                        arrayElement.group = item.group;

                        // Set Group element.
                        arrayElement.ops.push({
                            id: item.id,
                            name: item.name
                        });

                        // Add array.
                        array.push(arrayElement);
                    }
                } else {
                    // There is not in a group.
                    array.push(item);
                }
            });

            // With Groups.
            return array;
        },

        getGroupName(item) {
            if (item && item.group) {
                return item.group;
            }

            throw "Group name is missing!";
        },

        getItemValue(item) {
            if (isObject(item)) {
                if (typeof this.schema["selectOptions"] !== "undefined" && typeof this.schema["selectOptions"]["value"] !== "undefined") {
                    return item[this.schema.selectOptions.value];
                } else {
                    if (typeof item["value"] !== "undefined") {
                        return item.value;
                    } else {
                        throw "`value` is not defined. If you want to use another key name, add a `value` property under `selectOptions` in the field object.";
                    }
                }
            } else {
                return item;
            }
        },

        getItemName(item) {
            if (isObject(item)) {
                if (typeof this.schema["selectOptions"] !== "undefined" && typeof this.schema["selectOptions"]["label"] !== "undefined") {
                    return item[this.schema.selectOptions.label];
                } else {
                    if (typeof item["label"] !== "undefined") {
                        return item.label;
                    } else {
                        throw "`label` is not defined. If you want to use another key name, add a `label` property under `selectOptions` in the field object.";
                    }
                }
            } else {
                return item;
            }
        },
        getButtonAction(btn){

			let action = btn.action;

            if(this.value){
                action = `${btn.action}&_id=${this.value}`;
            }

            return action;
		},
        onRelatedFormSubmitted(schema, data){

            if(data && data.status){

                let obj = data.data;
                let label = objGet(obj, "label", "");
                if(!label) label = objGet(obj, 'name', "");
                let id = obj.id;

				this.search = label;
                this.initModelValue(id);


            }else{
                //todo show error
            }
        },
        onRelatedFormDeleted(schema){
			schema.reference = null;
            this.search = '';
            this.initModelValue('');
		},
        onInputEnter(){
			if(this.vfg.inline){
                this.$emit("filter-enter", {enter: true});
			}
		}
	}
};
