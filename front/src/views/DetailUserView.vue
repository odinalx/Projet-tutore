<template>
    <div>
      <Navbar />
      <main class="p-8">
        <DetailUserComponent/>
      </main>
    </div>
  </template>
  
  <script setup>
    import Navbar from "@/components/Accueil/Navbar.vue";
    import DetailUserComponent from "@/components/DetailUser/DetailUserComponent.vue";
    
  </script>
  