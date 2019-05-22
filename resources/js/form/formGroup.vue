<template>
	<div class="form-group" :class="getFieldRowClasses(field)">
		<label
				v-if="fieldTypeHasLabel(field)"
				:for="getFieldID(field)">
			<span v-html="field.label"></span>
		</label>

		<div class="field-wrap">
			<component ref="child"
					   :is="getFieldType(field)"
					   :vfg="vfg"
					   :invalid="fieldErrors(field).length > 0"
					   :disabled="fieldDisabled(field)"
					   :model="model"
					   :schema="field"
					   :formOptions="options"
					   @button-clicked="onButtonClicked"
					   @filter-enter="onFilterEnter"
					   @model-updated="onModelUpdated"
					   @validated="onFieldValidated"></component>
		</div>
		<div v-if="fieldErrors(field).length > 0" class="invalid-feedback errors">
			<span v-for="(error, index) in fieldErrors(field)" :key="index" v-html="error"></span>
		</div>
		<span class="form-text text-muted" v-html='field.help'>{{field.help}}</span>
	</div>
</template>
<script>

import { get as objGet, isNil, isFunction } from "lodash";
import { slugifyFormID } from "./utils/schema";
import formMixin from "./formMixin.js";
import fieldComponents from "./utils/fieldsLoader.js";

export default {
	name: "form-group",
	components: fieldComponents,
	mixins: [formMixin],
	props: {
		vfg: {
			type: Object,
			required: true
		},
		model: Object,
		options: {
			type: Object
		},
		field: {
			type: Object,
			required: true
		},
		errors: {
			type: Array,
			default() {
				return [];
			}
		}
	},
	mounted(){
	    //console.log(fieldComponents);
	},
	methods: {
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
		getFieldID(schema) {
			const idPrefix = objGet(this.options, "fieldIdPrefix", "");
			return slugifyFormID(schema, idPrefix);
		},
        getFieldName(schema, form) {
            return `${form.name}[]${schema.name}`
        },
		// Get type of field 'field-xxx'. It'll be the name of HTML element
		getFieldType(fieldSchema) {

		    let type = "input";

		    switch (fieldSchema.type) {
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
                case 'checkbox':
                    type = 'checkboxes';
                    break;
				case 'switch':
				    type = 'switch';
				    break;
				case 'radio':
				    type = 'radios';
				    break;
				case 'tag':
				    type = 'tag';
				    break;
				case 'date':
				case "datetime":
				    type = 'date';
				    break;
            }

            type = (type === 'input')? (fieldSchema.multiline)? 'textArea': type : type;

			return "field-" + type;
		},
		// Get type of button, default to 'button'
		getButtonType(btn) {
			return objGet(btn, "type", "button");
		},
		// Child field executed validation
		onFieldValidated(res, errors, field) {
			this.$emit("validated", res, errors, field);
		},
		buttonVisibility(field) {
			return field.buttons && field.buttons.length > 0;
		},
		buttonClickHandler(btn, field, event) {
			return btn.onclick.call(this, this.model, field, event, this);
		},
		// Get current hint.
		fieldHint(field) {
			if (isFunction(field.hint)) return field.hint.call(this, this.model, field, this);

			return field.hint;
		},
		fieldErrors(field) {
			return this.errors.filter((e) => e.field === field).map((item) => item.error);
		},
		onModelUpdated(newVal, schema) {
			this.$emit("model-updated", newVal, schema);
		},
		validate(calledParent) {
			return this.$refs.child.validate(calledParent);
		},
		clearValidationErrors() {
			if (this.$refs.child) {
				return this.$refs.child.clearValidationErrors();
			}
		},
        onButtonClicked(btn){
            this.$emit("button-clicked", btn);
        },
        onFilterEnter(btn){
            this.$emit("filter-enter", btn);
        }
	}
};
</script>
