<template>
    <div>
        <b-input-group v-if="!schema.invert">
            <date-picker v-if="schema.dateFormat"
                          :id="`${id}Date`"
                          :name="`${name}-date`"
                          :config="dateOptions"
                          v-model="date"
                          :disabled="disabled"
                          :autocomplete="schema.autocomplete"
                         placeholder="Date..."
                          :min="schema.min"
                          :max="schema.max"
                          :required="schema.required"
                          @input="onInput"
                          :class="{'col-8' : !!(schema.timeFormat)}"
            ></date-picker>
            <date-picker v-if="schema.timeFormat"
                         :id="`${id}Time`"
                          :name="`${name}-time`"
                          :config="timeOptions"
                          v-model="time"
                          :disabled="disabled"
                          :autocomplete="schema.autocomplete"
                          placeholder="Time..."
                          :min="schema.min"
                          :max="schema.max"
                          :required="schema.required"
                          @input="onInput"
                         :class="{'col-4' : !!(schema.dateFormat)}"
            ></date-picker>
        </b-input-group>
        <b-input-group v-else>
            <date-picker v-if="schema.timeFormat"
                         :id="`${id}Time`"
                         :name="`${name}-time`"
                         :config="timeOptions"
                         v-model="time"
                         :disabled="disabled"
                         :autocomplete="schema.autocomplete"
                         placeholder="Time..."
                         :min="schema.min"
                         :max="schema.max"
                         :required="schema.required"
                         @input="onInput"
                         :class="{'col-4' : !!(schema.dateFormat)}"
            ></date-picker>
            <date-picker v-if="schema.dateFormat"
                         :id="`${id}Date`"
                         :name="`${name}-date`"
                         :config="dateOptions"
                         v-model="date"
                         :disabled="disabled"
                         :autocomplete="schema.autocomplete"
                         placeholder="Date..."
                         :min="schema.min"
                         :max="schema.max"
                         :required="schema.required"
                         @input="onInput"
                         :class="{'col-8' : !!(schema.timeFormat)}"
            ></date-picker>
        </b-input-group>
    </div>
</template>

<script>

    import moment from 'moment';

    // Import this component
    import datePicker from 'vue-bootstrap-datetimepicker';

    // Import date picker css
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-input",
        components: {
            datePicker
        },
        mixins: [
            abstractInput
        ],
        data () {
            return {
                date: null,
                dateOptions: {
                    format: this.schema.dateFormat,
                    useCurrent: true
                },
                time: null,
                timeOptions: {
                    format: this.schema.timeFormat,
                    useCurrent: true,
                    stepping: (this.schema.stepping !== undefined) ? this.schema.stepping : 5
                }
            }
        },
        created() {
            this.setDateTime(this.value);
        },
        methods: {
            setDateTime(value) {
                if (value) {
                    if (typeof(value) === "string") {
                        this.date = moment.utc(value).local();
                        this.time = moment.utc(value).local();
                    } else if (typeof(value) === "object" && value.date) {
                        this.date = moment.utc(value.date).local();
                        this.time = moment.utc(value.date).local();
                    }
                }
            },
            onInput() {
                let format = [];
                let date;
                let time;

                let now = moment();

                if (this.schema.dateFormat) {
                    date = moment(this.date, this.dateOptions.format);
                    date.hours(0);
                    date.minutes(0);
                    date.seconds(0);
                    format.push(this.dateOptions.format);
                }
                if (this.schema.timeFormat && this.time) {
                    time = moment(this.time, this.timeOptions.format);
                    if (date) {
                        date.hours(now.hours());
                        date.minutes(now.minutes());
                        date.seconds(0);
                    }
                    format.push(this.timeOptions.format);
                }

                if (date && time) {
                    date.hours(time.hours());
                    date.minutes(time.minutes());
                    date.seconds(0);
                } else if (time) {
                    date = time;
                }
                if (date && time) {
                    this.$emit('input', date.toISOString());
                } else {
                    this.$emit('input', date.format(this.dateOptions.format));
                }

            },
            setValue(value){
                this.setDateTime(value);
            }
        }

    };
</script>
