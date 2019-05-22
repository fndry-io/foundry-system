<template>
	<input
	    :id="getFieldID(schema)"
	    type="checkbox"
	    v-model="value"
	    :autocomplete="schema.autocomplete"
	    :disabled="disabled"
	    :name="getFieldName(schema, form)"
	    :class="schema.class"
	    :required="schema.required"
	>
</template>

<script>
import abstractField from "../abstractField";

export default {
	mixins: [abstractField]
};
</script>
