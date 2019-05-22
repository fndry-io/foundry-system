<template>
<div :class="getFormClasses()">
    <div :id="schema.id" :class="schema.class">
        <form-component
                v-for="(child, key) in schema.children"
                :vfg="vfg"
                :key="key"
                :schema="child"
                :errors="errors"
                :options="options"
                :model="model"
                @button-clicked="handleButtonClick"
                @validated="onFieldValidated"
                @model-updated="onModelUpdated"></form-component>
    </div>
</div>
</template>

<script>
import { get as objGet, forEach, isFunction, isNil, isArray, isEmpty, isObject } from "lodash";
import formMixin from "./formMixin.js";
import formComponent from "./formComponent";
import ApiService, {route} from "../services/ApiService";

//todo move this to a shared location
function getScrollParent(node) {
    if (node == null) {
        return null;
    }

    if (node.scrollHeight > node.clientHeight) {
        return node;
    } else {
        return getScrollParent(node.parentNode);
    }
}

export default {
	name: "formGenerator",
	components: { formComponent },
	mixins: [formMixin],
	props: {
		schema: Object,
		model: {
		    type: Object,
            default: () => ({}),
        },
		options: {
			type: Object,
			default() {
				return {
					validateAfterLoad: false,
					validateAsync: false,
					validateAfterChanged: false,
					validationErrorClass: "error",
					validationSuccessClass: ""
				};
			}
		},
		multiple: {
			type: Boolean,
			default: false
		},
		isNewModel: {
			type: Boolean,
			default: false
		},
		tag: {
			type: String,
			default: "fieldset",
			validator: function(value) {
				return value.length > 0;
			}
		},
        validateBeforeSubmit: {
		    type: Boolean,
            default: true
        },
        inline: {
            type: Boolean,
            default: false
        }
	},

	data() {
		return {
			vfg: this,
			errors: [], // Validation errors
            submitting: false
		};
	},

	watch: {
		// new model loaded
		model: function(newModel, oldModel) {
			if (oldModel === newModel)
				// model property not changed, skip
				return;

			if (newModel != null) {
				this.$nextTick(() => {
					// Model changed!
					if (this.options.validateAfterLoad === true && this.isNewModel !== true) {
						this.validate();
					} else {
						this.clearValidationErrors();
					}
				});
			}
		}
	},

	mounted() {
		this.$nextTick(() => {
			if (this.model) {
				// First load, running validation if necessary
				if (this.options.validateAfterLoad === true && this.isNewModel !== true) {
					this.validate();
				} else {
					this.clearValidationErrors();
				}
			}
		});
	},

	methods: {
        handleButtonClick(btn){
            if(btn.type === 'submit'){
                this.submitForm(btn);
            }
        },
        getFormClasses(){
            return `form-generator ${this.inline? "form-inline": ""}`;
        },
        submitForm: function(btn){

            if (this.validateBeforeSubmit === true) {

                let validateAsync = objGet(this.formOptions, "validateAsync", true);
                let errors = this.validate();

                let handleErrors = errors => {
                    if ((validateAsync && !isEmpty(errors)) || (!validateAsync && !errors)) {
                        if (isFunction(this.onValidationError)) {
                            this.onValidationError(this.model, this.schema, errors, btn);
                        }
                        let parent = getScrollParent(this.$el);
                        if (parent) {
                            parent.scrollTop = 0;
                        }
                        this.$bvToast.toast('Sorry, errors were found. Please check inputs and try again.', {
                            autoHideDelay: 5000,
                            variant: 'warning',
                            solid: true
                        })
                    } else if (isFunction(this.onSubmit)) {
                        this.onSubmit(this.model, this.schema, btn);
                    }
                };

                if (errors && isFunction(errors.then)) {
                    errors.then(handleErrors);
                } else {
                    handleErrors(errors);
                }
            } else if (isFunction(this.onSubmit)) {
                // if we aren't validating, just pass the onSubmit handler the $event
                // so it can be handled there
                this.onSubmit(this.model, this.schema, btn);
            }

        },
		// Get visible prop of field
		fieldVisible(field) {
			if (isFunction(field.visible)) return field.visible.call(this, this.model, field, this);

			if (isNil(field.visible)) return true;

			return field.visible;
		},

		// Child field executed validation
		onFieldValidated(res, errors, field) {
			// Remove old errors for this field
			this.errors = this.errors.filter(e => e.field !== field.schema);
			if (!res && errors && errors.length > 0) {
				// Add errors with this field
				forEach(errors, err => {
					this.errors.push({
						field: field.schema,
						error: err
					});
				});
			}

			let isValid = this.errors.length === 0;
			this.$emit("validated", isValid, this.errors, this);
		},

		onModelUpdated(newVal, schema) {
			this.$emit("model-updated", newVal, schema);
		},

		// Validating the model properties
		validate(isAsync = null) {
			if (isAsync === null) {
				isAsync = objGet(this.options, "validateAsync", false);
			}

			this.clearValidationErrors();

			let fields = [];
			let results = [];

			let childValidation = child => {
                if (isFunction(child.validate)) {
                    fields.push(child.$refs.child); // keep track of validated children
                    results.push(child.validate(true));
                }else{
                    if(child.$children.length){
                        forEach(child.$children, child => {
                            childValidation(child);
                        });
                    }
                }
            };

			forEach(this.$children, child => {
                childValidation(child);
			});

			let handleErrors = errors => {
				let formErrors = [];
				forEach(errors, (err, i) => {
					if (isArray(err) && err.length > 0) {
						forEach(err, error => {
							formErrors.push({
								field: fields[i].schema,
								error: error
							});
						});
					}
				});
				this.errors = formErrors;
				let isValid = formErrors.length === 0;
				this.$emit("validated", isValid, formErrors, this);
				return isAsync ? formErrors : isValid;
			};

			if (!isAsync) {
				return handleErrors(results);
			}

			return Promise.all(results).then(handleErrors);
		},

		// Clear validation errors
		clearValidationErrors() {
			this.errors.splice(0);

			forEach(this.$children, child => {
			    if(isFunction(child.clearValidationErrors))
				    child.clearValidationErrors();
			});
		},

        onValidationError(model, schema, errors, e) {
            this.$emit('error', errors,  e);
        },

        handleServerErrors(errors){

            let childError = (child, er, key) => {
                if (isFunction(child.validate) && child.$refs.child) {
                    let schema = child.$refs.child.schema;
                    if(schema.name === key){
                        child.validate(er);
                    }
                }else{
                    if(child.$children.length){
                        forEach(child.$children, child => {
                            childError(child, er, key);
                        });
                    }
                }
            };

            for (let key in errors){
                if(errors.hasOwnProperty(key)){
                    let error = errors[key];
                    forEach(this.$children, child => {
                        childError(child, error, key);
                    });
                }
            }
        },
        onSubmit(model, schema, btn) {
            this.$set(this.model, 'submitting', true);

            ApiService.call(route(btn.action, btn.query), btn.method, model)
                .then((res) => {
                    if(!res.status){
                        //console.log(res.error);
                    }
                    this.$emit('success', res, model, btn);
                }, (res) => {
                    if(!res.status){
                        if(isObject(res.error)){
                            this.handleServerErrors(res.error);
                        }
                    }
                    this.$emit('fail', res, model, btn);
                })
                .finally(() => {
                    this.$delete(this.model, 'submitting');
                    this.$emit('finally');
                })
            ;
        },

        onCancel() {
            this.$emit('cancel');
        },

    }
};
</script>

<style lang="scss">
    .form-generator {
        &.form-inline{
            margin: 0 -5px 15px;
            label{
                display: none;
            }
            .form-fields-holder {
                display: flex;
                margin: 0 5px;
                .dropdown.bootstrap-select.form-control{
                    min-width: 160px;
                }
            }
            button {
                margin: 0 5px;
            }
            div{
                width: 100%;

                input{
                    width: 100%
                }
            }
        }
        .form-group.validated{
            .invalid-feedback.errors{
                display: block;
            }
            .form-control:invalid,
            .form-control.is-invalid,
            .form-control[invalid="true"],
            .custom-select:invalid,
            .custom-select.is-invalid {
                border-color: #d42c27 !important;
            }
            select.form-control[invalid="true"] + button {
                border: 1px solid #d42c27;
            }
        }

        .field-wrap.with-buttons {
            display: flex;
            .wrapper.with-buttons {
                flex: 1;
            }
        }
        select.selectpicker{
            display: block !important;
        }
    }
</style>
