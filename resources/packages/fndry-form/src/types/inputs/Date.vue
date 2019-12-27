<template>
    <div>
        <b-input-group>
            <b-dropdown right variant="input" class="date-picker-dropdown input-dropdown">
                <template v-slot:button-content>
                    <span>{{inputFormatted}}&nbsp;</span>
                </template>
                <b-dropdown-form>
                    <date-picker :value="date" @input="handlePickerInput" :options="options" :format="dateFormat"></date-picker>
                </b-dropdown-form>
            </b-dropdown>
        </b-input-group>

<!--        <div class="input-group">-->
<!--            <b-input :id="`${id}Date`"-->
<!--                     :name="`${name}-date`"-->
<!--                     :disabled="disabled"-->
<!--                     :autocomplete="schema.autocomplete"-->
<!--                     :min="schema.min"-->
<!--                     :max="schema.max"-->
<!--                     :required="schema.required"-->
<!--                     @input="onInput"-->
<!--            ></b-input>-->
<!--            <div class="input-group-append">-->
<!--                <span class="input-group-button">-->
<!--                    <b-dropdown id="dropdown-right" right variant="primary" class="m-2">-->
<!--                        <template v-slot.button-content>-->
<!--                            <span class="fa-calendar"></span>-->
<!--                        </template>-->
<!--                        <b-dropdown-item href="#">Action</b-dropdown-item>-->
<!--                        <b-dropdown-item href="#">Another action</b-dropdown-item>-->
<!--                        <b-dropdown-item href="#">Something else here</b-dropdown-item>-->
<!--                    </b-dropdown>-->
<!--                </span>-->


<!--            </div>-->
<!--        </div>-->
<!--        <b-input-group v-if="!schema.invert">-->
<!--            <date-picker v-if="schema.dateFormat"-->
<!--                          :id="`${id}Date`"-->
<!--                          :name="`${name}-date`"-->
<!--                          :config="dateOptions"-->
<!--                          v-model="date"-->
<!--                          :disabled="disabled"-->
<!--                          :autocomplete="schema.autocomplete"-->
<!--                         placeholder="Date..."-->
<!--                          :min="schema.min"-->
<!--                          :max="schema.max"-->
<!--                          :required="schema.required"-->
<!--                          @input="onInput"-->
<!--                          :class="{'col-8' : !!(schema.timeFormat)}"-->
<!--            ></date-picker>-->
<!--            <date-picker v-if="schema.timeFormat"-->
<!--                         :id="`${id}Time`"-->
<!--                          :name="`${name}-time`"-->
<!--                          :config="timeOptions"-->
<!--                          v-model="time"-->
<!--                          :disabled="disabled"-->
<!--                          :autocomplete="schema.autocomplete"-->
<!--                          placeholder="Time..."-->
<!--                          :min="schema.min"-->
<!--                          :max="schema.max"-->
<!--                          :required="schema.required"-->
<!--                          @input="onInput"-->
<!--                         :class="{'col-4' : !!(schema.dateFormat)}"-->
<!--            ></date-picker>-->
<!--        </b-input-group>-->
<!--        <b-input-group v-else>-->
<!--            <date-picker v-if="schema.timeFormat"-->
<!--                         :id="`${id}Time`"-->
<!--                         :name="`${name}-time`"-->
<!--                         :config="timeOptions"-->
<!--                         v-model="time"-->
<!--                         :disabled="disabled"-->
<!--                         :autocomplete="schema.autocomplete"-->
<!--                         placeholder="Time..."-->
<!--                         :min="schema.min"-->
<!--                         :max="schema.max"-->
<!--                         :required="schema.required"-->
<!--                         @input="onInput"-->
<!--                         :class="{'col-4' : !!(schema.dateFormat)}"-->
<!--            ></date-picker>-->
<!--            <date-picker v-if="schema.dateFormat"-->
<!--                         :id="`${id}Date`"-->
<!--                         :name="`${name}-date`"-->
<!--                         :config="dateOptions"-->
<!--                         v-model="date"-->
<!--                         :disabled="disabled"-->
<!--                         :autocomplete="schema.autocomplete"-->
<!--                         placeholder="Date..."-->
<!--                         :min="schema.min"-->
<!--                         :max="schema.max"-->
<!--                         :required="schema.required"-->
<!--                         @input="onInput"-->
<!--                         :class="{'col-8' : !!(schema.timeFormat)}"-->
<!--            ></date-picker>-->
<!--        </b-input-group>-->
    </div>
</template>

<script>

    import {merge} from 'lodash';
    import moment from 'moment';

    // Import this component
    //import datePicker from 'vue-bootstrap-datetimepicker';

    // Import date picker css
    //import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

    import abstractInput from '../abstractInput';

    import DatePicker from "./components/DatePicker";

    export default {
        name: "fndry-field-input",
        components: {
            DatePicker
        },
        mixins: [
            abstractInput
        ],
        data () {
            return {
                inputFormatted: null,
                date: null,
                options: merge({}, {
                    noButtons: true,
                    noDate: false,
                    noTime: false
                }, this.schema.pickerOptions)
            }
        },
        computed: {
            //Allows us to format the displayed input value
            inputMask: function(){
                return this.schema.mask;
            },
            inputMaskLength: function(){
                return (this.inputMask.match(new RegExp("0#", "g")) || []).length;
            },
            //Allows us to format the input into a moment
            inputMaskFormat: function(){
                return this.schema.maskFormat;
            },
            //allows us to convert a date into a valid iso date time string for the field value
            dateFormat: function(){
                return this.schema.dateFormat
            }
        },
        created() {
            this.setDateTime(this.value);
        },
        methods: {
            setDateTime(value) {
                if (value) {
                    let date;
                    if (typeof(value) === "string") {
                        date = value;
                    } else if (typeof(value) === "object" && value.date) {
                        date = value.date;
                    }
                    this.date = moment(value, this.dateFormat);
                    this.inputFormatted = this.date.format(this.inputMaskFormat);
                }
            },
            handlePickerInput(value) {
                this.date = value;
                this.inputFormatted = this.date.format(this.inputMaskFormat);
                this.onInput();
            },
            onInput(){
                this.$emit('input', this.date.format(this.dateFormat));
            },
            setValue(value){
                this.setDateTime(value);
            }
        }
    };
</script>

