<template>
  <div>
      <div class="bg-white max-w-md mx-auto p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Delete Confirmation</h2>
        <p class="mb-6">Are you sure you want to delete this item?</p>
        <div class="flex justify-end">
          <button @click="closeDeleteAlert" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
            Cancel
          </button>
          <button @click="deleteItem" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Delete
          </button>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  name: "DeleteAlert",
  emits: ["close-delete-alert", "delete-item"],
  data() {
    return {
      showDeleteAlert: false
    };
  },
  methods: {
    closeDeleteAlert(){
      this.$emit("close-delete-alert")
    },
    deleteItem() {
      this.$emit("delete-item")
    }
  }
}
</script>