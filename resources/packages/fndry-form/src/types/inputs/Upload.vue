<template>
    <div class="file-uploader">

        <b-form-file
            :id="id"
            :name="name"
            :state="Boolean(files)"
            v-if="showInput()"
            placeholder="Choose a file or drop it here..."
            drop-placeholder="Drop file here..."
            :accept="getAcceptAttributes()"
            :multiple="schema.multiple"
            :placeholder="placeholder"
            :disabled="!uploadable"
            :readonly="schema.readonly"
            :required="schema.required"
            :value="fileModel"
            ref="file"
            @input="handleFileInput"
            :file-name-formatter="formatNames"
        ></b-form-file>

        <div :class="filesClasses">
            <div v-if="files && files.length > 0" class="file-outer" v-for="(file, index) in files">
                <div class="file uploaded">
                    <div class="file-thumbnail" v-if="schema.type === 'image'">
                        <img v-if="schema.type === 'image'" class="responsive" :src="file.url" :alt="file.original_name" />
                    </div>
                    <div class="file-detail">
                        <div class="row">
                            <div class="col flex-grow-1">
                                <div class="file-name">
                                    {{file.original_name}}
                                </div>
                                <div class="file-info">
                                    <span v-if="file.type"><span class="badge badge-info">{{file.type}}</span>&nbsp;</span>
                                    <span v-if="file.size"><span class="badge badge-info">{{file.size}}kb</span></span>
                                </div>
                            </div>
                            <div class="col flex-grow-0 text-right">
                                <fndry-request-button v-if="schema.deleteUrl" size="sm"  variant="danger" :request="schema.deleteUrl" :params="{_entity: file.id, force: true, token: file.token}" type="confirm" :confirm-options="{message: 'Are you sure you want to remove this file?'}" @success="(response) => removeModelFile(index)"><span class="fa fa-trash"></span></fndry-request-button>
                            </div>
                        </div>
                    </div>
                    <div class="file-progress">
                        <b-progress
                            :value="100"
                            :max="100"
                            :animated="false"
                            :striped="false"
                            variant="primary"
                            style="min-width: 100px;"
                            height="2px"
                        ></b-progress>
                    </div>
                </div>
            </div>

            <div v-if="uploading.length > 0 && (file.uploading || file.failed)"
                 class="file-outer"
                 v-for="(file, index) in uploading"
                 :key="`${index}-${file.file.name}`"
            >
                <div class="file uploading">
                    <div class="file-thumbnail" v-if="schema.type === 'image' && file.file.url">
                        <img class="responsive" :src="file.file.url" :alt="file.file.name" />
                    </div>
                    <div class="file-detail">
                        <div class="row">
                            <div class="col flex-grow-1">
                                <div class="file-name">{{file.file.name}}</div>
                                <div class="invalid-feedback" v-if="file.failed" style="display: block;">
                                    <span v-if="file.validation.length > 0" v-for="error in file.validation" style="display: block;">{{error}}</span>
                                    <span v-else>{{file.error}}</span>
                                </div>
                            </div>
                            <div class="col flex-grow-0 text-right">
                                <b-button v-if="file.failed" size="sm" variant="danger" @click="removeUploading(index)"><span class="fa fa-trash"></span></b-button>
                            </div>
                        </div>
                    </div>
                    <div class="file-progress">
                        <b-progress
                            v-if="file.uploading"
                            :value="file.progress"
                            :max="100"
                            :animated="file.uploading"
                            :striped="file.uploading"
                            :variant="(file.failed) ? 'danger' : ((file.uploaded) ? 'success' : 'primary')"
                            height="2px"
                        ></b-progress>
                    </div>
                </div>
            </div>


            <div v-if="upload.length > 0"
                 class="file-outer upload"
                 v-for="file in upload"
            >
                <div class="file upload">
                    <div class="file-thumbnail" v-if="schema.type === 'image' && file.file.url">
                        <img class="responsive" :src="file.file.url" :alt="file.file.name" />
                    </div>
                    <div class="file-detail">
                        <div class="file-name">{{file.file.name}}</div>
                    </div>
                    <div class="file-progress">
                        <b-progress
                            :value="0"
                            :max="100"
                            :animated="false"
                            :striped="false"
                            variant="primary"
                            height="2px"
                        ></b-progress>
                    </div>
                </div>
            </div>

        </div>


    </div>
</template>


<script>

    import abstractInput from '../abstractInput';
    import {uploadInput} from '../../mixins/field';

    export default {
        name: "fndry-field-upload",
        mixins: [
            abstractInput,
            uploadInput
        ],

    };
</script>

<style lang="scss">

    /*.custom-file {*/
    /*    display: block;*/
    /*    height: auto !important;*/

    /*    .custom-file-input {*/
    /*        position: absolute;*/
    /*        top: 0;*/
    /*        right: 0;*/
    /*        left: 0;*/
    /*        bottom: 0;*/
    /*        height: auto;*/
    /*    }*/
    /*    .custom-file-label {*/
    /*        position: relative;*/
    /*        bottom: 0;*/
    /*        height: auto;*/
    /*        padding: 20px;*/
    /*        text-align: center;*/
    /*        background: #efefef;*/
    /*        width: 100%;*/
    /*    }*/
    /*    .custom-file-label::after {*/
    /*        visibility: hidden;*/
    /*    }*/
    /*}*/

    .file-uploader .file-progress .invalid-feedback {
        display: block;
    }


    .file-layout {

        .file-progress {
            position: absolute;
            bottom: -1px;
            left: -1px;
            right: -1px;
        }

        .file-outer {
            margin: 15px 0;
        }

        .file-name {
            padding: 0.3rem 0;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .file {
            position: relative;
            border: 1px solid #efefef;

            .file-thumbnail {
                img {
                    width: 100%;
                    height: auto;
                }
            }

            .file-detail {
                padding: 10px;
                background-color: rgba(255,255,255,0.5);
                box-sizing: border-box;
            }

            .file-progress {
                margin-bottom: 0;
            }
        }

        &.file-layout-thumbnails {
            margin: 0 -5px;
            .file-outer {
                display: inline-block;
                width: calc( 100% / 3 );
                padding: 0 5px 10px;

                &:nth-child(3n) {
                    &::after {
                        content: " ";
                        display: block;
                        clear: left;
                    }
                }
            }
            .file.uploaded {
                position: relative;
                .file-detail {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    display: none;
                }
                &:hover {
                    .file-detail {
                        display: block;
                    }
                }
            }
        }

        &.file-layout-details {

            .file-outer {
                padding: 0 0 10px;
            }

            .file {
                position: relative;
                display: block;
                width: auto;

                &::after {
                    display: block;
                    content: " ";
                    clear: both;
                }

                .file-thumbnail {
                    width: 75px;
                    float: left;
                }

                .file-thumbnail + .file-detail {
                    margin-left: 115px;
                    width: calc(100% - 115px);
                }

                .file-detail {
                    position: relative;
                }

                .file-info {
                    display: none;
                }
            }
        }
    }

</style>
