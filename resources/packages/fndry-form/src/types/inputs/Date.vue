<template>
    <div>
        <date-picker :id="id"
                     :name="name"
                     v-model="model"
                     :config="options"
                     :placeholder="schema.placeholder"
                     :disabled="disabled"
                     :autocomplete="schema.autocomplete"
                     :min="schema.min"
                     :max="schema.max"
                     :required="schema.required"
                     v-on="$listeners"
        ></date-picker>
    </div>
</template>

<script>
    // Import this component
    import datePicker from 'vue-bootstrap-datetimepicker';

    // Import date picker css
    //import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-input",
        components: {
            datePicker
        },
        mixins: [
            abstractInput
        ],
        data () {
            return {
                date: new Date(),
                options: {
                    format: this.schema.format,
                    useCurrent: false,
                }
            }
        }

    };
</script>
