import moment from 'moment';
import {merge, round, isEmpty} from 'lodash';


export const dateMixin = {
    props: {
        moment: moment,
        date: Object,
        noDate: Boolean,
        noTime: Boolean,
        noHour: Boolean,
        noMinute: Boolean,
        noDay: Boolean,
        noMonth: Boolean,
        noYear: Boolean,
        noPeriod: Boolean
    }
};

function RoundTo(number, roundto){
    return roundto * Math.round(number / roundto);
}

export const datePickerMixin = {
    props: {
        mode: {
            type: String
        },
        value: {
            required: false
        },
        format: {
            type: String,
            default(){
                return 'YYYY-MM-DDTHH:mmZ';
            }
        },
        options: {
            type: Object,
            default() {
                return {
                    mode: 'calendar',
                    autoUpdate: true,
                    noButtons: false,
                    noDate: false,
                    noTime: false,
                    noHour: false,
                    noMinute: false,
                    noDay: false,
                    noMonth: false,
                    noYear: false,
                    noPeriod: false
                };
            },
        }
    },
    data(){
        return {
            date: {
                years: null,
                months: null,
                days: null,
                hours: 0,
                minutes: 0,
                seconds: 0,
                milliseconds: 0
            },
            moment: null,
            range: null
        }
    },
    computed: {
        interval: function(){
            if (this.options.hasOwnProperty('interval')) {
                return this.options.interval;
            } else {
                return null;
            }
        }
    },
    created(){
        this.setMoment(this.value);
    },
    methods: {
        onInput(){
            this.$emit('input', this.value);
        },
        setMoment(value){
            if (!isEmpty(value)) {
                this.moment = moment(value, this.format);
            } else {
                this.moment = moment();
                if (this.interval && this.interval.minutes) {
                    let rounded = RoundTo(this.moment.minutes(), this.interval.minutes);
                    this.moment.minute(rounded);
                }
            }
            this.date = this.moment.toObject();
        },
        modify(quantity, prop){
            let number = 1;
            if (this.interval && this.interval.hasOwnProperty(prop)) {
                number = this.interval[prop] * quantity;
            } else {
                number = number * quantity;
            }
            this.moment.add(number, prop);
            this.date = this.moment.toObject();
            this.moment = moment(this.date);
            console.log(this.options.autoUpdate);
            if (this.options.autoUpdate) {
                this.handleOk();
            }
        },
        setDate(dateObj){
            this.date = merge({}, this.date, dateObj);
            this.moment = moment(this.date);
            console.log(this.options.autoUpdate);
            if (this.options.autoUpdate) {
                this.handleOk();
            }
        },
        handleCancel(){
            this.$emit('cancel');
        },
        handleReset(){
            this.$emit('input', null);
        },
        handleOk(){
            this.$emit('input', this.moment);
        }
    }
};
