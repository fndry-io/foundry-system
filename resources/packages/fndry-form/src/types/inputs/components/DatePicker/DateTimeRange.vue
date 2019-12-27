<template>
    <div class="date-time-range">
        <div v-if="!noButtons" class="range-up">
            <button type="button" tabindex="-1" class="btn btn-default btn-block btn-sm" @click="$emit('next')"><span class="fa fa-caret-up"></span></button>
        </div>
        <div class="range-value">
            <label :for="inputName" class="sr-only sr-only-focusable">{{inputLabel}}</label>
            <input :name="inputName" v-model="model" @keyup.enter="emitValue" @focusout="emitValue">
        </div>
        <div v-if="label" class="range-label">
            {{label}}
        </div>
        <div v-if="!noButtons" class="range-down">
            <button type="button" tabindex="-1" class="btn btn-default btn-block btn-sm" @click="$emit('prev')"><span class="fa fa-caret-down"></span></button>
        </div>
    </div>
</template>

<script>

    import {uniqueId} from 'lodash';

    export default {
        name: "DateTimePickerRange",
        props: {
            value: {
                required: true
            },
            inputLabel: {
                type: String,
                required: true
            },
            label: String,
            noButtons: Boolean
        },
        data(){
            return {
                model: this.value,
                inputName: uniqueId('time_range_')
            }
        },
        methods: {
            emitValue(){
                this.$emit('input', this.model);
            }
        },
        watch: {
            value: function(newVal, oldVal){
                if (newVal !== oldVal) {
                    this.model = newVal;
                }
            }
        }
    }
</script>
