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
        noPeriod: Boolean,
        days: Array,
        minDate: [String,Object],
        maxDate: [String,Object],
        validDates: Array
    },
    methods: {
        isDateInRange(date, prop){
            if (this.minDate && !date.isSameOrAfter(this.minDate, prop)) {
                return false;
            }
            if (this.maxDate && !date.isSameOrBefore(this.maxDate, prop)) {
                return false;
            }
            return true;
        }
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
        required: Boolean,
        minDate: String,
        maxDate: String,
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
                    noPeriod: false,
                    days: null
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
            range: null,
            limit: {
                min: (this.minDate) ? moment(this.minDate, moment.ISO_8601) : undefined,
                max: (this.maxDate) ? moment(this.maxDate, moment.ISO_8601) : undefined
            }
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
        onChange(){
            this.$emit('change', this.value);
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
            this.setDate(this.moment.toObject());
        },
        setDate(dateObj){
            let date = merge({}, this.date, dateObj);
            let _moment = moment(date);
            if (this.isDateInRange(_moment)) {
                this.moment = _moment;
                this.date = this.moment.toObject();
                if (this.options.autoUpdate) {
                    this.handleOk();
                }
            }
        },
        handleCancel(){
            this.$emit('cancel');
        },
        handleReset(){
            this.$emit('change', null);
        },
        handleOk(){
            this.$emit('change', this.moment);
        },
        isDateInRange(date, prop){
            if (this.limit.min && !date.isSameOrAfter(this.limit.min, prop)) {
                return false;
            }
            if (this.limit.max && !date.isSameOrBefore(this.limit.max, prop)) {
                return false;
            }
            return true;
        }
    }
};
