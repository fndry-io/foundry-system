<template>
    <div class="date-calender-range">
        <div v-if="!noButtons" class="range-up">
            <button type="button" class="btn btn-default btn-block btn-sm" @click="$emit('prev')"><span class="fa fa-caret-left"></span></button>
        </div>
        <div class="range-value">
            <div>{{label}}</div>
        </div>
        <div v-if="!noButtons" class="range-down">
            <button type="button" class="btn btn-default btn-block btn-sm" @click="$emit('next')"><span class="fa fa-caret-right"></span></button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DateCalendarRange",
        props: {
            value: {
                required: true
            },
            label: [String, Number],
            noButtons: Boolean
        }
    }
</script>
