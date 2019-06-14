<template>
    <div class="demo">
        <h2>demo</h2>
        <div class="container">
            <form-state :state="state" @submit.prevent="onSubmit">
                <form-schema :schema="schema" :model="model" @model-update="onModelUpdate"></form-schema>
                <button type="submit">Submit</button>
            </form-state>

            <pre>{{ model }}</pre>
            <pre>{{ state }}</pre>
        </div>
    </div>
</template>

<script>
    import FndryForm from "../../src";
    import extend from "extend";
    import {set} from "lodash";

    export default {
        name: 'demo',
        mixins: {
            FndryForm
        },
        data: function() {
            return {
                schema: {
                    type: 'form',
                    action: 'http://www.google.com',
                    method: 'POST',
                    inputs: [
                        {
                            type: 'text',
                            name: 'name.first_name',
                            value: 'bob',
                            required: true,
                            rules: [
                                'required'
                            ]
                        },
                        {
                            type: 'text',
                            name: 'name.last_name',
                            value: 'smith',
                            required: true,
                            rules: [
                                'required'
                            ]
                        },
                        {
                            type: 'email',
                            name: 'email',
                            value: 'bob@domain.com',
                            required: true,
                            rules: [
                                'required',
                                'email'
                            ]
                        }
                    ],
                    children: [
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'text',
                                    name: 'name.first_name',
                                    value: 'bob',
                                    required: true
                                },
                                {
                                    type: 'text',
                                    name: 'name.last_name',
                                    value: 'smith',
                                    required: true
                                }
                            ]
                        },
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'email',
                                    name: 'email',
                                    value: 'bob@domain.com',
                                    required: true,
                                    rules: [
                                        'required',
                                        'email'
                                    ]
                                }
                            ]
                        }
                    ]
                },
                model: {},
                state: {}
            }
        },
        created(){
            let model = {};
            this.schema.inputs.forEach((input) => {
                set(model, input.name, input.value);
            });
            this.model = extend({}, model);
        },
        methods: {
            onModelUpdate(model) {
                this.model = model;
            },
            onSubmit(){
                if(this.state.$invalid) {
                    // alert user and exit early
                    console.log('invalid');
                } else {
                    // otherwise submit form
                    console.log('valid');
                }
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
