<template>
    <span :title="formatted">{{ (this.timeago) ? since : formatted }}</span>
</template>

<script>

    import moment from 'moment';
    import {isObject, isNumber} from 'lodash';

    export default {
        name: 'FormatDate',
        props: {
            value: {
                type: [String, Object, Number],
                required: false,
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
                return (this.date) ? this.date.format(this.format) : null;
            },
            since: function() {
                return (this.date) ? this.date.fromNow() : null;
            },
            date: function(){
                if (this.value) {
                    if (isNumber(this.value)) {
                        return moment.unix(this.value).utc().local();
                    } else {
                        return moment.utc((isObject && this.value.date) ? this.value.date : this.value, this.source).local();
                    }
                }
            }
        }
    }

</script>
