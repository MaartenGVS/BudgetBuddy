<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">

      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr>
        <th scope="col" class="px-6 py-3 cursor-pointer">{{ $t('front-page.name') }}</th>
        <th scope="col" class="px-6 py-3 cursor-pointer">{{ $t('front-page.description') }}</th>
        <th scope="col" class="px-6 py-3 cursor-pointer">{{ $t('front-page.amount') }}</th>
        <th scope="col" class="px-6 py-3">{{ $t('front-page.actions') }}</th>
      </tr>
      </thead>

      <tbody>
      <tr v-for="transaction in transactions"
          class="bg-white border-b hover:bg-gray-100 transition duration-150 ease-in-out">
        <td class="px-6 py-4">{{ transaction["category_name"] }}</td>
        <td class="px-6 py-4 max-w-80">{{ transaction["description"] }}</td>
        <td class="px-6 py-4">{{ '€ ' + transaction["amount"] }}</td>
        <td class="px-6 py-4">

          <span @click="edit" :id="transaction['transaction_number']"
                class="text-blue-500 hover:text-blue-700 cursor-pointer inline-block mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round"
                       d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
            </svg>
          </span>

          <span @click="remove" :id="transaction['transaction_number']"
                class="text-red-500 hover:text-red-700 cursor-pointer inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor"
                 class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round"
                       d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
            </svg>
          </span>

        </td>
      </tr>
      </tbody>

    </table>
  </div>

</template>

<script>
export default {
  name: "TransactionsTable",
  emits: ['edit-transaction', 'remove-transaction'],
  props: {
    transactions: {
      type: Array,
      required: true
    }
  },
  methods: {
    edit(e) {
      this.$emit('edit-transaction', e.target.closest('span').id);
    },
    remove(e) {
      this.$emit('remove-transaction', e.target.closest('span').id);
    }
  }
}
</script>

