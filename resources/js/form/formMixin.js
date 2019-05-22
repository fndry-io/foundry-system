import { get as objGet, forEach, isNil, isArray, isString, isFunction } from "lodash";
import validators from "./utils/validators";

export default {
	methods: {
		// Get style classes of field
		getFieldRowClasses(field) {
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
		fieldErrors(field) {
			let res = this.errors.filter(e => e.field === field);
			return res.map(item => item.error);
		},
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

		addValidation(field){

            if(field.required){
                field.validator = validators.string.locale({
                    fieldIsRequired: "The field is required!",
                });
            }

            //Todo add more validation rules
			return field;
		}
	}
};
