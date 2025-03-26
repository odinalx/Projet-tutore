import { defineStore } from 'pinia';
import { sectionService } from '../services/sectionService';

export const useSectionStore = defineStore('sections', {
  state: () => ({
    sections: [],
    currentSection: null,
    error: null,
  }),

  actions: {
    async fetchSectionsByOrganisme(id) {
      try {
        const response = await sectionService.getSectionsByOrganisme(id);
        this.sections = response.sections;
      } catch (error) {
        this.error = error.message;
      }
    },

    async fetchSectionById(id) {
      try {
        const section = await sectionService.getSectionById(id);
        this.currentSection = section;
      } catch (error) {
        this.error = error.message;
      }
    },
  },
});
