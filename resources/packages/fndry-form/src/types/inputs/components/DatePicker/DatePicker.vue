<template>
    <div class="date-picker">
        <date-time
            v-if="options.mode === 'range'"
            :moment="moment"
            :date="date"
            @change="setDate"
            @modify="modify"
            :no-date="options.noDate"
            :no-time="options.noTime"
            :no-hour="options.noHour"
            :no-minute="options.noMinute"
            :no-year="options.noYear"
            :no-month="options.noMonth"
            :no-day="options.noDay"
            :no-period="options.noPeriod"
        ></date-time>
        <date-calendar
            v-else
            :moment="moment"
            :date="date"
            @change="setDate"
            @modify="modify"
            :no-date="options.noDate"
            :no-time="options.noTime"
            :no-hour="options.noHour"
            :no-minute="options.noMinute"
            :no-year="options.noYear"
            :no-month="options.noMonth"
            :no-day="options.noDay"
            :no-period="options.noPeriod"
        ></date-calendar>
        <div v-if="!options.noButtons" class="date-picker-buttons">
            <div class="button">
                <button type="button" class="btn btn-primary btn-block" @click="reset"><span class="fa fa-close"></span></button>
            </div>
            <div class="button">
                <button type="button" class="btn btn-primary btn-block" @click="reload"><span class="fa fa-refresh"></span></button>
            </div>
            <div class="button">
                <button type="button" class="btn btn-primary btn-block" @click="accept"><span class="fa fa-check"></span></button>
            </div>
        </div>
    </div>
</template>

<script>

    import DateTimePickerRange from "./DateTimeRange";
    import {datePickerMixin} from "./mixin";
    import DateCalendar from "./DateCalendar";
    import DateTime from "./DateTime";

    export default {
        name: "DatePicker",
        components: {DateTime, DateCalendar, DateTimePickerRange},
        mixins: [
            datePickerMixin
        ]
    }
</script>
