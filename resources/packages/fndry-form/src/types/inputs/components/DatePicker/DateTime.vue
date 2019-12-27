<template>
    <div class="date-time">
        <div class="date-time-date">
            <div v-if="!noYear && !noDate" class="date-time-year">
                <date-time-range :value="date.years"
                                 input-label="Year"
                                   @next="() => $emit('modify', 1, 'years')"
                                   @prev="() => $emit('modify', -1, 'years')"
                                 @input="(value) => $emit('change', {years: value})"
                ></date-time-range>
            </div>
            <div v-if="!noDay && !noDate" class="date-time-month">
                <date-time-range :value="date.months + 1" :label="moment.format('MMM')"
                                 input-label="Month"
                                   @next="() => $emit('modify', 1, 'months')"
                                   @prev="() => $emit('modify', -1, 'months')"
                                 @input="(value) => $emit('change', {months: value - 1})"
                ></date-time-range>
            </div>
            <div v-if="!noDay && !noDate" class="date-time-day">
                <date-time-range :value="date.date" :label="moment.format('ddd')"
                                 input-label="Day"
                                   @next="() => $emit('modify', 1, 'days')"
                                   @prev="() => $emit('modify', -1, 'days')"
                                 @input="(value) => $emit('change', {days: value})"
                ></date-time-range>
            </div>
            <div v-if="!noHour && !noTime" class="date-time-hour">
                <date-time-range :value="date.hours" :label="moment.format('a')"
                                 input-label="Hour"
                                 @next="() => $emit('modify', 1, 'hours')"
                                   @prev="() => $emit('modify', -1, 'hours')"
                                 @input="(value) => $emit('change', {hours: value})"
                ></date-time-range>
            </div>
            <div v-if="!noMinute && !noTime" class="date-time-minute">
                <date-time-range :value="date.minutes" label="min"
                                 input-label="Minute"
                                 @next="() => $emit('modify', 1, 'minutes')"
                                   @prev="() => $emit('modify', -1, 'minutes')"
                                 @input="(value) => $emit('change', {minutes: value})"
                ></date-time-range>
            </div>
        </div>
    </div>
</template>

<script>

    import DateTimeRange from "./DateTimeRange";
    import {dateMixin} from "./mixin";

    export default {
        name: "DateTime",
        components: {
            DateTimeRange
        },
        mixins: [
            dateMixin
        ]
    }
</script>
