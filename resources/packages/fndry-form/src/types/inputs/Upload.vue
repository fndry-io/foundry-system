<template>
    <div class="file-uploader">
        <b-form-file
                :id="id"
                :name="name"
                :state="model.length > 0"
                placeholder="Choose a file..."
                drop-placeholder="Drop file here..."
                :accept="getAcceptAttributes()"
                :multiple="schema.multiple"
                :placeholder="schema.placeholder"
                :disabled="disabled"
                :readonly="schema.readonly"
                :required="schema.required"
                ref="file"
                @change="handleFileUpload"
        ></b-form-file>

        <div v-if="model.length > 0"
             v-for="(file, index) in model"
             class="file-attached"
        >
            <div class="row">
                <div class="col flex-grow-1">
                    <div class="file-name">{{file.original_name}}</div>
                </div>
                <div class="col flex-grow-0 text-right">
                    <fndry-request-button v-if="schema.deleteUrl" size="sm"  variant="danger" :request="schema.deleteUrl" :params="{_entity: file.id}" type="confirm" :confirm-options="{message: 'Are you sure you want to remove this file?'}" @success="(response) => removeModelFile(index)"><span class="fa fa-trash"></span></fndry-request-button>
                </div>
            </div>
        </div>

        <div v-if="files.length > 0">
            <div v-for="(file, index) in files"
                 class="file-progress"
            >
                <div class="row">
                    <div class="col flex-grow-1">
                        <div class="file-name">{{file.file.name}}</div>
                    </div>
                    <div class="col flex-grow-0 text-right">
                        <b-progress
                                v-if="file.uploading"
                                :value="file.progress"
                                :max="100"
                                :animated="file.uploading"
                                :striped="file.uploading"
                                :variant="(file.failed) ? 'danger' : ((file.uploaded) ? 'success' : 'primary')"
                        ></b-progress>
                        <fndry-request-button v-if="schema.deleteUrl && file.uploaded" size="sm" variant="danger" :request="schema.deleteUrl" :params="{_entity: file.id}" type="confirm" :confirm-options="{message: 'Are you sure you want to remove this file?'}" @success="(response) => removeUploadFile(index)"><span class="fa fa-trash"></span></fndry-request-button>
                    </div>
                </div>
                <div class="invalid-feedback" v-if="file.failed">
                    {{file.error}}
                </div>
            </div>
        </div>
    </div>
</template>


<script>

    import {forEach, map, isArray, isEmpty, find} from 'lodash';

    import abstractInput from '../abstractInput';

    export default {
        name: "fndry-field-upload",
        mixins: [
            abstractInput
        ],
        data(){
            return{
                model: (isEmpty(this.value)) ? [] : (isArray(this.value) ? this.value : [this.value]),
                uploading: false,
                uploaded: false,
                failed: false,
                files: []
            }
        },
        methods: {
            handleFileUpload(evt){

                this.onInput();

                forEach(evt.target.files, (file) => {
                    let length = this.files.push({
                        progress: 0,
                        uploading: true,
                        uploaded: false,
                        file
                    });
                    let index = length - 1;

                    this.$fndryApiService.upload(this.schema.action, file,
                        (progressEvent) => {
                            this.files[index].progress = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                        })
                        .then((response) => {
                            this.files[index].id = response.data.id;
                            this.files[index].uploading = false;
                            this.files[index].uploaded = true;
                            this.files[index].failed = false;
                        })
                        .catch((response) => {
                            this.files[index].id = null;
                            this.files[index].progress = 0;
                            this.files[index].uploading = false;
                            this.files[index].failed = true;
                            this.files[index].error = (response.error) ? response.error : 'Unable to upload file';
                        })
                        .finally(() => {
                            this.onChange();
                        })
                    ;
                });
            },
            removeUploadFile(index) {
                this.files.splice(index, 1);
                this.onChange();
            },
            removeModelFile(index) {
                this.model.splice(index, 1);
                this.onChange();
            },
            onInput(){
                this.$emit('input', this.getValue());
            },
            onChange(){
                this.$emit('change', this.getValue());
            },
            getValue(){
                let value = null;
                if (this.schema.multiple) {
                    value = [];
                    forEach(this.model, (file) => {
                        if (file.id) {
                            value.push(file.id);
                        }
                    });
                    forEach(this.files, (file) => {
                        if (file.id && find(value, (v) => v === file.id ) === undefined) {
                            value.push(file.id);
                        }
                    });
                } else if (this.files[0]) {
                    value = this.files[0].id;
                }
                return value;
            },
            getAcceptAttributes(){

                let rules = this.schema.rules? this.schema.rules.split('|'): [];
                let mime = null;

                for(let key in rules){
                    if(rules.hasOwnProperty(key)){
                        if(rules[key].indexOf('mimes:') > -1){
                            if(mime){
                                mime = rules[key].split(':');
                            }
                        }
                    }
                }

                if(mime){
                    return mime[1];
                }else{
                    return '';
                }

            }
        }
    };
</script>

<style>

    .file-uploader .file-progress,
    .file-uploader .file-attached {
        margin: 10px 0;
    }
    .file-uploader .file-progress .invalid-feedback {
        display: block;
    }
</style>
