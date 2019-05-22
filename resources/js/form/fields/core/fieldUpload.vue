<template>
    <div class="wrapper custom-file">
        <input class="form-control file-upload custom-file-input"
               :id="getFieldID(schema)"
               type="file"
               :accept="getAcceptAttributes()"
               :multiple="schema.multiple"
               :placeholder="schema.placeholder"
               :readonly="schema.readonly"
               :required="schema.required"
               :disabled="disabled"
               ref="file"
               v-on:change="handleFileUpload()"
        />
        <label class="custom-file-label">{{label}}</label>
        <div v-if="uploadPercentage > 0" class="progress">
            <div role="progressbar" :aria-valuenow="uploadPercentage" aria-valuemin="0" aria-valuemax="100" :class="['progress-bar', {
            'bg-danger': failed,
            'bg-success': uploaded,
            'progress-bar-animated progress-bar-striped':uploading}]" :style="{width: uploadPercentage + '%'}"></div>
        </div>
    </div>
</template>


<script>
import abstractField from "../abstractField";
import ApiService from '../../../services/ApiService';

export default {
	mixins: [abstractField],
    mounted(){

    },
    data(){
	    return{
	        label: 'Choose File',
	        inputId : null,
            file: '',
            uploadPercentage: 0,
            uploading: false,
            uploaded: false,
            failed: false
        }
    },
	methods: {
        handleFileUpload(){
            this.file = this.$refs.file.files[0];
            this.uploading = true;
            this.failed = false;
            this.uploadPercentage = 0;
            ApiService.upload(this.getUploadPath(this.schema), this.$refs.file.files[0], function( progressEvent ) {
                this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
            }.bind(this))
                .then(function(response){
                    this.value = response.data.id;
                    this.uploading = false;
                    this.uploaded = true;
                    this.failed = false;
                }.bind(this))
                .catch(function(response){
                    this.label = 'Choose File';
                    this.uploadPercentage = 0;
                    this.validate(response.error.file);
                    this.uploading = false;
                    this.failed = true;
                }.bind(this));
            ;
        },
		getUploadPath(schema){
		    return schema.action? schema.action: '/system/upload'
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
