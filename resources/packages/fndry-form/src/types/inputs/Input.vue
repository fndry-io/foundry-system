<template>
    <div class="input-group">
        <div class="input-group-prepend" v-if="schema.prepend">
            <span class="input-group-text">{{schema.prepend}}</span>
        </div>
        <b-form-input :type="this.schema.type"
                      :id="id"
                      :name="name"
                      v-model="model"
                      :placeholder="schema.placeholder"
                      :disabled="disabled"
                      :autocomplete="autocomplete"
                      :min="schema.min"
                      :max="schema.max"
                      :required="schema.required"
                      v-on="$listeners"
                      :state="state"
                      :step="schema.step"
        ></b-form-input>
        <div class="input-group-append" v-if="schema.append">
            <span class="input-group-text">{{schema.append}}</span>
        </div>
    </div>
</template>

<script>

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-input",
        mixins: [
            abstractInput
        ]

    };
</script>
