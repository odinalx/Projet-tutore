<template>
  <Navbar/>
  <div>
    <Banner />
    <SectionsList category="Sport" :sections="['Sections 1', 'Sections 2', 'Sections 3']" />
  </div>
  <section class="py-8 text-center">
    <button class="mt-6 bg-gray-800 text-white hover:bg-gray-700">
      <RouterLink to="/sections"  >
        Voir toutes les sections
      </RouterLink> 
    </button> 
  </section>
</template>

<script setup>
import router from "@/router";
import Banner from "@/components/Accueil/banner.vue";
import Navbar from "@/components/Accueil/Navbar.vue";
import SectionsList from "@/components/Accueil/SectionsList.vue";
</script>
