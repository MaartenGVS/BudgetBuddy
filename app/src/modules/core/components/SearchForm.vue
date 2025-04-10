<template>
  <form @submit.prevent="updateSearch" class="w-full md:w-1/2 lg:w-1/3">

    <label for="search-input" class="block text-gray-700 text-sm font-bold mb-2">{{ $t('front-page.search') + ':' }}</label>

    <div class="flex items-center">
      <input type="text" id="search-input" v-model="searchText" placeholder="Search transactions"
             class="shadow appearance-none border rounded-lg flex-grow py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-150 ease-in-out">

      <button type="submit" class="ml-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
        </svg>
      </button>

    </div>

  </form>
</template>


<script>
import {cloneDeep} from "lodash";

export default {
  name: "SearchForm",
  emits: ["update-search"],
  props: {
    search: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      searchText: null
    };
  },
  watch: {
    search: {
      handler(newValue) {
        this.searchText = cloneDeep(newValue);
      },
      immediate: true,
    }
  },
  methods: {
    updateSearch() {
      this.$emit("update-search", this.searchText);
    }
  }
}
</script>
