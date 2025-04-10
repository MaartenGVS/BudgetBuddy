<template>
  <div class="mb-6 flex items-center">
    <select id="entries"
            @change="updateQuantityEntries"
            class="shadow border rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none
            focus:ring-2 focus:ring-blue-300 transition duration-150 ease-in-out">
      <option value="1">1</option>
      <option value="5">5</option>
      <option value="10" selected>10</option>
      <option value="20">20</option>
    </select>
    <label for="entries" class=" px-3 block text-gray-700 text-sm font-bold mr-2">
      {{ $t('front-page.entries') }}
    </label>
  </div>
</template>

<script>
export default {
  name: "EntityQuantityPicker",
  emits: ['update-entity-quantity'],
  methods: {
    updateQuantityEntries(event) {
      this.$emit('update-entity-quantity', event.target.value);
    }
  }
}
</script>
