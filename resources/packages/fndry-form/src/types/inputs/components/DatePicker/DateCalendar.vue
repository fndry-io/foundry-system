<template>
    <div class="date-calendar">
        <div class="date-calendar-ranges">
            <div class="date-calendar-year">
                <date-calendar-range :value="calendarDate.years" :label="calendarMoment.format('Y')"
                                        @next="() => modify(1, 'years')"
                                        @prev="() => modify(-1, 'years')"
                ></date-calendar-range>
            </div>
            <div class="date-calendar-month">
                <date-calendar-range :value="calendarDate.months + 1" :label="calendarMoment.format('MMM')"
                                     @next="() => modify(1, 'months')"
                                     @prev="() => modify(-1, 'months')"
                ></date-calendar-range>
            </div>
        </div>
        <div class="date-calendar-days">
            <div v-for="day in header">{{day}}</div>
        </div>
        <div class="date-calendar-dates">
            <div v-for="dateObj in dates" :class="{
                'active': dateObj.active,
                'selectable': dateObj.selectable,
                'current-month': dateObj.currentMonth,
                'previous-month': !dateObj.currentMonth
            }"><a v-if="dateObj.selectable" href="#" class="day" @click.prevent="() => $emit('change', dateObj)">{{dateObj.date}}</a><span v-else class="day">{{dateObj.date}}</span></div>
        </div>
        <div v-if="!noTime" class="date-calendar-ranges date-calendar-time">
<!--            <div class="date-calendar-block">-->
<!--                <span class="fa fa-clock-o"></span>-->
<!--            </div>-->
            <div class="date-calendar-hour">
                <date-calendar-range :value="calendarDate.years" :label="moment.format('HH')"
                                     @next="() => $emit('modify', 1, 'hours')"
                                     @prev="() => $emit('modify', -1, 'hours')"
                ></date-calendar-range>
            </div>
            <div class="date-calendar-minute">
                <date-calendar-range :value="date.minutes" :label="date.minutes"
                                     @next="() => $emit('modify', 1, 'minutes')"
                                     @prev="() => $emit('modify', -1, 'minutes')"
                ></date-calendar-range>
            </div>
            <div class="date-calendar-block">
                <span>{{moment.format('a')}}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import Moment from 'moment';
    import DateCalendarRange from "./DateCalendarRange";
    import {extendMoment} from 'moment-range';
    import {dateMixin} from "./mixin";

    const moment = extendMoment(Moment);

    export default {
        name: "DateCalendar",
        components: {DateCalendarRange},
        mixins: [
            dateMixin
        ],
        data(){
            return {
                header: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
                range: null,
                calendarMoment: null,
                calendarDate: {
                    years: null,
                    months: null,
                    days: null
                }
            };
        },
        created(){
            this.setMoment();
            this.setRange();
        },
        methods: {
            setMoment(){
                this.calendarMoment = this.moment.clone();
                this.calendarDate = this.calendarMoment.toObject();
            },
            setRange(){
                let start = moment(this.calendarMoment).startOf('month').startOf('day').day(0);
                let end = moment(this.calendarMoment).endOf('month').startOf('day').day(6);
                this.range = moment.range(start, end);
            },
            modify(quantity, prop){
                this.calendarMoment.add(quantity, prop);
                this.calendarDate = this.calendarMoment.toObject();
                this.setRange();
            }
        },
        computed: {
            dates: function(){
                let range = [];

                const days = Array.from(this.range.by('day'));

                for (let day of days) {
                    let {date, months, years} = day.toObject();
                    let active = day.isSame(this.moment, 'date');
                    let currentMonth = day.isSame(this.calendarMoment, 'month');

                    //determine if it is selectable
                    let selectable = true;
                    if (this.days && this.days.length) {
                        selectable = false;
                        let found = this.days.find((dayOfWeek) => {
                            return day.isoWeekday() === dayOfWeek;
                        });
                        if (found !== undefined) {
                            selectable = true;
                        }
                    }
                    if (this.validDates && this.validDates.indexOf(day.format('YYYY-MM-DD')) === -1) {
                        selectable = false;
                    }
                    if (selectable && !this.isDateInRange(day)) {
                        selectable = false;
                    }

                    range.push({date, months, years, active, currentMonth, selectable});
                }
                return range;
            }
        }
    }
</script>

