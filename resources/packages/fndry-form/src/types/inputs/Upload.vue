<template>
    <div class="file-uploader">

        <div v-if="files.length > 0"
             v-for="(file, index) in files"
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

        <div v-if="upload.length > 0">
            <div v-for="file in upload"
                 v-if="file.uploading || file.failed"
                 class="file-progress"
            >
                <div class="d-flex flex-row">
                    <div class="flex-grow-1">
                        <div class="file-name">{{file.file.name}}</div>
                    </div>
                    <div class="flex-grow-0 text-right">
                        <b-progress
                                v-if="file.uploading"
                                :value="file.progress"
                                :max="100"
                                :animated="file.uploading"
                                :striped="file.uploading"
                                :variant="(file.failed) ? 'danger' : ((file.uploaded) ? 'success' : 'primary')"
                                style="min-width: 100px;"
                        ></b-progress>
                    </div>
                </div>
                <div class="invalid-feedback" v-if="file.failed">
                    <span v-if="file.validation" v-for="error in file.validation" style="display: block;">{{error}}</span>
                    <span v-else>{{file.error}}</span>
                </div>
            </div>
        </div>

        <b-form-file
                :id="id"
                :name="name"
                :state="state"
                placeholder="Choose a file..."
                drop-placeholder="Drop file here..."
                :accept="getAcceptAttributes()"
                :multiple="schema.multiple"
                :placeholder="placeholder"
                :disabled="!uploadable"
                :readonly="schema.readonly"
                :required="schema.required"
                :value="model"
                ref="file"
                @change="handleFileUpload"
                :file-name-formatter="formatNames"
                no-drop
        ></b-form-file>
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
                files: (isEmpty(this.value)) ? [] : (isArray(this.value) ? this.value : [this.value]),
                upload: [],
                model: null,
                uploadable: !this.disabled,
                placeholder: this.schema.placeholder
            }
        },
        methods: {
            formatNames() {
                if (this.files.length) {
                    return `${this.files.length} files uploaded`
                } else {
                    return this.schema.placeholder
                }
            },
            handleFileUpload(evt){

                forEach(evt.target.files, (file) => {
                    let length = this.upload.push({
                        progress: 0,
                        uploading: true,
                        uploaded: false,
                        file
                    });
                    let index = length - 1;

                    this.$fndryApiService.upload(this.$fndryApiService.getHandleUrl(this.schema.action, this.schema.params), file,
                        (progressEvent) => {
                            this.upload[index].progress = Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total );
                        })
                        .then((response) => {
                            this.upload[index].progress = 100;
                            this.upload[index].uploading = false;
                            this.upload[index].uploaded = true;
                            this.upload[index].failed = false;
                            this.files.push({
                                id: response.data.id,
                                original_name: file.name,
                                file
                            });
                        })
                        .catch((response) => {
                            this.upload[index].id = null;
                            this.upload[index].progress = 0;
                            this.upload[index].uploading = false;
                            this.upload[index].failed = true;
                            this.upload[index].error = (response.error) ? response.error : 'Unable to upload file';
                            this.upload[index].validation = [];
                            if (response.code === 422 && response.data) {
                                forEach(response.data, (errors) => {
                                    forEach(errors, (error) => {
                                        this.upload[index].validation.push(error);
                                    });
                                });
                            }

                        })
                        .finally(() => {
                            this.onChange();
                            this.canUpload();
                            this.placeholder = this.formatNames();
                        })
                    ;
                });
                this.model = null;
                this.$refs['file'].reset();
            },
            removeModelFile(index) {
                this.files.splice(index, 1);
                this.onChange();
                this.canUpload();
                this.placeholder = this.formatNames();
            },
            onChange(){
                this.$emit('input', this.getValue());
            },
            getValue(){
                let value = null;
                if (this.schema.multiple && this.files.length > 0) {
                    value = [];
                    forEach(this.files, (file) => {
                        if (file.id) {
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

            },
            canUpload(){
                if (this.disabled) {
                    this.uploadable = false;
                    return;
                }
                if (this.schema.multiple) {
                    if (this.schema.max && this.files.length >= this.schema.max) {
                        this.uploadable = false;
                        return;
                    }
                } else {
                    if (this.files.length >= 1) {
                        this.uploadable = false;
                        return;
                    }
                }
                this.uploadable = true;
            }
        }
    };
</script>

<style lang="scss">

    .custom-file {
        display: block;
        height: auto !important;

        .custom-file-input {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            height: auto;
        }
        .custom-file-label {
            position: relative;
            bottom: 0;
            height: auto;
            padding: 20px;
            text-align: center;
            background: #efefef;
            width: 100%;
        }
        .custom-file-label::after {
            visibility: hidden;
        }
    }

    .file-uploader .file-progress,
    .file-uploader .file-attached {
        margin: 10px 0;
    }
    .file-uploader .file-progress .invalid-feedback {
        display: block;
    }
</style>
