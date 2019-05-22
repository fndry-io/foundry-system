
<template>
    <div>
        <div :class="getCheckboxWrapperClass()">
            <label class="k-checkbox"
                   v-for="(item, key) in items">
                <input
                        @change="onChanged($event, key)"
                        :name="getInputName(item)"
                        :id="getFieldID(schema, true)"
                        :checked="isItemChecked(key)"
                        :disabled="disabled"
                        :value="key"
                        type="checkbox"> {{getItemName(item)}}
                <span></span>
            </label>
        </div>
    </div>

</template>
<script>
import { isObject, isNil, clone } from "lodash";
import abstractField from "../abstractField";
import { slugify } from "../../utils/schema";

export default {
	mixins: [abstractField],
	data() {
		return {
			comboExpanded: false
		};
	},

	computed: {
		items() {
			let values = this.schema.options;
			if (typeof values == "function") {
				return values.apply(this, [this.model, this.schema]);
			} else return values;
		},

		selectedCount() {
			if (this.value) return this.value.length;

			return 0;
		}
	},

	methods: {
		getInputName(item) {
			if (this.schema && this.schema.inputName && this.schema.inputName.length > 0) {
				return slugify(this.schema.inputName + "_" + this.getItemValue(item));
			}
			return slugify(this.getItemValue(item));
		},

		getItemValue(item) {
			if (isObject(item)) {
				if (typeof this.schema["checklistOptions"] !== "undefined" && typeof this.schema["checklistOptions"]["value"] !== "undefined") {
					return item[this.schema.checklistOptions.value];
				} else {
					if (typeof item["value"] !== "undefined") {
						return item.value;
					} else {
                        throw "`value` is not defined. If you want to use another key name, add a `value` property under `radiosOptions` in the object.";
					}
				}
			} else {
			    //TODO Update this to handle different values, the Types from the server should describe this
				return item;
			}
		},
		getItemName(item) {
			if (isObject(item)) {
				if (typeof this.schema["checklistOptions"] !== "undefined" && typeof this.schema["checklistOptions"]["label"] !== "undefined") {
					return item[this.schema.checklistOptions.label];
				} else {
					if (typeof item["label"] !== "undefined") {
						return item.label;
					} else {
                        throw "`label` is not defined. If you want to use another key name, add a `label` property under `radiosOptions` in the field object.";
					}
				}
			} else {
				return item;
			}
		},

		isItemChecked(item) {
			return this.value && this.value.indexOf(this.getItemValue(item)) !== -1;
		},

		onChanged(event, item) {
			if (isNil(this.value) || !Array.isArray(this.value)) {
				this.value = [];
			}

			if (event.target.checked) {
				// Note: If you modify this.value array, it won't trigger the `set` in computed field
				const arr = clone(this.value);
				arr.push(this.getItemValue(item));
				this.value = arr;
			} else {
				// Note: If you modify this.value array, it won't trigger the `set` in computed field
				const arr = clone(this.value);
				arr.splice(this.value.indexOf(this.getItemValue(item)), 1);
				this.value = arr;
			}

		},
        getCheckboxWrapperClass(){
            return `${this.schema.inline? 'k-checkbox-inline': 'k-checkbox-list'}`;
        },
	},
    mounted(){

    },
};
</script>


<style lang="scss">

</style>
