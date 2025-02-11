<template>
    <section class="py-8 text-center">
      <div class="flex justify-center gap-8">
        <SectionCard v-for="section in sections" :key="section.id" :title="section.name" />
      </div>
      <Button class="mt-6 bg-gray-800 text-white hover:bg-gray-700">
        Voir toutes les sections
      </Button>
    </section>
  </template>
  
  <script setup>
  import SectionCard from "./SectionCard.vue";
  import { Button } from "@/components/ui/button";
  import { ref } from "vue";
  
  const sections = ref([
    { id: 1, name: "Sections 1" },
    { id: 2, name: "Sections 2" },
    { id: 3, name: "Sections 3" },
  ]);
  </script>
  