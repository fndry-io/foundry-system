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
            :days="options.days"
            :min-date="limit.min"
            :max-date="limit.max"
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
            :days="options.days"
            :min-date="limit.min"
            :max-date="limit.max"
        ></date-calendar>
        <div class="date-picker-buttons">
            <div class="button" v-if="!options.noButtons">
                <button type="button" class="btn btn-primary btn-block" @click="handleCancel"><span class="fa fa-close"></span></button>
            </div>
            <div class="button">
                <button type="button" class="btn btn-light btn-block" @click="handleReset"><span class="fa fa-trash"></span></button>
            </div>
            <div class="button" v-if="!options.noButtons">
                <button type="button" class="btn btn-success btn-block" @click="handleOk"><span class="fa fa-check"></span></button>
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
