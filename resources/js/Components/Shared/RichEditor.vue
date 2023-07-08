<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import StarterKit from '@tiptap/starter-kit';
import { Editor, EditorContent } from '@tiptap/vue-3';
import { Blockquote } from '@tiptap/extension-blockquote';
import { Heading } from '@tiptap/extension-heading';
import { CodeBlock } from '@tiptap/extension-code-block';
import { mergeAttributes } from '@tiptap/core'
import { CodeBracketIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
});

const emits = defineEmits(['update:modelValue'])

let editor = ref(null);

onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue,
        onUpdate: ({ editor }) => {
            let content = editor.getHTML()
            emits('update:modelValue', content)
        },
        editorProps: {
            attributes: {
                class: 'focus:outline-none block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400 h-36 max-h-36 overflow-y-auto',
            },
        },
        extensions: [
            StarterKit.configure({
                blockquote: {
                    HTMLAttributes: {
                        class: 'border-l-4 border-gray-300 dark:border-gray-600 pl-4',
                    },
                },
                codeBlock: {
                    HTMLAttributes: {
                        class: 'bg-gray-100 dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-300 p-2 rounded-lg',
                    },
                },
                heading: false,
            }),
            Heading.extend({
                levels: [1, 2, 3, 4, 5],
                renderHTML({ node, HTMLAttributes }) {
                    const level = this.options.levels.includes(node.attrs.level)
                        ? node.attrs.level
                        : this.options.levels[0];
                    const classes = {
                        1: 'text-4xl font-bold',
                        2: 'text-3xl font-bold',
                        3: 'text-2xl font-bold',
                        4: 'text-xl font-semibold',
                        5: 'text-lg font-semibold',
                    };
                    return [
                        `h${level}`,
                        mergeAttributes(this.options.HTMLAttributes, HTMLAttributes, {
                            class: `${classes[level]}`,
                        }),
                        0,
                    ];
                },
            }).configure({
                levels: [1, 2, 3, 4, 5],
            }),
        ],
    });
});

