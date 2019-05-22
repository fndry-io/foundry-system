<template>
    <div>
        <span class="k-switch k-switch--icon">
        <label>
            <input
                    :required="schema.required"
                    :class="schema.class"
                    @click="onClick()"
                    :disabled="schema.readonly"
                    :name="getFieldName(schema)"
                    type="checkbox"
                    :checked="value != 0">
            <span></span>
        </label>
    </span>
    </div>
</template>
<script>
import { isObject, isFunction, get as objGet } from "lodash";
import abstractField from "../abstractField";

export default {
	mixins: [abstractField],

    mounted(){
    },

	computed: {
		id() {
			return this.schema.model;
		}
	},

	methods: {
		onClick() {
			this.value = (this.value == 0) ? 1 : 0;
		},
	}
};
</script>

<style lang="scss">

</style>
