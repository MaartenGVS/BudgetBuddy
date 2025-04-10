<template>
  <div>
    <label for="transactionType" class="block text-gray-700 font-medium mb-2">{{ $t('manage-transaction-view.transaction-type') + ':' }}</label>
    <select id="transactionType"
            v-bind:value="category_type"
            @change="updateType"
            class="px-3 py-2 form-select block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700">
      <option value="">{{ $t('manage-transaction-view.select-type') }}</option>
      <option :value="$t('manage-transaction-view.incomes')">{{ $t('manage-transaction-view.income') }}</option>
      <option :value="$t('manage-transaction-view.expenses')">{{ $t('manage-transaction-view.expense') }}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: "CategoryTypeFilterButton",
  emits: ['filter-switch'],
  props: {
    category_type: {
      type: String,
      required: false
    }
  },
  methods: {
    updateType(event) {
      this.$emit('filter-switch', event.target.value);
    }
  }
}
</script>