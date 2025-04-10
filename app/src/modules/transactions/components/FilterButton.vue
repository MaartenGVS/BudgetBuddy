<template>
  <div class="mb-6 flex items-center">
    <label for="filter" class="block text-gray-700 text-sm font-bold mr-2">{{ $t('front-page.filter') }}</label>
    <select id="filter"
            @change="updateType"
            class="shadow border rounded-lg py-2 px-3 text-gray-700 leading-tight
            focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-150 ease-in-out">
      <option :value="$t('front-page.all')">{{ $t('front-page.all') }}</option>
      <option :value="$t('front-page.incomes')">{{ $t('front-page.incomes') }}</option>
      <option :value="$t('front-page.expenses')">{{ $t('front-page.expenses') }}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: "FilterButton",
  emits: ['filter-switch'],
  methods: {
    updateType(event) {
      this.$emit('filter-switch', event.target.value);
    }
  }
}
</script>

