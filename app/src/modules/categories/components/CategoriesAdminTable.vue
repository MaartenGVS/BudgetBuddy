<template>
  <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
    <thead>
    <tr class="text-left">
      <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100" @click="changeIdSortingDirection">
        {{ isSortingIdAsc ? 'Id &#9650;' : 'Id &#9660;' }}
      </th>
      <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100">Icon</th>
      <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100" @click="changeNameSortingDirection">
        {{ isSortingNameAsc ? 'Name &#9650;' : 'Name &#9660;' }}
      </th>
      <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100">Description</th>
      <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100">Type</th>
      <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100">Status</th>
      <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100">Actions</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="category in categories" :key="category.id"
        class="bg-white border-b hover:bg-gray-100 transition duration-150 ease-in-out">
      <td class="py-3 px-3">{{ category["id"] }}</td>
      <td class="py-3 px-3" v-if="category.icon_url === 'noUrl'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
        </svg>
      </td>
      <td class="py-3 px-3" v-else>
        <img :src="category.icon_url" alt="icon" class="w-6 h-6 mr-2">
      </td>


      <td class="py-3 px-3">{{ category["name"] }}</td>
      <td class="py-3 px-3">{{ category["description"] }}</td>
      <td class="py-3 px-3">{{ category["category_type"] }}</td>
      <td class="py-3 px-3">{{ category["active"] === 1 ? "active" : "disabled" }}</td>
      <td class="py-3 px-3">
           <span @click="edit" :id="category['id']"
               class="text-blue-500 hover:text-blue-700 cursor-pointer inline-block mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round"
                       d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
            </svg>
          </span>

        <span @click="showDeleteAlert" :id="category['id']"
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
</template>

<script>
export default {
  name: "CategoriesAdminTable",
  emits: [
    "edit-category",
    "show-delete-alert",
    "change-id-sorting-direction",
    "change-name-sorting-direction"
  ],
  props: {
    categories: {
      type: Array,
      required: true
    },
    isSortingIdAsc: {
      type: Boolean,
      required: true
    },
    isSortingNameAsc: {
      type: Boolean,
      required: true
    },
  },

  methods: {
    edit(e) {
      this.$emit('edit-category', e.target.closest('span').id);
    },
    showDeleteAlert(e) {
      this.$emit('show-delete-alert', e.target.closest('span').id);
    },
    changeIdSortingDirection() {
      console.log(this.categories);
      this.$emit("change-id-sorting-direction");
    },
    changeNameSortingDirection() {
      this.$emit("change-name-sorting-direction");
    }
  }
}
</script>

