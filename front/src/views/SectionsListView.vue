
<template>
  <div>
    <Navbar />
    <main class="p-8">
      <h1 class="text-2xl font-bold text-center">Liste des Sections</h1>
      <div class="mt-6 space-y-6">
        <SectionsList category="Sport" :sections="['Sections 1', 'Sections 2', 'Sections 3']" />
        <SectionsList category="Activité manuel" :sections="['Sections 4', 'Sections 5', 'Sections 6']" />
      </div>
    </main>
  </div>
</template>

<script setup>
    import Navbar from "@/components/Accueil/Navbar.vue";
    import Banner from "@/components/Accueil/banner.vue";
    import SectionsList from "@/components/Accueil/SectionsList.vue";
</script>
