<template>
  <div v-if="!isLoading" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-500 bg-opacity-75">
    <div class="bg-white rounded-lg shadow-xl p-6 w-3/4 h-4/6 max-w-4xl">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">{{ $t('manage-transaction-view.select-a-category') }}</h2>
        <button @click="closeModal" class="p-2 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
               stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18l12-12M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <search-form
          v-model:search="search"
          @update-search="updateSearch"
      />

      <entity-quantity-picker
          @update-entity-quantity="updateQuantityEntries"
      />

      <div class="overflow-auto" style="height: calc(100% - 100px);">
        <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-2">
          <li v-for="category in categories" :key="category.id"
              :class="['flex items-center p-4 rounded-lg shadow', { 'bg-blue-500 text-white': category.id === selectedCategoryId, 'bg-gray-100 hover:bg-gray-200': category.id !== selectedCategoryId }]"
              @click="selectCategory(category.id)">

            <img v-if="category.icon_url !== 'noUrl'" :src="category.icon_url" class="w-8 h-8 mr-2" alt="category-icon">
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-6 h-6 mr-2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
            </svg>

            {{ category.name }}
          </li>
        </ul>

        <paginator
            v-model:current-page="currentPage"
            v-model:previous_page_url="previous_page_url"
            v-model:next_page_url="next_page_url"
            @update-page="updatePage"
        />

      </div>
    </div>
  </div>
</template>
<script>
import SearchForm from "@/modules/core/components/SearchForm.vue";
import CategoryService from "@/modules/categories/services/CategoryService.js";
import Paginator from "@/modules/core/components/Paginator.vue";
import EntityQuantityPicker from "@/modules/transactions/components/EntityQuantityPicker.vue";
import {cloneDeep} from "lodash";

export default {
  name: "CategoryPicker",
  components: {
    EntityQuantityPicker,
    Paginator,
    SearchForm
  },
  emits: ['close'],
  props: {
    costType: {
      type: String,
      required: true
    },
    previousCategoryId: {
      type: Number,
      required: false
    },
  },
  data() {
    return {
      isLoading: false,
      service: new CategoryService(),
      categories: [],
      search: "",
      lang: "",
      sort: "",
      sortDirection: "",
      page: null,
      selectedCategoryId: null,
      currentPage: null,
      previous_page_url: null,
      next_page_url: null,
      perPage: null
    }
  },
  watch: {
    previousCategoryId: {
      handler(newValue) {
        this.selectedCategoryId = cloneDeep(newValue);
      },
      immediate: true
    }
  },
  async created() {
    await this.initializeData();
  },
  methods: {
    async initializeData() {
      this.isLoading = true;
      this.lang = this.$i18n.locale;
      this.sort = "name";
      this.sortDirection = "asc";
      this.currentPage = 1;
      this.perPage = 10;
      await this.fetchCategories();
      this.isLoading = false;
    },
    async fetchCategories() {
      const data = await this.service.getAll(this.lang, this.costType, this.search, this.sort, this.sortDirection, this.perPage, this.currentPage);
      this.categories = data.data;
      this.currentPage = data.current_page;
      this.previous_page_url = data.prev_page_url;
      this.next_page_url = data.next_page_url;
    },
    selectCategory(id) {
      this.selectedCategoryId = id;
    },
    closeModal() {
      if (this.selectedCategoryId) {
        this.$emit("close", this.selectedCategoryId, this.categories.find(category => category.id === this.selectedCategoryId).name);
      } else {
        this.$emit("close")
      }
    },
    updateSearch(search) {
      this.search = search;
      this.fetchCategories();
    },
    async updatePage(currentPage) {
      this.currentPage = currentPage;
      await this.fetchCategories();
    },
    async updateQuantityEntries(quantity) {
      this.currentPage = 1;
      this.perPage = quantity;
      await this.fetchCategories();
    }
  }
}
</script>