<template>
  <TopBar/>
  <main class="admin-panel bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-8 max-w-fit">
      <div class="py-8">
        <div class="flex flex-wrap items-center justify-between mb-6">

          <h2 class="text-2xl font-semibold leading-tight">Categories</h2>
          <button
              @click="goToCreatePage"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Category
          </button>
        </div>


        <div class=" px-3 overflow-auto w-full bg-white rounded-lg shadow overflow-y-auto relative">
          <search-form
              class="px-5 py-5"
              v-bind:search="search"
              @update-search="updateSearch"
          />

          <div class="flex justify-between items-center px-5 py-5">
            <entity-quantity-picker
                @update-entity-quantity="updateQuantityEntries"
            />

            <filter-button
                @filter-switch="updateType"
            />
          </div>

          <categories-admin-table
              v-bind:categories="categories"
              v-bind:is-sorting-id-asc="isSortingIdAsc()"
              v-bind:is-sorting-name-asc="isSortingNameAsc()"
              @change-id-sorting-direction="changeIdSortingDirection"
              @change-name-sorting-direction="changeNameSortingDirection"
              @edit-category="editCategory"
              @show-delete-alert="showDeleteAlert"
          />
          <paginator
              v-bind:current-page="currentPage"
              v-bind:previous_page_url="previous_page_url"
              v-bind:next_page_url="next_page_url"
              @update-page="updatePage"
          />
        </div>
      </div>
    </div>

    <div v-if="isDeleteAlertShown" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
      <delete-alert
          @close-delete-alert="closeDeleteAlert"
          @delete-item="removeCategory"
      />
    </div>
  </main>
</template>

<script>
import CategoriesAdminTable from "@/modules/categories/components/CategoriesAdminTable.vue";
import CategoryService from "@/modules/categories/services/CategoryService.js";
import SearchForm from "@/modules/core/components/SearchForm.vue";
import Paginator from "@/modules/core/components/Paginator.vue";
import EntityQuantityPicker from "@/modules/transactions/components/EntityQuantityPicker.vue";
import {useI18n} from "vue-i18n";
import CategoryTypeFilterButton from "@/modules/categories/components/FilterButton.vue";
import TopBar from "@/modules/core/components/TopBar.vue";
import TransactionsTable from "@/modules/transactions/components/TransactionsTable.vue";
import FilterButton from "@/modules/transactions/components/FilterButton.vue";
import DeleteAlert from "@/modules/core/components/DeleteAlert.vue";

export default {
  name: "AdminDashboardView",
  components: {
    DeleteAlert,
    FilterButton,
    TransactionsTable,
    TopBar,
    CategoryTypeFilterButton,
    EntityQuantityPicker,
    Paginator,
    SearchForm,
    CategoriesAdminTable
  },
  data() {
    return {
      lang: useI18n().locale,
      categories: [],
      service: new CategoryService(),
      isLoading: false,
      currentPage: null,
      previous_page_url: null,
      next_page_url: null,
      perPage: null,
      type: "All",
      search: "",
      sort: "name",
      sortDirection: "asc",
      isDeleteAlertShown: false,
      deleteCategoryId: null
    }
  },
  async created() {
    await this.initializeData();
  },
  watch: {
    lang: {
      handler: "initializeData"
    },
  },
  methods: {
    async initializeData() {
      this.isLoading = true;
      this.currentPage = 1;
      this.perPage = 10;
      await this.fetchCategories();
      this.isLoading = false;
    },
    async fetchCategories() {
      const data = await this.service.getAllWithElevatedPermissions(this.lang, this.type, this.search, this.sort, this.sortDirection, this.perPage, this.currentPage);
      this.categories = data.data;
      this.previous_page_url = data.prev_page_url;
      this.next_page_url = data.next_page_url;
    },
    isSortingIdAsc() {
      return this.sort === "id" && this.sortDirection === "asc";
    },
    isSortingNameAsc() {
      return this.sort === "name" && this.sortDirection === "asc";
    },
    changeIdSortingDirection() {
      this.sort = "id";
      this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
      this.fetchCategories();
    },
    changeNameSortingDirection() {
      this.sort = "name";
      this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
      this.fetchCategories();
    },
    updateSearch(search) {
      this.search = search;
      this.fetchCategories();
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchCategories();
    },
    async updateQuantityEntries(quantity) {
      this.currentPage = 1;
      this.perPage = quantity;
      await this.fetchCategories();
    },
    async updateType(type) {
      this.type = type;
      await this.fetchCategories();
    },
    editCategory(categoryId) {
      this.$router.push({name: "edit-category", params: {id: categoryId}});
    },
    removeCategory() {
      this.service.delete(this.lang, this.deleteCategoryId).then(() => {
        this.fetchCategories();
      });
      this.isDeleteAlertShown = false;
    },
    goToCreatePage() {
      this.$router.push({name: "create-category"});
    },
    showDeleteAlert(categoryId) {
      this.deleteCategoryId = categoryId;
      this.isDeleteAlertShown = true;
    },
    closeDeleteAlert() {
      this.isDeleteAlertShown = false;
    }
  }
}
</script>
