<script setup>
import { reactive, ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'

const isOpen = ref(false)
let resolvePromise = reactive(undefined)
let rejectPromise = reactive(undefined)

const title = ref('Are you sure?')
const message = ref('Are you sure you want to proceed? This action cannot be undone.')
const showCancelButton = ref(true)
const cancelButton = ref('Cancel')
const confirmButton = ref('Confirm')

const closeModal = () => {
    isOpen.value = false
}

const openModal = () => {
    isOpen.value = true
}

const show = (opts = {}) => {
    title.value = opts.title || 'Are you sure?';
    message.value = opts.message || 'Are you sure you want to proceed? This action cannot be undone.';
    showCancelButton.value = opts.showCancelButton || true;
    cancelButton.value = opts.cancelButton || 'Cancel';
    confirmButton.value = opts.confirmButton || 'Confirm';
    openModal();
    return new Promise((resolve, reject) => {
        resolvePromise = resolve;
        rejectPromise = reject;
    });
}

const _confirm = () => {
    closeModal();
    resolvePromise(true);
}
const _cancel = () => {
    closeModal();
    resolvePromise(false);
}

defineExpose({ show });
</script>
<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" class="relative z-50">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-500 dark:text-gray-300">
                                <h4 id="drawer-label"
                                    class="mb-1.5 leading-none text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ title }}
                                </h4>
                                <button @click="closeModal" type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white dark:bg-gray-700">
                                    <XMarkIcon class="w-5 h-5" />
                                    <span class="sr-only">Close menu</span>
                                </button>
                            </DialogTitle>
                            <div class="mt-2">
                                <p class="text text-gray-500 dark:text-gray-300">
                                    {{ message }}
                                </p>
                            </div>

                            <div class="mt-4 flex justify-end gap-x-3">
                                <button v-if="showCancelButton" type="button" @click="_cancel"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                    {{ cancelButton }}
                                </button>
                                <button type="button" @click="_confirm"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:bg-red-600 dark:hover:bg-red-700">
                                    {{ confirmButton }}
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
  