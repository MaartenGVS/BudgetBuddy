<template>
  <div class="paginator flex items-center justify-center space-x-4 py-4">

    <button
        :class="{ 'cursor-not-allowed opacity-50': previous_page_url === null }"
        :disabled="previous_page_url === null"
        @click="previousPage"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:bg-gray-400"
    >
      Previous
    </button>

    <span class="text-gray-800 font-medium">{{ currentPage }}</span>

    <button
        :class="{ 'cursor-not-allowed opacity-50': next_page_url === null }"
        :disabled="next_page_url === null"
        @click="nextPage"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:bg-gray-400"
    >
      Next
    </button>
  </div>
</template>


<script>
export default {
  name: "Paginator",
  props: {
    currentPage: {
      type: Number,
      required: true
    },
    previous_page_url: {
      type: String,
      required: false
    },
    next_page_url: {
      type: String,
      required: false
    }
  },
  methods: {
    previousPage() {
      this.$emit("update-page", this.currentPage - 1);
    },
    nextPage() {
      this.$emit("update-page", this.currentPage + 1);
    }
  }
}
</script>