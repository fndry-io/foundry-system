<template>
    <div class="demo">
        <h2>demo</h2>
        <div class="container text-left">
            <ValidationObserver ref="observer" v-slot="{ invalid }" :slim="true">
                <form @submit.prevent="onSubmit">
                    <fndry-form-schema :schema="schema" :model="model" :errors="errors"></fndry-form-schema>
                    <button type="submit">Submit</button>
                </form>
            </ValidationObserver>

            <pre>{{ model }}</pre>
        </div>
    </div>
</template>

<script>
    import FndryForm from "../../src";
    import extend from "extend";
    import {set} from "lodash";
    import { ValidationObserver } from 'vee-validate';

    export default {
        name: 'demo',
        components: {
            ValidationObserver
        },
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
                                    required: true,
                                    label: 'First Name'
                                },
                                {
                                    type: 'text',
                                    name: 'name.last_name',
                                    value: 'smith',
                                    required: true,
                                    label: 'Last Name',
                                    rules: 'required'
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
                                    label: 'Email',
                                    placeholder: 'Your personal email...',
                                    rules: 'required|email',
                                    help: 'Add your email to get help'
                                }
                            ]
                        }
                    ]
                },
                model: {},
                state: {},
                errors: {
                    'name.last_name': [
                        'This field must be supplied'
                    ]
                }
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
            async onSubmit () {
                const isValid = await this.$refs.observer.validate();
                if (!isValid) {
                    console.log('not valid');
                } else {
                    console.log('valid');
                }
                // 🐿 ship it
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
