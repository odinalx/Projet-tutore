<template>
  <div>
    <Navbar />
    <main class="p-8">
      <PresentationSection title="SDDDSection 1" 
      description="AAAALorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat." />
    </main>
  </div>
</template>

<script setup>
  import Navbar from "@/components/Accueil/Navbar.vue";
  import PresentationSection from "@/components/DetailSection/PresentationSection.vue";
</script>
