<template>
    <div class="demo">
        <h2>demo</h2>
        <div class="container-fluid text-left">
            <div class="row">
                <div class="col-8">
                    <ValidationObserver ref="observer" v-slot="{ invalid }" :slim="true">
                        <form @submit.prevent="onSubmit">
                            <fndry-form-schema :schema="schema" :model="model" :errors="errors" @update="onUpdate"></fndry-form-schema>
                            <button type="submit">Submit</button>
                        </form>
                    </ValidationObserver>
                </div>
                <div class="col-4">
                    <h3>Model</h3>
                    <pre>{{ model }}</pre>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import FndryForm from "../../src";
    import extend from "extend";
    import {merge, set} from "lodash";
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
                    values: {
                        name: {
                            first_name: 'bob',
                            last_name: 'smith'
                        },
                        email: 'bob@domain.com',
                        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed condimentum ullamcorper facilisis',

                        image: [
                            {
                                id: 488,
                                created_at: "2019-10-22 14:47:47",
                                is_public: true,
                                original_name: "600x600.jpg",
                                size: 15.18,
                                type: "image/jpeg",
                                updated_at: "2019-10-22 14:47:47",
                                url: "http://localhost/storage/5mcofenK9baHm0F6e7dJuxFAZYj0qTWvyRQUkDdx.jpeg",
                                uuid: "a7955dcd-1e2e-48c7-8f4b-dd639d5e4947"
                            },
                            {
                                id: 488,
                                created_at: "2019-10-22 14:47:47",
                                is_public: true,
                                original_name: "600x600.jpg",
                                size: 15.18,
                                type: "image/jpeg",
                                updated_at: "2019-10-22 14:47:47",
                                url: "http://localhost/storage/5mcofenK9baHm0F6e7dJuxFAZYj0qTWvyRQUkDdx.jpeg",
                                uuid: "a7955dcd-1e2e-48c7-8f4b-dd639d5e4947"
                            },
                            {
                                id: 488,
                                created_at: "2019-10-22 14:47:47",
                                is_public: true,
                                original_name: "600x600.jpg",
                                size: 15.18,
                                type: "image/jpeg",
                                updated_at: "2019-10-22 14:47:47",
                                url: "http://localhost/storage/5mcofenK9baHm0F6e7dJuxFAZYj0qTWvyRQUkDdx.jpeg",
                                uuid: "a7955dcd-1e2e-48c7-8f4b-dd639d5e4947"
                            },
                            {
                                id: 488,
                                created_at: "2019-10-22 14:47:47",
                                is_public: true,
                                original_name: "600x600.jpg",
                                size: 15.18,
                                type: "image/jpeg",
                                updated_at: "2019-10-22 14:47:47",
                                url: "http://localhost/storage/5mcofenK9baHm0F6e7dJuxFAZYj0qTWvyRQUkDdx.jpeg",
                                uuid: "a7955dcd-1e2e-48c7-8f4b-dd639d5e4947"
                            }
                        ],

                        file_multiple: [
                            {
                                id: 17,
                                original_name: "Dulux_Pearlglo_WB.pdf"
                            }
                        ],
                        'select-multiple-max': [
                            'red', 'blue'
                        ],
                        date: '2019-11-26',
                        daterange: '2019-11-30',
                        datetime: '2019-11-21 17:33',
                        datetimerange: '2018-11-21 17:33',
                        timerange: null,
                        repeat: "DTSTART:20191126T000000Z\nRRULE:FREQ=MONTHLY;COUNT=3;INTERVAL=1;BYMONTHDAY=26"
                    },
                    children: [
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'image',
                                    name: 'image',
                                    action: '/api/system/files/upload/image',
                                    required: true,
                                    label: 'Image Upload',
                                    multiple: true,
                                    layout: 'thumbnails',
                                    deleteUrl: '/api/system/files/{_entity}/delete',
                                },
                                // {
                                //     type: 'image',
                                //     name: 'image_multiple',
                                //     action: '/api/system/files/upload/image',
                                //     deleteUrl: '/api/system/files/{_entity}/delete',
                                //     required: true,
                                //     label: 'Image Upload (multiple)',
                                //     multiple: true
                                // }
                            ]
                        },
                        {
                            type: 'section',
                            title: 'Date Time',
                            children: [
                                {
                                    type: 'row',
                                    children: [
                                        {
                                            type: 'column',
                                            children: [
                                                {
                                                    type: 'date',
                                                    name: 'date',
                                                    required: true,
                                                    label: 'Date Picker (Calendar)',
                                                    mask: '####-##-##',
                                                    maskFormat: 'YYYY-MM-DD',
                                                    dateFormat: 'YYYY-MM-DD',
                                                    min: '2019-11-01T00:00:00',
                                                    max: '2020-03-31T23:59:59',
                                                    pickerOptions: {
                                                        mode: 'calendar',
                                                        noTime: true,
                                                        days: [4]
                                                    }
                                                },
                                                {
                                                    type: 'date',
                                                    name: 'daterange',
                                                    required: true,
                                                    label: 'Date Picker (Range)',
                                                    mask: '####-##-##',
                                                    maskFormat: 'YYYY-MM-DD',
                                                    dateFormat: 'YYYY-MM-DD',
                                                    //disabled: true,
                                                    pickerOptions: {
                                                        mode: 'range',
                                                        noTime: true
                                                    }
                                                }
                                            ]
                                        },
                                        {
                                            type: 'column',
                                            children: [
                                                {
                                                    type: 'datetime',
                                                    name: 'datetime',
                                                    required: true,
                                                    label: 'Date time',
                                                    mask: '####-##-## ##:##',
                                                    maskFormat: 'YYYY-MM-DD HH:mm',
                                                    dateFormat: 'YYYY-MM-DDTHH:mm:ssZZ',
                                                    min: '2019-11-01T00:00:00',
                                                    max: '2020-03-31T23:59:59',
                                                    pickerOptions: {
                                                        mode: 'calendar'
                                                    }
                                                },
                                                {
                                                    type: 'datetime',
                                                    name: 'datetimerange',
                                                    required: true,
                                                    label: 'Date time',
                                                    mask: '####-##-## ##:##',
                                                    maskFormat: 'YYYY-MM-DD HH:mm',
                                                    dateFormat: 'YYYY-MM-DDTHH:mm:ssZZ',
                                                    pickerOptions: {
                                                        mode: 'range'
                                                    }
                                                },
                                                {
                                                    type: 'datetime',
                                                    name: 'timerange',
                                                    required: true,
                                                    label: 'Date time',
                                                    mask: '##:##',
                                                    maskFormat: 'HH:mm',
                                                    dateFormat: 'YYYY-MM-DDTHH:mm:ssZZ',
                                                    pickerOptions: {
                                                        mode: 'range',
                                                        noDate: true,
                                                        interval: {
                                                            minutes: 5
                                                        }
                                                    }
                                                }
                                            ]
                                        },
                                        {
                                            type: 'repeat',
                                            name: 'repeat',
                                            required: false,
                                            label: 'Repeat',
                                            dateInputName: 'date',
                                            options: [
                                                {
                                                    rule: "FREQ=MONTHLY;COUNT=3;INTERVAL=1;BYMONTHDAY=%MONTHDAY%",
                                                    value: 'month3',
                                                    text: "Monthly for 3 months",
                                                },
                                                {
                                                    rule: "FREQ=MONTHLY;COUNT=6;INTERVAL=1;BYMONTHDAY=%MONTHDAY%",
                                                    value: 'month6',
                                                    text: "Monthly for 6 months",
                                                },
                                                {
                                                    rule: "FREQ=MONTHLY;COUNT=12;INTERVAL=1;BYMONTHDAY=%MONTHDAY%" ,
                                                    value: 'month12',
                                                    text: "Monthly for 12 months",
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            type: 'row',
                            children: [
                                {
                                    type: 'text',
                                    name: 'name.first_name',
                                    required: true,
                                    label: 'First Name'
                                },
                                {
                                    type: 'text',
                                    name: 'name.last_name',
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
                                }
                            ]
                        },
                        {
                            type: 'row',
                            children: [
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
                                    help: 'Simple Taggable',
                                    options: [],
                                    rules: 'required',
                                    required: true,
                                    label: 'Select Multiple',
                                    multiple: true,
                                    taggable: true,
                                    searchable: true,
                                    textKey: 'label',
                                    valueKey: 'id',
                                    max: 3,
                                    placeholder: 'Select or add new ones',
                                    searchText: 'Type to search or add...'
                                },
                                {
                                    type: 'select',
                                    name: 'select-taggable-search-add',
                                    help: 'Taggable with ajax search and add ablity',
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
            let model = merge({}, this.schema.values);
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
