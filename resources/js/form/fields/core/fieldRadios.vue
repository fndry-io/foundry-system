
<template>
    <div>
        <div :class="getRadioWrapperClass()">
            <label class="k-radio"
                   v-for="(item, key) in items">
                <input
                        :name="getFieldName(schema)"
                        @click="onSelection(key)"
                        :id="getFieldID(schema, true)"
                        :checked="isItemChecked(key)"
                        :disabled="isItemDisabled(key)"
                        :value="key"
                        :class="schema.class"
                        :required="schema.required"
                        type="radio"> {{getItemName(item)}}
                <span></span>
            </label>
        </div>
        <span class="form-text text-muted" v-if="schema.help">{{schema.help}}</span>
    </div>
</template>
<script>
import { isObject, isFunction, get as objGet } from "lodash";
import abstractField from "../abstractField";

export default {
	mixins: [abstractField],

	computed: {
		items() {
			let values = this.schema.options;
			if (typeof values == "function") {
				return values.apply(this, [this.model, this.schema]);
			} else {
				return values;
			}
		},
		id() {
			return this.schema.model;
		}
	},

	methods: {
		getItemValue(item) {
			if (isObject(item)) {
				if (typeof this.schema["radiosOptions"] !== "undefined" && typeof this.schema["radiosOptions"]["value"] !== "undefined") {
					return item[this.schema.radiosOptions.value];
				} else {
					if (typeof item["value"] !== "undefined") {
						return item.value;
					} else {
						throw "`value` is not defined. If you want to use another key name, add a `value` property under `radiosOptions` in the object.";
					}
				}
			} else {
				return item;
			}
		},
		getItemName(item) {
			if (isObject(item)) {
				if (typeof this.schema["radiosOptions"] !== "undefined" && typeof this.schema["radiosOptions"]["label"] !== "undefined") {
					return item[this.schema.radiosOptions.label];
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
		getItemCssClasses(item) {
			return {
				"is-checked": this.isItemChecked(item),
				"is-disabled": this.isItemDisabled(item)
			};
		},
		onSelection(item) {
			this.value = this.getItemValue(item);
		},
		isItemChecked(item) {
			let currentValue = this.getItemValue(item);
			return currentValue == this.value;
		},
		isItemDisabled(item) {
			if (this.disabled) {
				return true;
			}
			let disabled = objGet(item, "disabled", false);
			if (isFunction(disabled)) {
				return disabled(this.model);
			}
			return disabled;
		},
        getRadioWrapperClass(){
            return `${this.schema.inline? 'k-radio-inline': 'k-radio-list'}`;
        },
	}
};
</script>

<style lang="scss">

</style>
