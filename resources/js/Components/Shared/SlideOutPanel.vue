<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionRoot } from '@headlessui/vue';

defineProps({
    isOpen: Boolean,
    title: String,
})

const emit = defineEmits(['close'])
</script>
<template>
    <TransitionRoot :show="isOpen" as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
        leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <Dialog as="div" :open="isOpen" @close="emit('close')" class="relative z-50">
            <div class="fixed inset-0 bg-black/30" aria-hidden="true" />

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <DialogPanel
                        class="overflow-y-auto fixed top-0 right-0 z-50 p-4 w-full md:max-w-md h-screen bg-white transition-transform dark:bg-gray-800"
                        :class="{ 'translate-x-full': !isOpen, 'translate-x-0': isOpen }">
                        <DialogTitle>
                            <h4 id="drawer-label"
                                class="mb-1.5 leading-none text-xl font-semibold text-gray-900 dark:text-white">
                                {{ title }}
                            </h4>
                            <button @click="emit('close')" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white dark:bg-gray-700">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close menu</span>
                            </button>
                        </DialogTitle>

                        <div class="mt-10">
                            <slot />
                        </div>
                    </DialogPanel>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>