<template>
    <b-dropdown right variant="input" :class="{
        'date-picker-dropdown': true,
        'form-control': true,
        'is-valid': state === true,
        'is-invalid': state === false
    }" lazy :disabled="schema.disabled">
        <template v-slot:button-content>
            <span>{{inputFormatted}}&nbsp;</span>
        </template>
        <template v-slot="{hide}">
            <b-dropdown-form>
                <date-picker :value="date"
                             @change="(value) => {handlePickerInput(value, hide)}"
                             :options="options"
                             :format="dateFormat"
                             :required="schema.required"
                ></date-picker>
            </b-dropdown-form>
        </template>
    </b-dropdown>
</template>

<script>

    import {merge} from 'lodash';
    import moment from 'moment';

    import abstractInput from '../abstractInput';

    import DatePicker from "./components/DatePicker";

    export default {
        name: "FndryFieldDate",
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
                    autoUpdate: true,
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
                } else {
                    this.date = null;
                    this.inputFormatted = null;
                }
            },
            handlePickerInput(value, callback) {
                this.date = value;
                if (this.date !== null && this.date !== undefined) {
                    this.inputFormatted = this.date.format(this.inputMaskFormat);
                } else {
                    this.inputFormatted = "";
                }
                this.onInput();
                if (callback) {
                    callback();
                }
            },
            onInput(){
                if (this.date) {
                    this.$emit('input', this.date.format(this.dateFormat));
                } else {
                    this.$emit('input', null);
                }
            },
            setValue(value){
                this.setDateTime(value);
            }
        },
        watch: {
            value: function(newVal, oldVal){
                if (newVal !== oldVal) {
                    this.setDateTime(newVal);
                }
            }
        }
    };
</script>

