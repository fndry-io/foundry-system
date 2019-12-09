<template>
    <div class="editor">
        <div class="editor-content" @click="focus">
            <editor-content :editor="editor" />
        </div>
        <editor-menu-bar class="editor-menu-bar" :editor="editor" :keep-in-bounds="keepInBounds" v-slot="{ commands, isActive, menu, getMarkAttrs }">
            <div
                class="menububble"
            >
                <b-button-group>
                    <b-button
                        size="sm"
                        variant="outline-info"
                        :class="{ 'is-active': isActive.bold() }"
                        @click="commands.bold"
                    >
                        <icon name="bold" />
                    </b-button>
                    <b-button
                        size="sm"
                        variant="outline-info"
                        :class="{ 'is-active': isActive.italic() }"
                        @click="commands.italic"
                    >
                        <icon name="italic" />
                    </b-button>
                    <b-button
                        size="sm"
                        variant="outline-info"
                        :class="{ 'is-active': isActive.italic() }"
                        @click="commands.underline"
                    >
                        <icon name="underline" />
                    </b-button>
                    <b-button
                        size="sm"
                        variant="outline-info"
                        :class="{ 'is-active': isActive.italic() }"
                        @click="commands.underline"
                    >
                        <icon name="strikethrough" />
                    </b-button>
                </b-button-group>&nbsp;
                <b-button-group>
                    <b-button
                        size="sm"
                        variant="outline-info"
                        :class="{ 'is-active': isActive.bullet_list() }"
                        @click="commands.bullet_list"
                    >
                        <icon name="list-ul" />
                    </b-button>
                    <b-button
                        size="sm"
                        variant="outline-info"
                        :class="{ 'is-active': isActive.ordered_list() }"
                        @click="commands.ordered_list"
                    >
                        <icon name="list-ol" />
                    </b-button>
                </b-button-group>&nbsp;

            </div>
        </editor-menu-bar>
    </div>
</template>

<script>
    import { Editor, EditorContent, EditorMenuBar, Node, Text, Doc } from 'tiptap'
    import {
        Blockquote,
        BulletList,
        CodeBlock,
        HardBreak,
        Heading,
        ListItem,
        OrderedList,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History
    } from 'tiptap-extensions';
    import Icon from "../../components/FormIcon";
    import abstractInput from '../abstractInput';

    class Paragraph extends Node {
        get name() {
            return 'paragraph';
        }

        get schema() {
            return {
                content: 'inline*',
                draggable: false,
                group: 'block',
                parseDOM: [{
                    tag: 'div',
                }],
                toDOM() {
                    return ['div', 0];
                },
            };
        }
    }

    export default {
        name: 'html-editor',
        mixins: [
            abstractInput
        ],
        props: {
            value: {
                required: false,
                default() {
                    return null
                }
            }
        },
        components: {
            Icon,
            EditorContent,
            EditorMenuBar
        },
        data() {
            return {
                keepInBounds: true,
                useBuiltInExtensions: false,
                editor: new Editor({
                    extensions: [
                        new Doc(),
                        new Text(),
                        new Paragraph(),
                        new Blockquote(),
                        new BulletList(),
                        new HardBreak(),
                        new Heading({ levels: [1, 2, 3] }),
                        new ListItem(),
                        new OrderedList(),
                        new Link(),
                        new Bold(),
                        new Code(),
                        new Italic(),
                        new Strike(),
                        new Underline(),
                        new History()
                    ],
                    content: this.value,
                    onUpdate: this.onUpdate
                }),
            }
        },
        methods: {
            onUpdate(obj){
                this.$emit('input', obj.getHTML());
            },
            focus() {
                this.editor.focus();
            }
        },
        watch: {
            value: function(newValue, oldValue){
                if (newValue !== oldValue && newValue === null) {
                    this.editor.setContent(newValue);
                }
            }
        },
        beforeDestroy() {
            this.editor.destroy()
        },
    }
</script>

<style lang="scss">
    .editor {
        position: relative;
        display: block;
    }
    .editor-menu-bar {
        margin: 10px 0;
    }
    .editor-content {
        display: block;
        padding: 0.8rem;
        background: #fff;
        border: 1px solid #e4e7ea;
        max-height: 500px;
        overflow-y: auto;
        min-height: 200px;

        ul {
            display: block;
            list-style-type: disc;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0;
            margin-inline-end: 0;
            padding-inline-start: 40px;
        }
        ol {
            display: block;
            list-style-type: decimal;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0;
            margin-inline-end: 0;
            padding-inline-start: 40px;
        }
        p {
            margin: 0;
            padding: 0;
        }

        .ProseMirror {
            &:focus {
                outline: none;
            }
        }
    }
</style>
