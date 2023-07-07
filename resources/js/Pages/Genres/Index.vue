<script setup>
import { ColorPicker } from 'vue-color-kit'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { toast } from 'vue3-toastify';
import 'vue-color-kit/dist/vue-color-kit.css'
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SlideOutPanel from '@/Components/Shared/SlideOutPanel.vue';
import ConfirmationModal from '@/Components/Shared/ConfirmationModal.vue';
import { PlusIcon } from '@heroicons/vue/20/solid';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import dayjs from 'dayjs';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import GenreTable from '@/Components/Genres/GenreTable.vue';

defineProps({
    genres: Object,
});

const confirm = ref(null);

const slideOut = ref({
    visible: false,
    title: null,
    data: null,
});

const onCreate = () => {
    slideOut.value.visible = true;
    slideOut.value.title = 'Add New Genre';
};

const onEdit = (genre) => {
    slideOut.value.visible = true;
    slideOut.value.title = 'Edit Genre';

    form.id = genre.id;
    form.name = genre.name;
    form.slug = genre.slug;
    form.color = genre.color;
};


const handleSlideoutClose = () => {
    slideOut.value.visible = false;
    slideOut.value.title = null;
    form.reset();
};

const form = useForm({
    id: null,
    name: null,
    slug: null,
    color: '#0594B7',
});

const changeColor = (color) => {
    form.color = color.hex;
};

const formSuccess = (message) => {
    handleSlideoutClose();
    form.reset();
    toast.success(message);
};

const formError = (message) => {
    toast.error(message);
};

const submitForm = () => {
    if (form.id) {
        form.put(route('genres.update', form.id), {
            preserveScroll: true,
            onSuccess: () => formSuccess('Genre updated successfully!'),
            onError: () => formError('An error occurred while trying to update the genre.'),
        });
    } else {
        form.post(route('genres.store'), {
            preserveScroll: true,
            onSuccess: () => formSuccess('Genre created successfully!'),
            onError: () => formError('An error occurred while creating the genre.'),
        });
    }
};

const confirmDelete = async (id) => {
    const ok = await confirm.value.show({
        title: 'Delete Genre',
        message: 'Are you sure you want to delete this genre?',
        okButton: 'Delete',
    });

    if (ok) {
        form.delete(route('genres.destroy', id), {
            preserveScroll: true,
            onSuccess: () => formSuccess('Genre deleted successfully!'),
            onError: () => formError('An error occurred while deleting the genre.'),
        });
    }
}
</script>

<template>
    <ConfirmationModal ref="confirm" />
    <AdminLayout title="Genres">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Genres
            </h2>
        </template>

        <SlideOutPanel :isOpen="slideOut.visible" :title="slideOut.title" @close="handleSlideoutClose">
            <form @submit.prevent="submitForm" class="space-y-4">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Name
                    </label>
                    <input type="text" id="name" v-model="form.name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                </div>
                <div>
                    <label for="slug" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Slug <small>(optional)</small>
                    </label>
                    <input type="text" id="slug" v-model="form.slug"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div>
                    <Menu as="div" class="relative inline-block text-left">
                        <div>
                            <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Color
                            </label>

                            <MenuButton as="template">
                                <div class="flex">
                                    <div
                                        class="inline-flex items-center px-2 py-2 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <span class="w-6 h-6 rounded" :style="`background: ${form.color}`" />
                                    </div>
                                    <input v-model="form.color" type="text"
                                        class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        readonly>
                                </div>
                            </MenuButton>
                        </div>

                        <transition enter-active-class="transition duration-100 ease-out"
                            enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                            leave-active-class="transition duration-75 ease-in"
                            leave-from-class="transform scale-100 opacity-100"
                            leave-to-class="transform scale-95 opacity-0">
                            <MenuItems
                                class="absolute left-0 mt-2 w-56 origin-top-left divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <MenuItem v-slot="{ active }">
                                <ColorPicker class="box-content" :color="form.color" @changeColor="changeColor"
                                    @openSucker="openSucker" @inputFocus="inputFocus" @inputBlur="inputBlur" />
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
                <div class="flex justify-between">
                    <button type="button" @click="handleSlideoutClose"
                        class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center mr-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Cancel</button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                </div>
            </form>
        </SlideOutPanel>

        <section class="mx-auto max-w-screen-xl">
            <GenreTable :data="genres" @table:creating="onCreate" @table:updating="onEdit"
                @table:deleting="confirmDelete" />
        </section>
    </AdminLayout>
</template>
