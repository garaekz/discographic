<script setup lang="ts">
import { ref } from 'vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import Paginator from './Paginator.vue';

type Column = {
  key: string;
  label: string;
};

defineProps({
  data: {
    type: Object,
    required: true,
  },
  columns: {
    type: Array as () => Column[],
    required: true,
  },
  hasActions: {
    type: Boolean,
    required: false,
    default: true,
  },
  hasCreate: {
    type: Boolean,
    required: false,
    default: true,
  },
  createLabel: {
    type: String,
    required: false,
    default: 'Create',
  },
});

const emit = defineEmits([
  'table:creating',
  'table:updating',
  'table:deleting',
  'table:page-change',
  'table:search'
]);

const search = ref('');

const onPageChange = (page: number) => {
  emit('table:page-change', { page });
};

const createDebounce = (fn: Function, delay: number) => {
  let timeoutId: number;
  return (...args: any[]) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => fn(...args), delay);
  };
};

const handleSearch = createDebounce((event: Event) => {
  emit('table:search', search.value);
}, 500);

</script>
<template>
  <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
      <div class="w-full md:w-1/2">
        <form class="flex items-center">
          <label for="search-bar" class="sr-only">Search</label>
          <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                  clip-rule="evenodd" />
              </svg>
            </div>
            <input
              @input="handleSearch"
              v-model="search"
              type="text"
              id="search-bar"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
              placeholder="Buscar" required>
          </div>
        </form>
      </div>
      <div
        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
        <button @click="emit('table:creating')" type="button"
          class="flex items-center justify-center text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm pl-2 pr-4 py-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true">
            <path
              d="M10.75 6.75a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z">
            </path>
          </svg>
          {{ createLabel }}
        </button>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-4 py-3" v-for="column in columns" :key="column.key">{{ column.label }}</th>
            <th v-if="hasActions" scope="col" class="px-4 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b dark:border-gray-700" v-for="item in data.data" :key="item.id">
            <td class="px-4 py-3" v-for="column in columns" :key="column.key">
              <slot :name="column.key" :item="item">
                {{ item[column.key] }}
              </slot>
            </td>
            <td v-if="hasActions" class="px-4 py-3 flex space-x-1 items-center justify-end">
                <button @click="emit('table:updating', item)" type="button"
                    class="flex items-center justify-center text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded text-sm px-1 py-1 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800 border border-indigo-800">
                    <PencilSquareIcon class="w-5" />
                </button>
                <button @click="emit('table:deleting', item)" type="button"
                    class="flex items-center justify-center text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded text-sm px-1 py-1 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 border border-red-800">
                    <TrashIcon class="w-5" />
                </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <Paginator
      :currentPage="data.current_page"
      :totalItems="data.total"
      :perPage="data.per_page"
      :links="data.links"
      @pageChange="onPageChange"
    />
  </div>
</template>