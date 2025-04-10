<template>
  <form
      v-if="newTransaction"
      @submit.prevent="submitTransaction"
  >
    <div class="mb-4">
      <label for="category"
             class="block text-gray-700 font-medium mb-2">{{ $t('manage-transaction-view.category') + ':' }}</label>
      <div @click="showCategoryPicker"
           class="flex items-center justify-between border border-gray-300 rounded-lg p-3 cursor-pointer">
        <p class="text-gray-700">
          {{
            newTransaction.category_id === null ? $t('manage-transaction-view.select-category') : newTransaction.category.name
          }}
        </p>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
             stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
        </svg>
      </div>
    </div>

    <div class="mb-4">
      <label for="description" class="block text-gray-700 font-medium mb-2">{{
          $t('manage-transaction-view.description')
        }}</label>
      <div class="flex items-center p">

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
             stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
        </svg>

        <input type="text" id="description" v-model="newTransaction.description" required
               class="ml-1.5 px-3 form-input block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
    </div>

    <div class="mb-4">
      <label for="amount" class="block text-gray-700 font-medium mb-2">{{
          $t('manage-transaction-view.amount') + '€:'
        }}</label>
      <div class="flex items-center p">

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
             stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
        </svg>

        <input type="text" id="amount" name="amount" pattern="^\d+(\.\d{1,2})?$" v-model.number="newTransaction.amount"
               required
               class="ml-1.5 px-3 form-input block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
    </div>


    <div class="mb-4 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
           stroke="currentColor" class="w-6 h-6 mr-2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/>
      </svg>

      <div class="flex items-center">
        <label for="month" class="block text-gray-700 font-medium mr-2">{{
            $t('manage-transaction-view.month') + ':'
          }}</label>
        <input type="number" id="month" v-model="newTransaction.month" required max="12" min="1"
               class="px-3 py-2 form-input block w-24 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mr-4">
      </div>

      <div class="flex items-center">
        <label for="year" class="block text-gray-700 font-medium mr-2">{{
            $t('manage-transaction-view.year') + ':'
          }}</label>
        <input type="number" id="year" v-model="newTransaction.year" required min="1900" max="2099"
               class="px-3 py-2 form-input block w-24 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>

    </div>

    <button type="submit"
            class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 rounded-lg transition duration-300 ease-in-out">
      {{ $t('manage-transaction-view.submit-transaction') }}
    </button>
  </form>
  <category-picker
      v-if="showModal"
      v-bind:costType="costType"
      v-bind:previous-category-id="newTransaction.category_id"
      @close="closeModal"
  />
</template>

<script>
import CategoryPicker from "@/modules/categories/components/CategoryPicker.vue";
import {cloneDeep} from "lodash";

export default {
  name: "CreateEditTransactionForm",
  components: {
    CategoryPicker
  },
  emits: ['submit'],
  props: {
    transaction: {
      type: Object,
      required: false
    },
    costType: {
      type: String,
      required: false
    },
    selectedCategoryId: {
      type: Number,
      required: false
    }
  },
  data() {
    return {
      newTransaction: {},
      showModal: false
    }
  },
  watch: {
    transaction: {
      handler(newValue) {
        this.newTransaction = cloneDeep(newValue)
      },
      deep: true,
      immediate: true
    },
    selectedCategoryId: {
      handler(newValue) {
        this.newTransaction.category_id = newValue;
      }
    }
  },
  created() {
    const currentRoute = this.$route;

    if (!this.transaction) {
      this.newTransaction = {
        description: '',
        category_id: null,
        category: {
          name: null
        },
        amount: null,
        month: parseInt(currentRoute.params.month),
        year: parseInt(currentRoute.params.year),
        type: null
      };
    }
  },
  methods: {
    showCategoryPicker() {
      this.showModal = true;
    },
    closeModal(categoryId, categoryName) {
      if (categoryId && categoryName) {
        this.newTransaction.category_id = categoryId;
        this.newTransaction.category.name = categoryName;
      }
      this.showModal = false;
    },
    submitTransaction() {
      this.$emit("submit", this.newTransaction);
    }
  }
}
</script>