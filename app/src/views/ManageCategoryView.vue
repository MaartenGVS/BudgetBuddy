<template>
  <main class="manage-category-page bg-gray-100 font-sans">
    <div class="container mx-auto mt-10">
      <div class="bg-white p-8 rounded-lg shadow-md">

        <h2 class="text-2xl font-semibold mb-5">
          {{
            isCreateMode
                ? 'Create Category'
                : 'Edit Category'
          }}
        </h2>

        <create-edit-category-form
            v-bind:category="category"
            @submit-category="submitCategory"
        />

      </div>
    </div>
  </main>
</template>

<script>
import CreateEditCategoryForm from "@/modules/categories/components/CreateEditCategoryForm.vue";
import CreateEditTransactionForm from "@/modules/transactions/components/CreateEditTransactionForm.vue";
import CategoryService from "@/modules/categories/services/CategoryService.js";

export default {
  name: "ManageCategoryView",
  components: {
    CreateEditTransactionForm,
    CreateEditCategoryForm
  },
  data() {
    const currentRoute = this.$route;
    return {
      lang: "en",
      service: new CategoryService(),
      isCreateMode: currentRoute.name === 'create-category',
      category: null,
    };
  },
  async created() {
    if (this.isCreateMode) return;

    const categoryId = this.$route.params.id;
    this.category = await this.getCategory(categoryId);
  },
  methods: {
    async getCategory(categoryId) {
      return await this.service.get(categoryId);
    },
    async submitCategory(category) {
      if (this.isCreateMode) await this.createCategory(category);
      if (!this.isCreateMode) await this.updateCategory(category);
    },
    async createCategory(category) {
      await this.service.create(this.lang, category)
          .then(() => this.$router.push({name: 'admin'}))
          .catch(error => console.error(error));
    },
    async updateCategory(category) {
      await this.service.update(this.lang, category)
          .then(() => this.$router.push({name: 'admin'}))
          .catch(error => console.error(error));
    }
  }
}
</script>