<template>
  <TopBar/>
  <main class="w-full mx-auto py-5 h-[calc(100vh-5rem)]">

    <h1 class="ml-6">
      {{ $t('home.title') }}
    </h1>

  </main>

</template>

<script setup>
import TopBar from "@/modules/core/components/TopBar.vue";
</script>