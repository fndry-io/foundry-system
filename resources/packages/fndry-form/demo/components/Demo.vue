<template>
    <div class="demo">
        <h2>demo</h2>
        <div class="container text-left">
            <ValidationObserver ref="observer" v-slot="{ invalid }" :slim="true">
                <form @submit.prevent="onSubmit">
                    <fndry-form-schema :schema="schema" :model="model" :errors="errors" @update="onUpdate"></fndry-form-schema>
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
    import {merge, set} from "lodash";
    import { ValidationObserver } from 'vee-validate';

    import {getInputValues} from '../../src/utils/schema';

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
                    children: [
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'repeat',
                                    name: 'repeat',
                                    //value: "DTSTART:20190917T120914Z\nRRULE:FREQ=MONTHLY;INTERVAL=5;COUNT=12;BYDAY=+3TU",
                                    required: false,
                                    label: 'Repeat'
                                }
                            ]
                        },
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
                                    type: 'file',
                                    name: 'file',
                                    action: '/api/system/files/upload',
                                    required: true,
                                    label: 'File Upload',
                                    multiple: false
                                },
                                {
                                    type: 'file',
                                    name: 'file_multiple',
                                    action: '/api/system/files/upload',
                                    deleteUrl: '/api/system/files/{_entity}/delete',
                                    value: [
                                        {
                                            id: 17,
                                            original_name: "Dulux_Pearlglo_WB.pdf"
                                        }
                                    ],
                                    required: true,
                                    label: 'File Upload (multiple)',
                                    multiple: true
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
                        },
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'text',
                                    multiline: 5,
                                    name: 'textaera',
                                    value: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed condimentum ullamcorper facilisis',
                                    label: 'Text Area'
                                }
                            ]
                        },
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'checkbox',
                                    name: 'checkbox',
                                    label: 'Checkbox',
                                    help: 'Make your selection'
                                },
                                {
                                    type: 'switch',
                                    name: 'switch',
                                    label: 'Switch',
                                    switch: true
                                },
                                {
                                    type: 'checkboxes',
                                    name: 'checkboxes',
                                    options: [
                                        {text: 'Red', value: 'red'},
                                        {text: 'Blue', value: 'blue'},
                                        {text: 'Green', value: 'green'}
                                    ],
                                    label: 'Checkboxes',
                                    inline: false
                                }
                            ]
                        },
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'radio',
                                    name: 'radios',
                                    options: [
                                        {text: 'Red', value: 'red'},
                                        {text: 'Blue', value: 'blue'},
                                        {text: 'Green', value: 'green'}
                                    ],
                                    label: 'Radios',
                                    inline: false
                                }
                            ]
                        },
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'select',
                                    name: 'select',
                                    options: [
                                        {text: 'Red', value: 'red'},
                                        {text: 'Blue', value: 'blue'},
                                        {text: 'Green', value: 'green'}
                                    ],
                                    label: 'Select One',
                                    multiple: false
                                },
                                {
                                    type: 'select',
                                    name: 'select-only-one',
                                    options: [
                                        {text: 'Red', value: 'red'},
                                        {text: 'Blue', value: 'blue'},
                                        {text: 'Green', value: 'green'}
                                    ],
                                    label: 'Select One',
                                    multiple: false,
                                    required: true,
                                    searchable: true
                                }
                            ]
                        },
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'select',
                                    name: 'select-multiple',
                                    options: [
                                        {text: 'Red', value: 'red'},
                                        {text: 'Blue', value: 'blue'},
                                        {text: 'Green', value: 'green'}
                                    ],
                                    required: true,
                                    label: 'Select Multiple',
                                    multiple: true,
                                    searchable: true
                                },
                                {
                                    type: 'select',
                                    name: 'select-multiple-max',
                                    help: 'Select with required and max selection',
                                    // value: [
                                    //     'red', 'blue'
                                    // ],
                                    options: [
                                        {label: 'Red', id: 'red'},
                                        {label: 'Blue', id: 'blue'},
                                        {label: 'Green', id: 'green'},
                                        {label: 'Black', id: 'black'},
                                        {label: 'White', id: 'white'},
                                        {label: 'Purple', id: 'purple'},
                                        {label: 'Orange', id: 'orange'},
                                        {label: 'Brown', id: 'brown'}
                                    ],
                                    rules: 'required',
                                    required: true,
                                    label: 'Select Multiple',
                                    multiple: true,
                                    searchable: true,
                                    textKey: 'label',
                                    valueKey: 'id',
                                    max: 3,
                                    url: '/api/system/pick-lists/13/list',
                                },
                                {
                                    type: 'select',
                                    name: 'select-taggable',
                                    help: 'Taggable with local search ablity',
                                    options: [
                                        {label: 'Red', id: 'red'},
                                        {label: 'Blue', id: 'blue'},
                                        {label: 'Green', id: 'green'},
                                        {label: 'Black', id: 'black'},
                                        {label: 'White', id: 'white'},
                                        {label: 'Purple', id: 'purple'},
                                        {label: 'Orange', id: 'orange'},
                                        {label: 'Brown', id: 'brown'}
                                    ],
                                    rules: 'required',
                                    required: true,
                                    label: 'Select Multiple',
                                    multiple: true,
                                    taggable: true,
                                    searchable: true,
                                    textKey: 'label',
                                    valueKey: 'id',
                                    taggableUrl: '/api/system/pick-lists/13/items/add',
                                    max: 3
                                },
                                {
                                    type: 'select',
                                    name: 'select-taggable-search',
                                    help: 'Taggable with ajax search ablity',
                                    options: [],
                                    rules: 'required',
                                    required: true,
                                    label: 'Select Multiple',
                                    multiple: true,
                                    taggable: true,
                                    searchable: true,
                                    textKey: 'label',
                                    valueKey: 'id',
                                    url: '/api/system/pick-lists/13/list',
                                    taggableUrl: '/api/system/pick-lists/13/items/add',
                                    max: 3
                                }
                            ]
                        },
                        {
                            type: 'section',
                            title: 'Section Title',
                            description: 'A description about the section',
                            children: [
                                {
                                    type: 'row',
                                    children: [
                                        {
                                            type: 'date',
                                            name: 'date',
                                            value: '2018-09-01',
                                            required: true,
                                            label: 'Date',
                                            format: 'YYYY-MM-DD',
                                        },
                                        {
                                            type: 'datetime',
                                            name: 'datetime',
                                            value: '2018-11-21 17:33',
                                            required: true,
                                            label: 'Date time',
                                            format: 'YYYY-MM-DD HH:mm a',
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'column',
                                    children: [
                                        {
                                            type: 'tag',
                                            tag: 'h1',
                                            content: 'Title'
                                        },
                                        {
                                            type: 'tag',
                                            tag: 'div',
                                            content: '<h2>Sub Title</h2><p>Paragraph of Text</p>'
                                        }
                                    ]
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
            getInputValues(this.schema, model);
            this.model = extend({}, model);
        },
        methods: {
            onUpdate(model) {
                this.model = merge({}, model);
            },
            async onSubmit () {
                const isValid = await this.$refs.observer.validate();
                if (!isValid) {
                    console.log('not valid');
                } else {
                    console.log('valid');
                }
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
