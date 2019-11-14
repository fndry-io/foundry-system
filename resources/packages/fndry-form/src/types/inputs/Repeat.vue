<template>
    <div>
        <div class="mb-3">
            <b-form-select :value="config" :options="templateOptions" @change="onPresetChosen">
            </b-form-select>
            <b-form-text v-if="text">Repeat {{text}}</b-form-text>
        </div>

        <div v-if="config === 'custom'">
            <b-form-group
                    label-cols-sm="3"
                    label="Every"
                    label-for="interval"
            >
                <b-input-group>
                    <input name="interval" :value="model.interval" type="text" class="form-control" size="2" @change="onIntervalChange" @blur="onBlur">
                    <b-form-select v-model="model.freq" :options="frequencies" @change="onFreqChange" @blur="onBlur"></b-form-select>
                </b-input-group>
            </b-form-group>

            <b-form-group v-if="intervalOptions.length > 0"
                          id="repeatGroup"
                          label="On"
                          class="repeat-group"
                          label-cols-sm="3"
                          label-for="repeat"

            >
                <b-form-select v-if="model.freq === rrule('MONTHLY')"
                               :value="intervalMonthOption"
                               :options="intervalOptions"
                               name="repeat"
                               @change="handleIntervalMonthChange"
                               @blur="onBlur"
                ></b-form-select>
                <b-form-checkbox-group v-if="model.freq === rrule('WEEKLY')"
                                       :checked="intervalWeekOption"
                                       :options="intervalOptions"
                                       name="repeat"
                                       class="day-of-week"
                                       @blur="onBlur"
                                       @change="handleIntervalWeekChange"
                                       :required="true"
                ></b-form-checkbox-group>
            </b-form-group>

        </div>

        <b-form-group
            id="ending-group"
            label="Ending"
            label-cols-sm="3"
            label-for="end"
        >
            <b-input-group>
                <b-form-select :value="ends" :options="endOptions" @blur="onBlur" @change="handleEnds"></b-form-select>
                <date v-if="ends === 'on'" id="endsOn" :schema="endsOnDateSchema" :value="endsOn" style="width: 50%" @input="handleEndsOn" @change="handleEndsOn" @blur="onBlur"></date>
                <b-form-input v-if="ends === 'after'" :value="endsAfter" type="number" :disabled="ends !== 'after'" min="0" style="width: 33%" @input="handleEndsAfter" @blur="onBlur"></b-form-input>
                <b-input-group-append v-if="ends === 'after'" is-text><span class="fa fa-redo"></span></b-input-group-append>
            </b-input-group>
        </b-form-group>

    </div>
</template>

