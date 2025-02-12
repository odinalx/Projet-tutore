<template>
  <div>
    <h2 class="text-xl font-bold">{{ category }}</h2>
    <div class="grid grid-cols-3 gap-4 mt-4">      
      <SectionCard v-for="(section, index) in sections" :key="index" :title="section" />    
    </div>
  </div>
</template>

<script setup>

  import SectionCard from "./SectionCard.vue";
  defineProps({ category: String, sections: Array });
</script>