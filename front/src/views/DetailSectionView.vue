<script>
import { useAuthStore } from '@/stores/authStore';
import { useSectionStore } from '@/stores/sectionStore';
import DetailSection from '@/components/DetailSection.vue';
import ResponsableSectionPanel from '@/components/ResponsableSectionPanel.vue';
import ErrorBox from '@/components/ErrorBox.vue';
import LoadingBox from '@/components/LoadingBox.vue';
import utilsMixin from '@/mixins/utilsMixin';

export default {
  mixins: [utilsMixin],
  data() {
    return {
      error: null,
      isLoading: true,
    };
  },
  setup() {
    const authStore = useAuthStore();
    const sectionStore = useSectionStore();
    return { authStore, sectionStore };
  },
  components: {
    DetailSection,
    ResponsableSectionPanel,
    ErrorBox,
    LoadingBox,
  },
  computed: {
    currentSection() {
      return this.sectionStore.currentSection;
    },
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
  },
  async mounted() {
    try {
      await this.sectionStore.fetchSectionById(this.$route.params.id);
    } catch (error) {
      this.error = error.message;
    } finally {
      this.isLoading = false;
    }
  },
};
</script>

<template>
  <div class="container mx-auto p-6">
    <ErrorBox v-if="error && !isLoading" :message="error" class="mt-24" />
    <LoadingBox :is-visible="isLoading" class="mt-24" />  
    <!--Boite principale-->   
    <div v-if="!isLoading && !error && currentSection">
      <button @click="goBack" class="text-blue-500 hover:underline">
        &larr; Retour
      </button>
      <DetailSection :section="currentSection" class="mt-8" />
      <ResponsableSectionPanel :is-visible="checkUserRole()" :currentSection="currentSection" class="mt-8" />
    </div>
  </div>
</template>

