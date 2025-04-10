<template>
  <TopBar/>
  <main v-if="!isLoading" class="front-dashboard bg-gray-100 min-h-screen flex items-center flex-col justify-center">

    <budget-panel
        v-bind:budget="budget"
        v-bind:month="month"
        @update-previous-month="updateMonth"
        @update-next-month="updateMonth"
    />

    <div class="w-4/5 mx-auto mt-16 bg-white shadow-xl rounded-lg overflow-hidden p-8">

      <div class="flex justify-between items-center mb-6">
        <search-form
            v-bind:search="search"
            @update-search="updateSearch"
        />
        <add-transaction-button/>
      </div>

      <entity-quantity-picker
          @update-entity-quantity="updateQuantityEntries"
      />

      <filter-button
          @filter-switch="updateType"
      />

      <transactions-table
          v-bind:transactions="transactions"
          @edit-transaction="editTransaction"
          @remove-transaction="showDeleteAlert"
      />

    </div>

    <paginator
        v-bind:current-page="currentPage"
        v-bind:previous_page_url="previous_page_url"
        v-bind:next_page_url="next_page_url"
        @update-page="updatePage"
    />


    <div v-if="isDeleteAlertShown" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
      <delete-alert
          @close-delete-alert="closeDeleteAlert"
          @delete-item="removeTransaction"
      />
    </div>

  </main>
</template>

<script>
import BudgetPanel from "@/modules/budget/components/BudgetPanel.vue";
import AddTransactionButton from "@/modules/transactions/components/AddTransactionButton.vue";
import TransactionsTable from "@/modules/transactions/components/TransactionsTable.vue";
import SearchForm from "@/modules/core/components/SearchForm.vue";
import TransactionService from "@/modules/transactions/services/transactionService";
import BudgetService from "@/modules/budget/services/budgetService";
import {useI18n} from "vue-i18n";
import FilterButton from "@/modules/transactions/components/FilterButton.vue";
import Paginator from "@/modules/core/components/Paginator.vue";
import EntityQuantityPicker from "@/modules/transactions/components/EntityQuantityPicker.vue";
import TopBar from "@/modules/core/components/TopBar.vue";
import DeleteAlert from "@/modules/core/components/DeleteAlert.vue";

export default {
  name: "FrontDashboardView",
  components: {
    DeleteAlert,
    TopBar,
    EntityQuantityPicker,
    Paginator,
    FilterButton,
    BudgetPanel,
    TransactionsTable,
    AddTransactionButton,
    SearchForm
  },
  data() {
    return {
      isLoading: null,
      lang: useI18n().locale,
      budget: [],
      transactions: [],
      transactionService: new TransactionService(),
      budgetService: new BudgetService(),
      month: null,
      year: null,
      search: "",
      type: "all",
      currentPage: null,
      previous_page_url: null,
      next_page_url: null,
      perPage: null,
      isDeleteAlertShown: false,
      deleteTransactionId: null
    };
  },
  async created() {
    await this.initializeData();
  },
  watch: {
    lang: {
      handler: "fetchData"
    },
    $route: {
      handler: "initializeData"
    }
  },
  methods: {
    async initializeData() {
      this.isLoading = true;
      const currentRoute = this.$route;
      this.month = parseInt(currentRoute.params.month);
      this.year = parseInt(currentRoute.params.year);
      this.currentPage = 1;
      this.perPage = 10;
      await this.fetchData();
      this.isLoading = false;
    },
    async fetchData() {
      await Promise.all([this.fetchBudget(), this.fetchTransactions()]);
    },
    async fetchBudget() {
      this.budget = await this.budgetService.get(this.lang, this.month, this.year);
    },
    async fetchTransactions() {
      const data = await this.transactionService.getAll(this.lang, this.month, this.year, this.type, this.search, this.perPage, this.currentPage);
      this.transactions = data["data"];
      this.currentPage = data["current_page"];
      this.previous_page_url = data["prev_page_url"];
      this.next_page_url = data["next_page_url"];
    },
    updateSearch(searchText) {
      this.search = searchText;
      this.fetchTransactions();
    },
    async updateMonth(newMonth) {
      let updatedYear = this.year;

      if (newMonth > 12) {
        newMonth = 1;
        updatedYear += 1;
      } else if (newMonth < 1) {
        newMonth = 12;
        updatedYear -= 1;
      }

      this.$router.push({name: "dashboard", params: {month: newMonth, year: updatedYear}});
    },
    async updateType(type) {
      this.type = type;
      await this.fetchTransactions();
    },
    async updatePage(currentPage) {
      this.currentPage = currentPage;
      await this.fetchTransactions();
    },
    async updateQuantityEntries(quantity) {
      this.currentPage = 1;
      this.perPage = quantity;
      await this.fetchTransactions();
    },
    editTransaction(transactionNumber) {
      this.$router.push({name: 'edit-transaction', params: {id: transactionNumber}});
    },
    showDeleteAlert(transactionId) {
      this.deleteTransactionId = transactionId;
      this.isDeleteAlertShown = true;
    },
    closeDeleteAlert() {
      this.isDeleteAlertShown = false;
    },
    removeTransaction() {
      this.transactionService.delete(this.lang, this.deleteTransactionId).then(() => {
        this.fetchTransactions();
        this.$router.go();
      });
      this.isDeleteAlertShown = false;
    }
  }
}
</script>