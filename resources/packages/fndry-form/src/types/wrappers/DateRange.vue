<template>
    <div class="date-range">
        <div class="row">
            <div class="col-md-6">
                <fndry-form-type v-if="startAt"
                                 :schema="startAt"
                                 :model="model"
                                 :errors="errors"
                                 :on="$listeners"
                                 @input="onInput"
                                 @blur="onBlur"
                                 @focus="onFocus"
                ></fndry-form-type>
            </div>
            <div class="col-md-6">
                <fndry-form-type v-if="endAt"
                                 :schema="endAt"
                                 :model="model"
                                 :errors="errors"
                                 :on="$listeners"
                                 @input="onInput"
                                 @blur="onBlur"
                                 @focus="onFocus"
                                 :key="key"
                ></fndry-form-type>
            </div>
        </div>
    </div>
</template>

<script>

    import {merge, find, forEach, get} from 'lodash';

    import moment from 'moment';

    import abstractWrapper from '../abstractWrapper'

    export default {
        name: "date-range",
        mixins: [
            abstractWrapper
        ],
        data() {
            return {
                startAt: find(this.schema.children, (child) => child.name === this.schema.start),
                endAt: find(this.schema.children, (child) => child.name === this.schema.end),
                key: 0,
                options: []
            };
        },
        created() {
            let startAt = get(this.model, this.startAt.name, null);
            let endTime = get(this.model, this.endAt.name, null);
            if (startAt !== null && endTime === null) {
                this.onInput(this.startAt.name, startAt);
            }
        },
        methods: {
            onInput(field, value) {
                if (field === this.schema.start) {
                    if (value) {
                        let date = moment(value, moment.ISO_8601);
                        let endTime = get(this.model, this.endAt.name, null);
                        let startAt = get(this.model, this.startAt.name, null);

                        if (endTime === null) {
                            date.add(1, 'hours');
                        } else {
                            let start = moment(endTime, moment.ISO_8601);
                            let end = moment(startAt, moment.ISO_8601);
                            let minutes = start.diff(end, 'minutes');

                            if (minutes > 0) {
                                date.add(minutes, 'minutes');
                            } else {
                                date.add(1, 'hours');
                            }
                        }

                        this.$emit('input', this.endAt.name, date.toISOString());
                        this.key++;
                    }
                }
                this.$emit('input', field, value);
            },

            onBlur(field) {
                this.$emit('blur', field);
            },

            onFocus(field) {
                this.$emit('focus', field);
            },
        }
    };
</script>
