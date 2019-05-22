<template>
    <div class="form-group">
        <input
                :invalid="isInvalid()"
                :required="schema.required"
                :readonly="schema.readonly"
                :placeholder="schema.placeholder"
                :name="getFieldName(schema)"
                :value="date"
                :id="getFieldID(schema)"
                ref="datePicker"
                type='text'
                class="form-control"/>
    </div>
</template>

<script>
import abstractField from "../abstractField";
import { debounce, isFunction, isNumber, isObject, isString } from "lodash";
import moment from "moment";

export default {
	mixins: [abstractField],
	computed: {
        formatMoment() {
            if(this.schema && this.schema.type === "datetime") {
                return 'MM/DD/YYYY hh:mm A';
            }else
                return 'MM/DD/YYYY';
        },
        format(){
            if(this.schema && this.schema.type === "datetime") {
                return 'mm/dd/yyyy HH:ii P';
            }else
                return 'mm/dd/yyyy';
        }
	},
    data(){
	    return {
	        date: null
        }
    },
	methods: {
		onInput($event) {
		    if(this.schema.type === "datetime") {
                this.value = moment($event.date).utc().format(this.formatMoment);
            } else {
                this.value = moment($event.date).format(this.formatMoment);
            }
		},
        formatValueToField(value){

		    if (value && isString(value)){
		        return value;
            } else if(value && isObject(value)) {
                return moment(value.date).format(this.formatMoment);
            } else {
		        if(value)
                    return moment(value).format(this.formatMoment);
		        else
		            return null;
            }

        }
	},

	mounted() {

	    let picker = $(this.$refs.datePicker);

        if(this.value){

            if(this.schema.type === "datetime") {
                this.date = moment.utc(this.value).local().format(this.formatMoment);
            } else {
                this.date = moment(this.value).format(this.formatMoment);
            }
        }

        let options = {
            Default: moment.locale(),
            autoclose: true,
            clearBtn: true
        };

        if(this.schema.max){
            options.endDate = moment(this.schema.max, 'YYYY-MM-DD').format(this.formatMoment)
        }

        options.format = this.format;

	    let that = this;

	    if(this.schema.type === 'datetime'){
            $(picker).datetimepicker(options)
                    .on('changeDate', function(e) {
                       that.onInput(e);
                    });
        } else {
	        $(picker).datepicker(options)
                    .on('changeDate', function(e) {
                        that.onInput(e);
                    });
        }
	},
};
</script>
