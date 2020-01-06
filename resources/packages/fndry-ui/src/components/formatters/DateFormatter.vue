<template>
    <span :title="formatted">{{ (this.timeago) ? since : formatted }}</span>
</template>

<script>

    import moment from 'moment';
    import {isObject, isNumber} from 'lodash';

    export default {
        name: 'DateFormatter',
        props: {
            value: {
                type: [String, Object, Number],
                required: true,
            },
            format: {
                type: String,
                default() {
                    return 'dddd, MMMM Do YYYY, h:mm:ss a'
                }
            },
            source: {
                type: String,
                default() {
                    return 'YYYY-MM-DD HH:mm:ss.SSSSSS'
                }
            },
            timeago: {
                type: Boolean
            }
        },
        computed: {
            formatted: function() {
                return this.date.format(this.format);
            },
            since: function() {
                return this.date.fromNow();
            },
            date: function(){
                if (isNumber(this.value)) {
                    return moment.unix(this.value).utc();
                } else {
                    return moment.utc((isObject && this.value.date) ? this.value.date : this.value, this.source)
                }
            }
        }
    }

</script>
