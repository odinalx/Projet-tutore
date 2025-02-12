<!-- <script setup lang="ts">
// Le router est déjà configuré dans main.ts
</script> -->

<template>
  <div class="min-h-screen bg-gray-100">
    <!-- <nav class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-3">
        <router-link
          to="/accueil"
          class="text-gray-800 hover:text-gray-600 font-medium"
        >
          Accueil
        </router-link>
      </div>
    </nav> -->
    <!-- <navbar/> -->

    <router-view />
  </div>
</template>

<script setup>
import Navbar from "@/components/Accueil/Navbar.vue";
import Banner from "@/components/Accueil/Banner.vue";
import SectionsList from "@/components/Accueil/SectionsList.vue";
</script>
