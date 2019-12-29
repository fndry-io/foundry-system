<template>
    <ValidationProvider ref="provider" :vid="getVid()" :rules="schema.rules" :name="name" v-slot="{ validate, errors, valid, failedRules }" :slim="true">
        <b-form-group
                :id="`fieldset-${schema.name}`"
                :description="schema.help"
                :label="(!noLabel) ? schema.label : null"
                :label-for="schema.id"
                :state="valid"
                :required="fieldRequired(schema)"
                :class="{'required': fieldRequired(schema)}"
                v-if="schema.type !== 'hidden'"
                :label-cols="(schema.inline) ? 2: null"
        >
            <div class="field-wrap">
                <component ref="name"
                           :is="fieldType(schema)"
                           :schema="schema"
                           :id="schema.id"
                           :name="name"
                           v-model="value"
                           :root-model="model"
                           @input="onInput"
                           @change="onChange"
                           :state="valid"
                           :validation="{validate}"
                           :required="fieldRequired(schema)"
                           :disabled="schema.disabled"
                ></component>
            </div>
            <b-form-invalid-feedback :state="valid">
                <span v-for="(error, index) in errors" :key="index">{{error}}</span>
            </b-form-invalid-feedback>
        </b-form-group>
        <fndry-field-hidden
                v-else
                ref="name"
                :id="schema.id"
                :name="name"
                v-model="value"
                :root-model="model"
                @input="onInput"
                @change="onChange"
        ></fndry-field-hidden>
    </ValidationProvider>
</template>

<script>

    import {merge} from 'lodash';

    import fields from "../types/fields";
    import field from "../mixins/field";
    import schema from "../mixins/schema";
    import fieldGroup from "../mixins/fieldGroup";

    export default {
        name: "form-group",
        components: merge(fields),
        mixins: [
            field,
            schema,
            fieldGroup
        ]
    };
</script>


<style>
    .form-group.required > label::after {
        display: inline;
        content: ' *';
        color: #dc3545;
    }
</style>
