<script>
import { useSectionStore } from '@/stores/sectionStore';
import { useOrganismeStore } from '@/stores/organismeStore';
import SectionCard from '@/components/SectionCard.vue';
import utilsMixin from '@/mixins/utilsMixin';
import LoadingBox from '@/components/LoadingBox.vue';
import ErrorBox from '@/components/ErrorBox.vue';

export default {
  mixins: [utilsMixin],
  components: {
    SectionCard,
    ErrorBox,
    LoadingBox
  },
  data() {
    return {
      isLoading: true,
      error: null,
    };
  },
  setup() {
    const sectionStore = useSectionStore();
    const organismeStore = useOrganismeStore();
    return { sectionStore, organismeStore };
  },
  methods: {
    async fetchSections() {
      try {
        await this.sectionStore.fetchSectionsByOrganisme(this.organismeStore.currentOrganisme.id);
      } catch (err) {
        this.error = this.sectionStore.error;
      } finally {
        this.isLoading = false;
      }
    },
  },
  mounted() {
    this.fetchSections();
  },
};
</script>

<template>
  <div class="container mx-auto p-6">
    <div class="flex items-center mb-8">
      <div class="w-1/4">
        <RouterLink to="/" class="text-blue-600 hover:underline flex items-center">
          <span class="mr-1">&larr;</span> Retour à l'accueil
        </RouterLink>
      </div>
      <h1 class="text-3xl font-bold text-center w-2/4">Toutes nos sections</h1>
      <RouterLink v-if="checkUserRole()" to="/create-section" class="bg-green-600 text-white py-2 px-4 rounded-md">
        + Ajouter une nouvelle section
      </RouterLink>
    </div>

    <LoadingBox :isVisible="isLoading" class="mt-24" />
    <ErrorBox v-if="error && !isLoading" :message="error" class="mt-24" />
    
    <div v-if="!isLoading && !error" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <SectionCard v-for="section in sectionStore.sections" :key="section.id" :section="section" />
    </div>
  </div>
</template>
