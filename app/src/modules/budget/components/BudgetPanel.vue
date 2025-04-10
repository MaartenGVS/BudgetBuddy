<template>
  <div class="mt-16 relative flex items-center justify-between bg-white p-6 rounded-lg
  shadow-lg max-w-2xl w-full overflow-visible h-full pb-12">

    <button @click="goPreviousMonth" class="p-2 rounded-full hover:bg-gray-200 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
           class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
      </svg>
    </button>

    <div class="text-center z-10">
      <h2 class="text-xl font-semibold text-gray-800">{{ $t('front-page.title') }}</h2>
      <h3 class="text-lg text-gray-600">{{ budget['month'] + ' ' + budget['year']}}</h3>
    </div>

    <button @click="goNextMonth" class="p-2 rounded-full hover:bg-gray-200 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
           class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
      </svg>
    </button>

    <div class="absolute flex justify-center w-full bottom-[-2.5rem] left-0">
      <div class="text-white font-bold rounded-full w-20 h-20 flex items-center justify-center text-lg"
           :class="budget['budget'] < 0 ? 'bg-red-500' : 'bg-emerald-500'">
        {{ "€ " + budget["budget"] }}
      </div>
    </div>

  </div>

</template>


<script>
export default {
  name: "BudgetPanel",
  emits: ["update-previous-month", "update-next-month"],
  props: {
    month: {
      type: Number,
      required: true
    },
    budget: {
      type: Object,
      required: true
    }
  },
  methods: {
    goPreviousMonth() {
      this.$emit("update-previous-month", this.month - 1);
    },
    goNextMonth() {
      this.$emit("update-next-month", this.month + 1);
    }
  }
}
</script>