<script>
    import {forEach, merge, find, get, isObject} from 'lodash';
    import moment from 'moment';

    import { RRule, RRuleSet, rrulestr } from 'rrule';

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

    const frequencies = {
        day: {
            value: RRule.DAILY,
            label: 'Day(s)'
        },
        week: {
            value: RRule.WEEKLY,
            label: 'Week(s)',
            options: [
                {
                    label: 'Monday',
                    shortValue: 'MO',
                    value: 0,
                },
                {
                    label: 'Tuesday',
                    shortValue: 'TU',
                    value: 1,
                },
                {
                    label: 'Wednesday',
                    shortValue: 'WE',
                    value: 2,
                },
                {
                    label: 'Thursday',
                    shortValue: 'TH',
                    value: 3,
                },
                {
                    label: 'Friday',
                    shortValue: 'FR',
                    value: 4,
                },
                {
                    label: 'Saturday',
                    shortValue: 'SA',
                    value: 5,
                },
                {
                    label: 'Sunday',
                    shortValue: 'SU',
                    value: 6,
                }
            ]
        },
        month: {
            value: RRule.MONTHLY,
            label: 'Month(s)',
            options: [
                {
                    label: 'Day of Month',
                    value: 'day'
                },
                {
                    label: 'Day of Week',
                    value: 'week'
                }
            ]
        },
        year: {
            value: RRule.YEARLY,
            label: 'Yearly'
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

    const base = {
        freq: null,
        dtstart: null,
        interval: null,
        wkst: null,
        count: null,
        until: null,
        tzid: null,
        bysetpos: null,
        bymonth: null,
        bymonthday: null,
        byyearday: null,
        byweekno: null,
        byweekday: null,
        byhour: null,
        byminute: null,
        bysecond: null,
        byeaster: null
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

            let freq = [];
            forEach(frequencies, (interval) => {
                freq.push({
                    text: interval.label,
                    value: interval.value
                })
            });

            let endOptions = [];
            forEach(ends, (option) => {
                endOptions.push(option)
            });

            return {
                config: (this.schema.default) ? this.schema.default : 'monthly',
                rule: null,
                rvalue: null,
                text: null,
                date: null,
                dateInput: null,
                intervalOptions: [],
                intervalWeekOption: null,
                intervalMonthOption: null,
                ends: 'never',
                endsAfter: null,
                endsOn: null,
                model: merge({}, base),
                templateOptions: [],
                frequencies: freq,
                endOptions: endOptions,
                endsOnDateSchema: {
                    type: 'date',
                    placeholder: 'Date',
                    dateFormat: 'YYYY-MM-DD'
                },
            }
        },
        created() {
            this.init();
            if (this.value) {
                this.config = 'custom';
                this.convertFromRRule(this.value);
                this.onFreqChange();
            }
        },
        methods: {
            init() {
                let date;
                if (this.schema.dateInputName) {
                    this.dateInput = get(this.rootModel, this.schema.dateInputName, null);
                    if (this.dateInput) {
                        date = moment.utc(this.dateInput).local();
                    }
                }

                if (!date) {
                    date = moment();
                }

                this.date = date;
                this.model.dtstart = date.toDate();
                this.model = merge({}, this.model);
                this.setOptions();

                //copy this so we can set it to the newly set one after the date was changed
                let ends = {
                    ends: this.ends,
                    endsOn: this.endsOn,
                    endsAfter: this.endsAfter
                };

                if (this.config === 'custom') {
                    this.onFreqChange();
                } else {
                    this.onPresetChosen(this.config, false);
                }

                if (ends.ends !== 'never') {
                    this.ends = ends.ends;
                    this.endsOn = ends.endsOn;
                    this.endsAfter = ends.endsAfter;
                    this.handleEnds(this.ends);
                }

            },
            setValue(value){
                if (value !== this.rvalue) {
                    this.convertFromRRule(value);
                    this.config = 'custom'
                }
            },
            onInput(){
                this.convertToRRule(this.model);
                let value = RRule.optionsToString(this.model);
                if (value !== this.value) {
                    this.$emit('input', value);
                }
            },
            onChange(){
                let value = RRule.optionsToString(this.model);
                if (value !== this.value) {
                    this.$emit('change', value);
                }
            },
            onBlur(){
                this.$emit('blur');
            },

            onPresetChosen(value, fireOnInput = true) {
                this.config = value;
                let selected = find(this.templateOptions, (option) => option.value === value);
                if (selected && selected.rule) {
                    this.convertFromRRule(selected.rule);
                    if (fireOnInput) this.onInput();
                }
            },
            handleEnds(value){
                this.ends = value;
                if (value === 'never') {
                    delete this.model.count;
                    delete this.model.until;
                    this.model = merge({}, this.model);
                    this.onInput();
                } else if (this.endsOn && value === 'on') {
                    this.handleEndsOn(this.endsOn);
                } else if (this.endsAfter && value === 'after') {
                    this.handleEndsAfter(this.endsAfter);
                }
            },
            handleEndsOn(value) {
                this.model = merge({}, this.model, {count: null, until: moment.utc(value).toDate()});
                this.endsOn = value;
                this.onInput();
            },
            handleEndsAfter(value){
                this.model = merge({}, this.model, {count: value, until: null});
                this.endsAfter = value;
                this.onInput();
            },
            onIntervalChange(evt){
                this.model = merge({}, this.model, {interval: evt.target.value});
                this.onInput();
            },
            onFreqChange(val, fireOnInput = true){
                delete this.model.bysetpos;
                delete this.model.byweekday;
                delete this.model.bymonthday;

                if (this.model.freq === RRule.WEEKLY) {
                    this.intervalOptions = this.getWeeklyOptions();
                } else if (this.model.freq === RRule.MONTHLY) {
                    this.intervalOptions = this.getMonthlyOptions();
                } else {
                    this.intervalOptions = [];
                }

                this.model = merge({}, this.model);
                if (fireOnInput) this.onInput();
            },
            getWeeklyOptions(selected = undefined){
                let options = [];
                forEach(frequencies.week.options, (option) => {
                    options.push({
                        text: option.label.substr(0, 2).toUpperCase(),
                        value: option.value
                    })
                });
                let day = this.date.isoWeekday() - 1;
                let weekDay = find(frequencies.week.options, (option) => option.value === day);

                if (selected !== false) {
                    delete this.model.byweekday;
                    delete this.model.bymonthday;
                    if (selected) {
                        this.intervalWeekOption = selected;
                    } else {
                        this.intervalWeekOption = [weekDay.value];
                    }
                    this.model = merge({}, this.model, {byweekday: this.intervalWeekOption});
                    this.onInput();
                }

                return options;
            },
            getMonthlyOptions(selected = undefined){

                let day = this.date.isoWeekday() - 1;
                let weekDay = find(frequencies.week.options, (option) => option.value === day);

                let weekDayText = weekDay.label;
                let weekDayTextShort = weekDay.shortValue;

                let weekCount = Math.floor(this.date.date() / 7);
                let dayWeekOfMonth = counts[weekCount];

                let dayOption = {
                    bymonthday: this.date.date()
                };

                if (selected !== false) {
                    delete this.model.byweekday;
                    delete this.model.bymonthday;
                    if (selected) {
                        this.intervalMonthOption = selected;
                    } else {
                        this.intervalMonthOption = dayOption;
                    }

                    this.model = merge({}, this.model, this.intervalMonthOption);
                }

                return [
                    {
                        text: `Day ${this.date.date()} of the Month`,
                        value: dayOption
                    },
                    {
                        text: `The ${dayWeekOfMonth} ${weekDayText} of the Month`,
                        value: {
                            byweekday: [RRule[weekDayTextShort].nth(weekCount + 1)]
                        }
                    },
                    {
                        text: `The first monday of the Month`,
                        value: {
                            byweekday: [RRule['MO']]
                        }
                    },
                    {
                        text: `The last ${weekDayText} of the Month`,
                        value: {
                            byweekday: [RRule[weekDayTextShort].nth(-1)]
                        }
                    }
                ];
            },
            handleIntervalMonthChange(value) {
                delete this.model.byweekday;
                delete this.model.bymonthday;
                this.intervalMonthOption = value;
                this.model = merge({}, this.model, value);
                this.onInput();
            },
            handleIntervalWeekChange(value) {
                delete this.model.byweekday;
                delete this.model.bymonthday;

                if (value.length > 0) {
                    this.intervalWeekOption = value;
                }
                this.model = merge({}, this.model, {byweekday: value});
                this.onInput();
            },
            convertToRRule(model){
                this.rule = new RRule(model);
                this.text = this.rule.toText();
                this.rvalue = this.rule.toString();
            },
            convertFromRRule(rfcString){

                let model = RRule.parseString(rfcString);

                this.date = moment.utc(model.dtstart).local();

                if (model.freq === RRule.WEEKLY) {

                    let byweekday = [];
                    forEach(model.byweekday, (day) => {
                        byweekday.push(day.weekday);
                    });

                    this.intervalOptions = this.getWeeklyOptions(byweekday);

                } else if (model.freq === RRule.MONTHLY) {

                    let intervalMonthOption = null;

                    if (model.bymonthday) {
                        intervalMonthOption = {
                            bymonthday: model.bymonthday
                        };
                    } else if (model.byweekday) {
                        intervalMonthOption = {
                            byweekday: model.byweekday
                        };
                    }

                    this.intervalOptions = this.getMonthlyOptions(intervalMonthOption);

                } else {
                    this.intervalOptions = [];
                }

                if (model.count) {
                    this.ends = 'after';
                    this.endsAfter = model.count;
                } else if (model.until) {
                    this.ends = 'on';
                    this.endsOn = moment(model.until).toISOString();
                } else {
                    this.ends = 'never';
                }

                this.model = merge({}, model);
                this.convertToRRule(this.model);
            },
            rrule(CONSTANT){
                return RRule[CONSTANT];
            },
            setOptions() {
                let day = this.date.isoWeekday() - 1;
                let monthDay = this.date.date();
                let month = this.date.month();
                let dayText = frequencies.week.options[day];
                let monthText = months[month];
                let weekDay = find(frequencies.week.options, (option) => option.value === day);
                let weekCount = Math.floor(this.date.date() / 7);
                let dayWeekOfMonth = counts[weekCount];

                let dateString = this.date.utc().format('YYYYMMDDTHHmmss');
                const dtstart = `DTSTART:${dateString}\nRRULE:`;

                function replace(text, replacements) {
                    replacements.forEach((replacement) => {
                        text = text.replace(replacement.placeholder, replacement.value);
                    });
                    return text;
                }

                //replacements
                let replacements = [
                    {
                        placeholder: '%WEEKDAY%',
                        value: dayText.shortValue
                    },
                    {
                        placeholder: '%MONTHDAY%',
                        value: monthDay
                    },
                    {
                        placeholder: '%DAY%',
                        value: weekCount + 1
                    }
                ];

                let defaults = [];

                let options = [];

                if (this.schema.options) {
                    defaults = this.schema.options;
                } else {
                    defaults = [
                        {
                            value: 'daily',
                            rule: `FREQ=DAILY;INTERVAL=1`
                        },
                        {
                            value: 'weekly',
                            rule: replace(`FREQ=WEEKLY;INTERVAL=1;BYDAY=%WEEKDAY%`, replacements)
                        },
                        {
                            value: 'monthlybyday',
                            rule: replace(`FREQ=MONTHLY;INTERVAL=1;BYDAY=%DAY%%WEEKDAY%`, replacements)
                        },
                        {
                            value: 'monthly',
                            rule: `FREQ=MONTHLY;INTERVAL=1`
                        },
                        {
                            value: 'yearly',
                            rule: `FREQ=YEARLY;INTERVAL=1`
                        },
                        {
                            value: 'weekday',
                            text: `Every weekday (Monday to Friday)`,
                            rule: `FREQ=WEEKLY;INTERVAL=1;BYDAY=MO,TU,WE,TH,FR`
                        }
                    ];
                }

                defaults.forEach((_option) => {
                    let option = merge({}, _option, {
                        rule: dtstart + replace(_option.rule, replacements)
                    });
                    if (!option.hasOwnProperty('text')) {
                        option.text = RRule.fromString(dtstart + option.rule).toText();
                    }
                    options.push(option);
                });

                options.push({
                    text: `Custom`,
                    value: 'custom',
                    rule: ""
                });

                this.templateOptions = [...options];
            }
        },
        watch: {
            rootModel: {
                deep: true,
                handler(newVal, oldVal){
                    if (this.schema.dateInputName) {
                        let newDate = get(newVal, this.schema.dateInputName, null);
                        if (newDate !== this.dateInput) {
                            this.init();
                            this.onInput();
                        }
                    }

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
