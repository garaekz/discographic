<script setup>
import { ref } from 'vue';
import { ColorPicker } from 'vue-color-kit'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { toast } from 'vue3-toastify';
import 'vue-color-kit/dist/vue-color-kit.css'
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ArtistTable from '@/Components/Artists/ArtistTable.vue';
import ConfirmationModal from '@/Components/Shared/ConfirmationModal.vue';
import SlideOutPanel from '@/Components/Shared/SlideOutPanel.vue';

defineProps({
    artists: Object,
});

const confirm = ref(null);

const slideOut = ref({
    visible: false,
    title: null,
    data: null,
});

const onCreate = () => {
    slideOut.value.visible = true;
    slideOut.value.title = 'Add New Artist';
};

const onEdit = (artist) => {
    slideOut.value.visible = true;
    slideOut.value.title = 'Edit Artist';

    form = { form, ...artist }
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
    image: null,
    region: null,
    bio: null,
    excerpt: null,
    links: null,
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
        form.put(route('artists.update', form.id), {
            preserveScroll: true,
            onSuccess: () => formSuccess('Artist updated successfully!'),
            onError: () => formError('An error occurred while trying to update the artist.'),
        });
    } else {
        form.post(route('artists.store'), {
            preserveScroll: true,
            onSuccess: () => formSuccess('Artist created successfully!'),
            onError: () => formError('An error occurred while creating the artist.'),
        });
    }
};

const confirmDelete = async (id) => {
    const ok = await confirm.value.show({
        title: 'Delete Artist',
        message: 'Are you sure you want to delete this artist?',
        okButton: 'Delete',
    });

    if (ok) {
        form.delete(route('artists.destroy', id), {
            preserveScroll: true,
            onSuccess: () => formSuccess('Artist deleted successfully!'),
            onError: () => formError('An error occurred while deleting the artist.'),
        });
    }
}
</script>

<template>
    <ConfirmationModal ref="confirm" />
    <AdminLayout title="Artists">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Artists
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
                        Slug <small>(if not provided, it will be generated automatically)</small>
                    </label>
                    <input type="text" id="slug" v-model="form.slug"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div>
                    <label for="slug" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Region
                    </label>
                    <input type="text" id="slug" v-model="form.region"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
            <ArtistTable :data="artists" @table:creating="onCreate" @table:updating="onEdit"
                @table:deleting="confirmDelete" />
        </section>
    </AdminLayout>
</template>
