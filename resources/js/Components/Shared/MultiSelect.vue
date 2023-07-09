<script setup>
import { ref, watch, onMounted } from 'vue';
import { CheckIcon, ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/solid';
import { XMarkIcon } from '@heroicons/vue/20/solid';
import { Listbox, ListboxButton, ListboxOption, ListboxOptions } from '@headlessui/vue';

const emit = defineEmits(['update:modelValue']);
const props = defineProps({
    options: Object,
    modelValue: Array,
});

const selected = ref(props.options.filter((option) => {
    return props.modelValue.some((opt) => opt.id === option.id);
}));

watch(selected, (newVal) => {
    emit('update:modelValue', newVal);
});

const removeSelected = (option) => {
    const index = selected.value.findIndex((opt) => opt.value === option.value);
    if (index !== -1) {
        selected.value.splice(index, 1);
    }
};
</script>
<template>
    <div class="w-96">
        <Listbox v-model="selected" v-slot="{ open }" multiple>
            <div class="relative">
                <ListboxButton
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 flex justify-between py-2">
                    <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <div v-for="option in selected" :key="option.value"
                            class="items-center rounded-md text-sm font-medium bg-blue-100 text-blue-800 flex justify-between gap-2">
                            <span class="pl-2 whitespace-nowrap">
                                {{ option.name }}
                            </span>
                            <button @click.prevent="removeSelected(option)" class="p-1 hover:bg-blue-200 rounded-md focus:outline-none" type="button">
                                <XMarkIcon class="h-4 w-4 text-blue-500" />
                            </button>
                        </div>
                    </div>
                    <span class="py-1">
                        <ChevronUpIcon v-if="open" class="h-4 w-4 text-gray-500" />
                        <ChevronDownIcon v-else class="h-4 w-4 text-gray-500" />
                    </span>
                </ListboxButton>
            </div>
            <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                leave-to-class="opacity-0">
                <ListboxOptions
                    class="absolute z-40 mt-1 max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                    <ListboxOption v-slot="{ active, selected }" v-for="person in options" :key="person.name" :value="person" as="template">
                        <li 
                            @click="close"
                            :class="[
                            active ? 'bg-blue-100 text-blue-800 dark:bg-gray-800 dark:text-white' : 'text-gray-900 dark:text-white',
                            'select-none relative flex py-2 pl-11 pr-9 cursor-pointer',
                        ]">
                            <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600">
                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                            <span :class="[
                                selected ? 'font-bold' : 'font-normal',
                                'block truncate text-sm dark:text-white text-gray-900',
                            ]">{{ person.name }}</span>
                            
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </Listbox>
    </div>
</template>