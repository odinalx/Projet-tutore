import { defineStore } from "pinia";
import { organismeService } from "@/services/organismeService";

export const useOrganismeStore = defineStore("organismes", {
  state: () => ({
    organismes: [],
    currentOrganisme: null,
    error: null,
  }),

  actions: {
    async fetchOrganismes() {
      try {
        const response = await organismeService.getOrganismes();
        this.organismes = response.data;
      } catch (error) {
        this.error = error.message;
      }
    },

    async fetchOrganisme(id) {
      try {
        const response = await organismeService.getOrganismeById(id);
        this.currentOrganisme = response;
      } catch (error) {
        this.error = error.message;
      }
    }
  },
});
