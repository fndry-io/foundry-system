<template>
    <div class="editor">
        <quill-editor ref="QuillEditor"
                      :content="model"
                      :options="editorOption"
                      @blur="onEditorBlur"
                      @focus="onEditorFocus"
                      @ready="onEditorReady"
                      @change="onEditorChange"
        ></quill-editor>
    </div>
</template>

<script>

    import {get} from 'lodash';

    import { quillEditor } from 'vue-quill-editor';
    import Quill from 'quill';

    import abstractInput from '../abstractInput';

    export default {
        name: 'html-editor',
        mixins: [
            abstractInput
        ],
        components: {
            quillEditor
        },
        props: {
            value: {
                required: false,
                default() {
                    return null
                }
            }
        },
        data() {
            return {
                model: this.value,
                editorOption: {
                    modules: {
                        toolbar: this.makeToolbar()
                    },
                    theme: 'snow'
                },
            }
        },
        created(){
            let block = get(this.schema, 'config.editor.block', 'p');
            if (block === 'div') {
                let Block = Quill.import('blots/block');
                Block.tagName = 'DIV';
                Quill.register(Block, true);
            }
        },
        computed: {
            editor() {
                return this.$refs.QuillEditor.quill
            }
        },
        methods: {
            onEditorBlur(quill) {
                this.$emit('blur');
            },
            onEditorFocus(quill) {
                this.$emit('focus');
            },
            onEditorReady(quill) {

            },
            onEditorChange({ quill, html, text }) {
                this.content = html;
                this.$emit('input', this.content);
            },
            makeToolbar() {
                return get(this.schema, 'config.editor.toolbar', [
                    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                    ['blockquote', 'code-block'],
                    [{'align': []}],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{'script': 'sub'}, {'script': 'super'}],      // superscript/subscript
                    [{'indent': '-1'}, {'indent': '+1'}],          // outdent/indent
                    [{'direction': 'rtl'}],                         // text direction

                    [{'size': ['small', false, 'large', 'huge']}],  // custom dropdown
                    [{'header': [1, 2, 3, 4, 5, 6, false]}],

                    [{'color': []}],          // dropdown with defaults from theme
                    ['image', 'video'],

                    ['clean']                                         // remove formatting button
                ])
            }
        }
    }
</script>

<style lang="scss">

    @import '~quill/dist/quill.core.css';
    @import '~quill/dist/quill.snow.css';
    @import '~quill/dist/quill.bubble.css';

    .ql-editor {
        min-height: 400px;
    }
</style>
