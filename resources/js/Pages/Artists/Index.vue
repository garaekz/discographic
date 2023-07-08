<script setup>
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue-color-kit/dist/vue-color-kit.css'
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ArtistTable from '@/Components/Artists/ArtistTable.vue';
import ConfirmationModal from '@/Components/Shared/ConfirmationModal.vue';
import SlideOutPanel from '@/Components/Shared/SlideOutPanel.vue';
import RichEditor from '@/Components/Shared/RichEditor.vue';

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
    form.id = artist.id;
    form.name = artist.name;
    form.slug = artist.slug;
    form.image = artist.image;
    form.imagePreview = artist.image;
    form.region = artist.region;
    form.bio = artist.bio;
    form.links = artist.links;
};


const handleSlideoutClose = () => {
    slideOut.value.visible = false;
    slideOut.value.title = null;
    form.reset();
};

const form = useForm({
    _method: 'POST',
    id: null,
    name: null,
    slug: null,
    image: null,
    imagePreview: null,
    region: null,
    bio: null,
    links: null,
});

const formSuccess = (message) => {
    handleSlideoutClose();
    form.reset();
    toast.success(message);
};

const formError = (message, err) => {
    console.error(err);
    toast.error(message);
};

const submitForm = () => {
    if (form.id) {
        form._method = 'PUT';
        form.post(route('artists.update', form.id), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => formSuccess('Artist updated successfully!'),
            onError: (e) => formError('An error occurred while trying to update the artist.', e),
        });
    } else {
        form.post(route('artists.store'), {
            forceFormData: true,
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

const imageFile = ref(null);

const handleFileDrop = (e) => {
    if (imageFile.value) {
        form.image = imageFile.value.files[0];
    }
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = (e) => {
        form.imagePreview = e.target.result;
    };

    reader.readAsDataURL(file);
};
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
                <div>
                    <label for="slug" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Artist Image
                    </label>
                    <div v-if="form.imagePreview" class="flex">
                        <div class="relative">
                            <img :src="form.imagePreview" class="w-32 h-32 rounded-lg" />
                            <button type="button" @click="form.image = null; form.imagePreview = null"
                                class="absolute top-0 right-0 bg-red-600 hover:bg-red-700 focus:outline-none text-white rounded-full h-8 w-8 flex items-center justify-center -mt-2 -mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div v-else class="flex items-center justify-center w-full">
                        <label
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click
                                        to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or Webp (MAX. 800x800px)
                                </p>
                            </div>
                            <input @change="handleFileDrop" ref="imageFile" type="file" class="hidden" />
                        </label>
                    </div>
                </div>
                <div>
                    <RichEditor v-model="form.bio" />
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
    </AdminLayout></template>
