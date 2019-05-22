<template>
    <div class="wrapper">
        <input type="text" class="form-control"
               :id="getFieldID(schema)"
               :type="inputType"
               :value="value"
               @input="onInput"
               @blur="onBlur"
               :class="schema.class"
               @change="schema.onChange || null"
               :disabled="disabled"
               :accept="schema.accept"
               :alt="schema.alt"
               :autocomplete="schema.autocomplete"
               :checked="schema.checked"
               :height="schema.height"
               :list="schema.list"
               :max="schema.max"
               :maxlength="schema.maxlength"
               :min="schema.min"
               :minlength="schema.minlength"
               :multiple="schema.multiline"
               :name="getFieldName(schema)"
               :pattern="schema.pattern"
               :placeholder="schema.placeholder"
               :readonly="schema.readonly"
               :required="schema.required"
               :invalid="isInvalid()"
               :size="schema.size"
               :src="schema.src"
               :step="schema.step"
               v-on:keyup.enter="onInputEnter()"
               :width="schema.width"
        >
        <span v-if="schema.type.toLowerCase() === 'color' || schema.type.toLowerCase() === 'range'">{{ value }}></span>
</div>
</template>

<script>
import abstractField from "../abstractField";
import { debounce, get as objGet, isFunction, isNumber } from "lodash";

export default {
    name: 'fieldInput',
	mixins: [abstractField],
	computed: {
		inputType() {
			return this.schema.type;
		}
	},
	methods: {
		formatValueToModel(value) {
			if (value != null) {
				switch (this.schema.type.toLowerCase()) {
					case "number":
					case "range":
						// debounce
						return (newValue, oldValue) => {
							this.debouncedFormatFunc(value, oldValue);
						};
				}
			}

			return value;
		},
		formatValueToField(value) {
			return value;
		},

		formatNumberToModel(newValue, oldValue) {
			if (!isNumber(newValue)) {
				newValue = NaN;
			}
			this.updateModelValue(newValue, oldValue);
		},
		onInput($event) {
			let value = $event.target.value;
			switch (this.schema.type.toLowerCase()) {
				case "number":
				case "range":
					if (isNumber(parseFloat($event.target.value))) {
						value = parseFloat($event.target.value);
					}
					break;
			}
			this.value = value;
		},
		onBlur() {
			if (isFunction(this.debouncedFormatFunc)) {
				this.debouncedFormatFunc.flush();
			}
		}
	},

	mounted() {
		switch (this.schema.type.toLowerCase()) {
			case "number":
			case "range":
				this.debouncedFormatFunc = debounce(
					(newValue, oldValue) => {
						this.formatNumberToModel(newValue, oldValue);
					},
					parseInt(objGet(this.schema, "debounceFormatTimeout", 1000)),
					{
						trailing: true,
						leading: false
					}
				);
				break;
		}
	},

	created() {
		if (this.schema.type.toLowerCase() === "file") {
			//console.warn("Use 'file' field instead.");
		}
	}
};
</script>
