<template>

  <button @click="addTransaction"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition
      duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">
    {{ $t('front-page.add-transactions') }}
  </button>

</template>

<script>
export default {
  name: "AddTransactionButton",
  methods: {
    addTransaction() {
      this.$router.push({name: 'add-transaction'});
    }
  }
}
</script>
