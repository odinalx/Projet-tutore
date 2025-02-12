<template>
    <div>
      <Navbar />
      <main class="p-8">
        <InscriptionComponent/>
      </main>
    </div>
  </template>
  
  <script setup>
    import Navbar from "@/components/Accueil/Navbar.vue";
    import InscriptionComponent from "@/components/Inscription/InscriptionComponent.vue";    
  </script>
  