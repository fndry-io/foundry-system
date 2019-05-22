<template>
    <textarea
            class="form-control"
            v-model="value"
            :id="getFieldID(schema)"
            :class="schema.class"
            :disabled="disabled"
            :maxlength="schema.max"
            :minlength="schema.min"
            :invalid="isInvalid()"
            :placeholder="schema.placeholder"
            :readonly="schema.readonly"
            :required="schema.required"
            v-on:keyup.enter="onInputEnter()"
            :rows="schema.multiline || 3"
            :name="getFieldName(schema, form)"
    ></textarea>
</template>

<script>
import abstractField from "../abstractField";

export default {
	mixins: [abstractField]
};
</script>
