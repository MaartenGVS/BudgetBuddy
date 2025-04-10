<template>
  <TopBar/>
  <main
        class="manage-transaction-page bg-gray-50 min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-8">
      <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6">
        {{
          isCreateMode
              ? $t('manage-transaction-view.create-new-transaction')
              : $t('manage-transaction-view.edit-transaction')
        }}
      </h1>
      <p class="text-gray-600 mb-6">
        {{
          isCreateMode
              ? $t('manage-transaction-view.create-instructions')
              : $t('manage-transaction-view.edit-instructions')
        }}
      </p>


      <category-type-filter-button
          v-bind:category_type="costType"
          @filter-switch="updateType"
      />

      <div class="mt-6">
        <create-edit-transaction-form
            v-bind:transaction="transaction"
            v-bind:costType="costType"
            v-bind:selectedCategoryId="selectedCategoryId"
            @submit="submitTransaction"
        />
      </div>
    </div>
  </main>
</template>

<script>
import TransactionService from "@/modules/transactions/services/transactionService.js";
import CreateTransactionForm from "@/modules/transactions/components/CreateEditTransactionForm.vue";
import FilterButton from "@/modules/categories/components/FilterButton.vue";
import CategoryTypeFilterButton from "@/modules/categories/components/FilterButton.vue";
import TopBar from "@/modules/core/components/TopBar.vue";
import CreateEditTransactionForm from "@/modules/transactions/components/CreateEditTransactionForm.vue";

export default {
  name: "ManageTransactionPage",
  components: {
    CreateEditTransactionForm,
    TopBar,
    CategoryTypeFilterButton,
    FilterButton,
    CreateTransactionForm
  },
  watch: {
    lang: {
      handler: "reloadPage"
    },
    $route: {
      handler: "reloadPage"
    }
  },
  data() {
    const currentRoute = this.$route;
    return {
      lang: this.$i18n.locale,
      service: new TransactionService(),
      isCreateMode: currentRoute.name === 'add-transaction',
      transaction: null,
      costType: null,
      selectedCategoryId: null
    };
  },
  async created() {
    if (this.isCreateMode) return;

    const transactionId = this.$route.params.id;
    this.transaction = await this.getTransaction(transactionId);

    this.selectedCategoryId = this.transaction.category_id;

    if (this.transaction.category.category_type === this.$t('manage-transaction-view.income')) {
      this.costType = this.$t('manage-transaction-view.incomes')
    } else if (this.transaction.category.category_type === this.$t('manage-transaction-view.expense')) {
      this.costType = this.$t('manage-transaction-view.expenses')
    }
  },
  methods: {
    async getTransaction(transactionId) {
      return await this.service.get(this.lang, transactionId);
    },
    async submitTransaction(transaction) {
      if (this.isCreateMode) await this.createTransaction(transaction);
      if (!this.isCreateMode) await this.updateTransaction(transaction);

    },
    async updateTransaction(transaction) {
      await this.service.update(this.lang, transaction)
          .then(() => {
            this.$router.push({
              name: 'dashboard',
              params: {month: transaction.month, year: transaction.year}
            });
          })
          .catch(error => {
            console.error(error);
          });
    },
    async createTransaction(transaction) {
      this.service.create(this.lang, transaction)
          .then(() => {
            this.$router.push({
              name: 'dashboard',
              params: {month: transaction.month, year: transaction.year}
            });
          })
          .catch(error => {
            console.error(error);
          });
    },
    updateType(type) {
      this.costType = type;
      this.resetSelectedCategory();
    },
    resetSelectedCategory() {
      this.selectedCategoryId = null;
    },
    reloadPage(){
      this.$router.go();
    }
  }
}
</script>