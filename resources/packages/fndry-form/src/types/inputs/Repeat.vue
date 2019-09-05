<template>
    <div>
        <b-input-group class="mb-3">
            <b-form-select :value="config" :options="options" @change="onPresetChosen">
            </b-form-select>
        </b-input-group>

        <div v-if="config === 'custom'">
            <b-form-group
                    label-cols-sm="3"
                    label="Every"
                    label-for="frequency"
            >
                <b-input-group>
                    <input name="frequency" v-model="model.frequency" type="text" class="form-control" size="2" @blur="onBlur">
                    <b-form-select v-model="model.interval" :options="intervals" @change="onIntervalChange" @blur="onBlur"></b-form-select>
                </b-input-group>
            </b-form-group>

            <b-form-group v-if="intervalOptions.length > 0"
                          id="repeatGroup"
                          label="On"
                          class="repeat-group"
                          label-cols-sm="3"
                          label-for="repeat"

            >
                <b-form-select v-if="model.interval === 'month'"
                               v-model="model.intervalMonth"
                               :options="intervalOptions"
                               name="repeat"
                               @change="handleChange"
                               @blur="onBlur"
                ></b-form-select>
                <b-form-checkbox-group v-if="model.interval === 'week'"
                                       v-model="model.intervalWeek"
                                       :options="intervalOptions"
                                       name="repeat"
                                       class="day-of-week"
                                       @blur="onBlur"
                ></b-form-checkbox-group>
            </b-form-group>

            <b-form-group
                    id="ending-group"
                    label="Ending"
                    label-cols-sm="3"
                    label-for="end"
            >
                <b-input-group>
                    <b-form-select v-model="model.ends" :options="endOptions" @blur="onBlur"></b-form-select>
                    <date v-if="model.ends === 'on'" id="endsOn" :schema="endsOnDateSchema" :value="model.endsOn" style="width: 50%" @input="handleEndsOn" @change="handleEndsOn" @blur="onBlur"></date>
                    <b-form-input v-if="model.ends === 'after'" v-model="model.endsAfter" type="number" :disabled="model.ends !== 'after'" min="0" style="width: 33%" @blur="onBlur"></b-form-input>
                    <b-input-group-append v-if="model.ends === 'after'" is-text><span class="fa fa-redo"></span></b-input-group-append>
                </b-input-group>
            </b-form-group>
        </div>

    </div>
</template>

