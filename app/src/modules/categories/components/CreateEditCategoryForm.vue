<template>
  <form
      v-if="newCategory.languages"
      @submit.prevent="submitCategory"
      class="grid grid-cols-2 gap-6">

    <div>

      <p class="text-red-500" v-for="(errorMessage, index) in errorMessages" :key="index">
        {{ errorMessage.join('') }}
      </p>

      <div class="mb-4">
        <label for="nameEnglish" class="block text-gray-700 text-sm font-bold mb-2">Name (English)</label>
        <input type="text"
               v-model="newCategory.languages.find(cat => cat.language === 'en').name"
               id="nameEnglish"
               name="nameEnglish"
               placeholder="Enter category name in English"
               required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      </div>

      <div class="mb-4">
        <label for="descriptionEnglish" class="block text-gray-700 text-sm font-bold mb-2">
          Description (English)
        </label>
        <textarea id="descriptionEnglish"
                  v-model="newCategory.languages.find(cat => cat.language === 'en').description"
                  name="descriptionEnglish" rows="3" placeholder="Enter description"
                  required
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
      </div>

      <div class="mb-4">
        <label for="categoryType" class="block text-gray-700 text-sm font-bold mb-2">Category Type (English)</label>
        <select id="categoryType"
                v-model="newCategory.languages.find(cat => cat.language === 'en').category_type"
                name="categoryType" required
                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <option value="">Select a type</option>
          <option value="income">Income</option>
          <option value="expense">Expense</option>
        </select>
      </div>
    </div>

    <div>
      <div class="mb-4">
        <label for="nameDutch" class="block text-gray-700 text-sm font-bold mb-2">Name (Dutch)</label>
        <input type="text"
               v-model="newCategory.languages.find(cat => cat.language === 'nl').name"
               id="nameDutch" name="nameDutch" placeholder="Enter category name in Dutch" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
      </div>

      <div class="mb-4">
        <label for="descriptionDutch" class="block text-gray-700 text-sm font-bold mb-2">
          Description (Dutch)
        </label>
        <textarea id="descriptionDutch"
                  v-model="newCategory.languages.find(cat => cat.language === 'nl').description"
                  name="descriptionDutch" rows="3" placeholder="Enter description" required
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
      </div>

      <div class="mb-4">
        <label for="categoryType" class="block text-gray-700 text-sm font-bold mb-2">Category Type (Dutch)</label>
        <select id="categoryType"
                v-model="newCategory.languages.find(cat => cat.language === 'nl').category_type"
                name="categoryType" required
                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <option value="">Select a type</option>
          <option value="inkomen">Inkomen</option>
          <option value="uitgave">Uitgave</option>
        </select>
      </div>
    </div>


    <div class="col-span-2">

      <div class="flex items-center mb-8">
        <input type="checkbox"
               v-model="isActive"
               id="activeSwitch" name="active" class="form-checkbox h-5 w-5 text-blue-600">
        <label for="activeSwitch" class="ml-2 block text-gray-700 text-sm font-bold">Active</label>
      </div>

      <div class="mb-8">
        <label for="imageUpload" class="block text-gray-700 text-sm font-bold mb-3">Category Image</label>
        <div class="flex ">
          <div class="relative ">
            <input type="file" id="imageUpload" name="image"
                   accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp, image/svg, image/ico"
                   class="block text-sm text-gray-700
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   @change="handleImageUpload">
          </div>
          <div class="w-48 h-48 relative overflow-hidden">
            <img v-if="newCategory.image" :src="newCategory.image" alt="Selected Image"
                 class="absolute inset-0 w-full h-full object-contain">
            <img v-else-if="newCategory.icon_url" :src="newCategory.icon_url" alt="Selected Image"
                 class="absolute inset-0 w-full h-full object-contain">
          </div>
        </div>
      </div>


    </div>

    <div class="col-span-2">
      <button type="submit"
              class="w-2/12 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Submit
      </button>
    </div>
  </form>
</template>

<script>
import {cloneDeep} from "lodash";

export default {
  name: "CreateEditCategoryForm",
  emits: ['submit-category'],
  props: {
    category: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      newCategory: {},
      errorMessages: []
    }
  },
  watch: {
    category: {
      handler(newValue) {
        this.newCategory = cloneDeep(newValue)
      },
      deep: true,
    }
  },
  created() {
    if (!this.category) {
      this.newCategory = {
        active: 1,
        image: '',
        languages: [
          {language: 'en', name: '', description: '', category_type: ''},
          {language: 'nl', name: '', description: '', category_type: ''}
        ]
      }
    }
  },
  computed: {
    isActive: {
      get() {
        return this.newCategory.active === 1;
      },
      set(value) {
        this.newCategory.active = value ? 1 : 0;
      }
    }
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (e) => {
        this.newCategory.image = e.target.result;
      };
    },
    submitCategory() {
      this.errorMessages = [];
      if (!this.newCategory.icon_url && !this.newCategory.image) {
        this.errorMessages.push(['Please select an image for the category']);
        return;
      }
      this.$emit('submit-category', this.newCategory);
    }
  }
}
</script>