onBeforeUnmount(() => {
    editor.value.destroy();
});
</script>
<template>
    <div v-if="editor">
        <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
            <div class="flex items-center justify-between px-3 py-2 border-b dark:border-gray-600">
                <div class="flex flex-wrap items-center divide-gray-200 sm:divide-x dark:divide-gray-600">
                    <div class="flex items-center space-x-1 sm:pr-4">
                        <Menu as="div" class="relative inline-block text-left z-50">
                            <div>
                                <MenuButton
                                    class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6.25 4C6.66421 4 7 4.33579 7 4.75V11H17V4.75C17 4.33579 17.3358 4 17.75 4C18.1642 4 18.5 4.33579 18.5 4.75V19.25C18.5 19.6642 18.1642 20 17.75 20C17.3358 20 17 19.6642 17 19.25V12.5H7V19.25C7 19.6642 6.66421 20 6.25 20C5.83579 20 5.5 19.6642 5.5 19.25V4.75C5.5 4.33579 5.83579 4 6.25 4Z" />
                                    </svg>

                                    <span class="sr-only">Heading</span>
                                </MenuButton>
                            </div>

                            <transition enter-active-class="transition duration-100 ease-out"
                                enter-from-class="transform scale-95 opacity-0"
                                enter-to-class="transform scale-100 opacity-100"
                                leave-active-class="transition duration-75 ease-in"
                                leave-from-class="transform scale-100 opacity-100"
                                leave-to-class="transform scale-95 opacity-0">
                                <MenuItems
                                    class="absolute left-0 mt-2 w-56 origin-top-right text-base list-none bg-white divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600 rounded-md ring-black ring-opacity-5 focus:outline-none">
                                    <MenuItem v-slot="{ active }">
                                    <button @click="editor.chain().focus().setParagraph().run()" :class="[
                                        active ? 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900' : 'text-gray-900 dark:text-white',
                                        'group flex w-full items-center rounded-md px-2 py-2',
                                    ]">
                                        Paragraph
                                    </button>
                                    </MenuItem>

                                    <MenuItem v-slot="{ active }">
                                    <button @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="[
                                        active ? 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900' : 'text-gray-900 dark:text-white',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-4xl',
                                    ]">
                                        Header 1
                                    </button>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                    <button @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="[
                                        active ? 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900' : 'text-gray-900 dark:text-white',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-3xl',
                                    ]">
                                        Header 2
                                    </button>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                    <button @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="[
                                        active ? 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900' : 'text-gray-900 dark:text-white',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-2xl',
                                    ]">
                                        Header 3
                                    </button>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                    <button @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" :class="[
                                        active ? 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900' : 'text-gray-900 dark:text-white',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-xl',
                                    ]">
                                        Header 4
                                    </button>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                    <button @click="editor.chain().focus().toggleHeading({ level: 5 }).run()" :class="[
                                        active ? 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900' : 'text-gray-900 dark:text-white',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-lg',
                                    ]">
                                        Header 5
                                    </button>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                        <button @click="editor.chain().focus().toggleBold().run()"
                            :disabled="!editor.can().chain().focus().toggleBold().run()"
                            :class="{ 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900': editor.isActive('bold') }"
                            type="button"
                            class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg width="13" height="18" viewBox="0 0 13 18" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0 1.75C0 1.05964 0.559644 0.5 1.25 0.5H6.25C8.87335 0.5 11 2.62665 11 5.25C11 6.26565 10.6812 7.20685 10.1383 7.979C11.83 8.78054 13 10.5036 13 12.5C13 15.2614 10.7614 17.5 8 17.5H1.25C0.559644 17.5 0 16.9404 0 16.25V1.75ZM2.5 10V15H8C9.38071 15 10.5 13.8807 10.5 12.5C10.5 11.1193 9.38071 10 8 10H2.5ZM2.5 7.5L6.25127 7.5C7.49333 7.49931 8.5 6.49222 8.5 5.25C8.5 4.00736 7.49264 3 6.25 3H2.5V7.5Z" />
                            </svg>
                            <span class="sr-only">Bold Text</span>
                        </button>
                        <button @click="editor.chain().focus().toggleItalic().run()"
                            :disabled="!editor.can().chain().focus().toggleItalic().run()"
                            :class="{ 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900': editor.isActive('italic') }"
                            type="button"
                            class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg width="18 " height="18" viewBox="0 0 24 24" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10 4.75C10 4.33579 10.3358 4 10.75 4H19.25C19.6642 4 20 4.33579 20 4.75C20 5.16421 19.6642 5.5 19.25 5.5H15.7357L9.90812 18.5H13.25C13.6642 18.5 14 18.8358 14 19.25C14 19.6642 13.6642 20 13.25 20H4.75C4.33579 20 4 19.6642 4 19.25C4 18.8358 4.33579 18.5 4.75 18.5H8.2643L14.0919 5.5H10.75C10.3358 5.5 10 5.16421 10 4.75Z" />
                            </svg>
                            <span class="sr-only">Italic Text</span>
                        </button>
                        <button @click="editor.chain().focus().toggleBlockquote().run()"
                            :class="{ 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900': editor.isActive('blockquote') }"
                            class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg aria-hidden="true" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"
                                focusable="false">
                                <g>
                                    <path
                                        d="M464 32c26.5 0 48 21.5 48 48v240c0 88.4-71.6 160-160 160h-8c-13.3 0-24-10.7-24-24v-48c0-13.3 10.7-24 24-24h8c35.3 0 64-28.7 64-64v-64h-80c-26.5 0-48-21.5-48-48v-128c0-26.5 21.5-48 48-48h128zM176 32c26.5 0 48 21.5 48 48v240c0 88.4-71.6 160-160 160h-8c-13.3 0-24-10.7-24-24v-48c0-13.3 10.7-24 24-24h8c35.3 0 64-28.7 64-64v-64h-80c-26.5 0-48-21.5-48-48v-128c0-26.5 21.5-48 48-48h128z">
                                    </path>
                                </g>
                            </svg>
                            <span class="sr-only">Blockquote</span>
                        </button>
                        <button @click="editor.chain().focus().toggleCodeBlock().run()"
                            :class="{ 'bg-gray-100 dark:bg-gray-600 dark:text-white text-gray-900': editor.isActive('codeBlock') }"
                            class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <CodeBracketIcon class="w-5 h-5" />
                            <span class="sr-only">Code Block</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                <label for="editor" class="sr-only">Publish post</label>
                <editor-content :editor="editor" />
            </div>
        </div>

    </div>
</template>