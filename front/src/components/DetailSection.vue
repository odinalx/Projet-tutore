<script>
import { organismeService } from '@/services/organismeService';
import { useOrganismeStore } from '@/stores/organismeStore';
export default {
  props: {
    section: {
      type: Object,
    },
  },
  data() {
    return {
      organismeName: ''
    }
  },
  async mounted() {
    const currentOrganisme = await organismeService.getOrganismeById(this.organismeStore.currentOrganisme.id);
    this.organismeName = currentOrganisme.nom;
  },
  setup() {
    const organismeStore = useOrganismeStore();
    return { organismeStore };
  },

};
</script>

<template>
  <div v-if="section" class="bg-white rounded-lg shadow-md overflow-hidden">
    <h1 class="text-3xl font-bold ml-8 mt-8">{{ section.nom }}</h1>
    <img v-if="section.image" :src="section.image" :alt="section.nom" class="w-full h-64 object-cover" />

    <div class="p-6">
      <div class="mb-6">
        <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
          {{ section.categorie || 'Non spécifiée' }}
        </span>
      </div>

      <p class="text-gray-700 mb-4">{{ section.description }}</p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div v-if="section.tarif" class="flex items-center">
          <span class="font-semibold mr-2">Tarif:</span>
          <span>{{ section.tarif }}€</span>
        </div>
        <div v-if="section.capacite" class="flex items-center">
          <span class="font-semibold mr-2">Capacité:</span>
          <span>{{ section.capacite }} personnes</span>
        </div>
        <div v-if="section.organisme_id" class="flex items-center">
          <span class="font-semibold mr-2">Organisme:</span>
          <span>{{ organismeName }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