<script>
    import {forEach, merge, find, get} from 'lodash';
    import moment from 'moment';

    import abstractInput from '../abstractInput';
    import DatePicker from "vue-bootstrap-datetimepicker/src/component";
    import Date from "./Date";

    const counts = [
        '1st',
        '2nd',
        '3rd',
        '4th',
        '5th'
    ];

    const months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    const intervals = {
        day: {
            label: 'Day'
        },
        week: {
            label: 'Week',
            options: {
                1: 'Monday',
                2: 'Tuesday',
                3: 'Wednesday',
                4: 'Thursday',
                5: 'Friday',
                6: 'Saturday',
                7: 'Sunday'
            }
        },
        month: {
            label: 'Month',
            options: [
                {
                    text: 'Day of Month',
                    value: 'day'
                },
                {
                    text: 'Day of Week',
                    value: 'week'
                }
            ]
        },
        year: {
            label: 'Year'
        }
    };

    const ends = {
        never: {
            text: 'Never',
            value: 'never',
        },
        on: {
            text: 'On',
            value: 'on',
        },
        after: {
            text: 'After',
            value: 'after',
        }
    };

    const daily = {
        config: 'daily',
        frequency: 1,
        interval: 'day',
        intervalWeek: null,
        intervalMonth: null,
        ends: 'never',
        endsAfter: 1,
        endsOn: null,
    };

    export default {
        name: "repeat-input",
        components: {
            Date,
            DatePicker

        },
        mixins: [
            abstractInput
        ],
        data () {
            return {
                config: 'daily',
                date: null,
                dateInput: null,
                intervalOptions: [],
                model: merge({}, {
                    config: 'daily',
                    frequency: 1,
                    interval: 'day',
                    intervalWeek: null,
                    intervalMonth: null,
                    ends: 'never',
                    endsAfter: 1,
                    endsOn: null,
                }, this.value)
            }
        },
        created() {
            let date;
            if (this.schema.dateInputName) {
                this.dateInput = get(this.rootModel, this.schema.dateInputName, null);
                date = moment.utc(this.dateInput).local();
            }
            if (!date) {
                date = moment();
            }
            this.date = date;

            if (this.value === null) {
                this.onIntervalChange('daily');
                this.onChange();
            } else {
                this.config = this.value.config;
                this.onIntervalChange(this.value.config);
            }

        },
        methods: {
            onInput(){
                this.$emit('input', this.model);
            },
            onChange(){
                this.$emit('change', this.model);
            },
            onBlur(){
                this.$emit('blur');
            },
            onPresetChosen(value) {
                this.config = value;
                let selected = find(this.options, (option) => option.value === value);
                if (selected && selected.config) {
                    this.model = merge({}, this.model, selected.config);
                }
                this.onIntervalChange();
            },
            handleEndsOn(value) {
                this.model = merge({}, this.model, {endsOn: value});
            },
            handleChange() {
                this.model = merge({}, this.model);
            },
            onIntervalChange(){
                if (this.model.interval === 'week') {
                    this.intervalOptions = this.getWeeklyOptions();
                } else if (this.model.interval === 'month') {
                    this.intervalOptions = this.getMonthlyOptions();
                } else {
                    this.intervalOptions = [];
                }
                this.handleChange();
            },
            getWeeklyOptions(){
                let options = [];
                forEach(intervals.week.options, (text, value) => {
                    options.push({
                        text: text.substr(0, 2),
                        value
                    })
                });
                return options;
            },
            getMonthlyOptions(){

                let day = this.date.isoWeekday();
                let dayText = intervals.week.options[day];
                let weekCount = Math.floor(this.date.date() / 7);
                let dayWeekOfMonth = counts[weekCount];

                return [
                    {
                        text: `Day ${this.date.date()}`,
                        value: 'date'
                    },
                    {
                        text: `The ${dayWeekOfMonth} ${dayText}`,
                        value: 'day'
                    }
                ];
            }
        },
        watch: {
            model: function(){
                this.onInput();
            }
        },
        computed: {
            options: function() {

                let day = this.date.isoWeekday();
                let month = this.date.month();
                let dayText = intervals.week.options[day];
                let monthText = months[month];
                let weekCount = Math.floor(this.date.date() / 7);
                let dayWeekOfMonth = counts[weekCount];

                return [
                    {
                        value: 'daily',
                        text: 'Daily',
                        config: {
                            config: 'daily',
                            frequency: 1,
                            interval: 'day',
                            ends: 'never'
                        }
                    },
                    {
                        value: 'weekly',
                        text: `Weekly on ${dayText}`,
                        config: {
                            config: 'weekly',
                            frequency: 1,
                            interval: 'week',
                            intervalWeek: [day],
                            ends: 'never',
                        }
                    },
                    {
                        value: 'monthly',
                        text: `Monthly on the ${dayWeekOfMonth} ${dayText}`,
                        config: {
                            config: 'monthly',
                            frequency: 1,
                            interval: 'month',
                            intervalMonth: 'day',
                            ends: 'never',
                        }
                    },
                    {
                        value: 'yearly',
                        text: `Annually on ${this.date.date()} ${monthText}`,
                        config: {
                            config: 'yearly',
                            frequency: 1,
                            interval: 'year',
                            intervalMonth: 'date',
                            ends: 'never',
                        }
                    },
                    {
                        value: 'weekday',
                        text: `Every weekday (Monday to Friday)`,
                        config: {
                            config: 'weekday',
                            frequency: 1,
                            interval: 'week',
                            intervalWeek: [1,2,3,4,5],
                            ends: 'never',
                        }
                    },
                    {
                        text: `Custom`,
                        value: 'custom',
                        config: {
                            config: 'custom'
                        }
                    }
                ];
            },
            types: function() {
                return types;
            },
            intervals: function() {
                let options = [];
                forEach(intervals, (interval, value) => {
                    options.push({
                        text: interval.label,
                        value
                    })
                });
                return options;
            },
            endOptions: function() {
                let options = [];
                forEach(ends, (option, value) => {
                    options.push(option)
                });
                return options;
            },
            endsOnDateSchema: function(){
                return {
                    type: 'date',
                    placeholder: 'Date',
                    dateFormat: 'DD/MM/YYYY'
                }
            }
        },

    };
</script>


<style lang="scss">

    .repeat-group {
        .day-of-week .custom-control {
            width: 25% !important;
        }
    }
    .ending-group {
        .ending-option {
            width: 50% !important;
        }
    }
</style>