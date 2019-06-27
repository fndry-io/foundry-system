import { get, set, each, isObject, isArray, isFunction, cloneDeep } from "lodash";

// Create a new model by schema default values
export const createDefaultObject = (schema, obj = {}) => {
	each(schema.fields, field => {
		if (get(obj, field.model) === undefined && field.default !== undefined) {
			if (isFunction(field.default)) {
				set(obj, field.model, field.default(field, schema, obj));
			} else if (isObject(field.default) || isArray(field.default)) {
				set(obj, field.model, cloneDeep(field.default));
			} else set(obj, field.model, field.default);
		}
	});
	return obj;
};

// Get a new model which contains only properties of multi-edit fields
export const getMultipleFields = schema => {
	let res = [];
	each(schema.fields, field => {
		if (field.multi === true) res.push(field);
	});

	return res;
};

export const getInputValues = (schema, model) => {
    //console.log(schema);
	if (schema.hasOwnProperty('name')) {
		if (get(model, schema.name, undefined) === undefined) {
            if (schema.hasOwnProperty('type') && (schema.type === 'switch' || schema.type === 'checkbox')) {
            	if (schema.value === schema.checkedValue) {
                    set(model, schema.name, schema.value);
				} else {
                    set(model, schema.name, schema.uncheckedValue);
				}
            } else if (schema.hasOwnProperty('value')) {
                set(model, schema.name, schema.value);
            } else if(schema.multiple === true) {
                set(model, schema.name, []);
            } else {
                set(model, schema.name, null);
            }
		}
	}
	if (schema.hasOwnProperty('children')) {
		getChildInputValues(schema, model);
	}
};

export const getChildInputValues = (schema, model) => {
    each(schema.children, (child) => {
        getInputValues(child, model);
    });
}

// Merge many models to one 'work model' by schema
export const mergeMultiObjectFields = (schema, objs) => {
	let model = {};

	let fields = getMultipleFields(schema);

	each(fields, field => {
		let mergedValue;
		let notSet = true;
		let path = field.model;

		each(objs, obj => {
			let v = get(obj, path);
			if (notSet) {
				mergedValue = v;
				notSet = false;
			} else if (mergedValue !== v) {
				mergedValue = undefined;
			}
		});

		set(model, path, mergedValue);
	});

	return model;
};

export const slugifyFormID = (schema, prefix = "") => {
	// Try to get a reasonable default id from the schema,
	// then slugify it.
	if (typeof schema.id !== "undefined") {
		// If an ID's been explicitly set, use it unchanged
		return prefix + schema.id;
	} else {
		// Return the slugified version of either:
		return (
			prefix +
			(schema.inputName || schema.label || schema.model || "")
				// NB: This is a very simple, conservative, slugify function,
				// avoiding extra dependencies.
				.toString()
				.trim()
				.toLowerCase()
				// Spaces & underscores to dashes
				.replace(/ |_/g, "-")
				// Multiple dashes to one
				.replace(/-{2,}/g, "-")
				// Remove leading & trailing dashes
				.replace(/^-+|-+$/g, "")
				// Remove anything that isn't a (English/ASCII) letter, number or dash.
				.replace(/([^a-zA-Z0-9-]+)/g, "")
		);
	}
};

export const slugify = (name = "") => {
	// Return the slugified version of either:
	return (
		name
			// NB: This is a very simple, conservative, slugify function,
			// avoiding extra dependencies.
			.toString()
			.trim()
			// .toLowerCase()
			// Spaces to dashes
			.replace(/ /g, "-")
			// Multiple dashes to one
			.replace(/-{2,}/g, "-")
			// Remove leading & trailing dashes
			.replace(/^-+|-+$/g, "")
			// Remove anything that isn't a (English/ASCII) letter, number or dash.
			.replace(/([^a-zA-Z0-9-_/./:]+)/g, "")
	);
};

export const getFields = (form) => {

    let fields = {};
    let pickLists = {};

    let getField = (children) => {

        for (let index in children){
            if(children.hasOwnProperty(index)){
                if(children[index].type && children[index].type === 'section'){
                    fields[children[index].title] = children[index];
                }else if(!children[index].children) {
                    if(children[index].name) {
                        let name = children[index].name;
                        fields[name] = children[index];
                    }
                    else{
                        if(children[index].title)
                            fields[children[index].title] = children[index];
                    }

                    if(children[index].type === 'select'){
                        let i = children[index].name.lastIndexOf('.');
                        let n = children[index].name.substring(i+1);
                        pickLists[n] = children[index].options;
                    }
                }else{
                    if(children[index].children.length > 0){
                        getField(children[index].children);
                    }
                }
            }
        }
    };

    getField(form.children);

    return {
        fields: fields,
        pickLists: pickLists
    };

};

export default {
	createDefaultObject,
	getMultipleFields,
	mergeMultiObjectFields,
	slugifyFormID,
	slugify,
	getFields
};
