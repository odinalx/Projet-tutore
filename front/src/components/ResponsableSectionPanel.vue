<script>
import { sectionService } from '@/services/sectionService';
import { useOrganismeStore } from '@/stores/organismeStore';

export default {
  props: {
    isVisible: {
      type: Boolean,
      default: false,
    },
    currentSection: {
      type: Object,
    },
  },
  data() {
    return {
      error: null,
      showConfirmation: false,
      showModificationConfirmation: false,
      formData: {
        nom: null,
        description: null,
        categorie: null,
        capacite: null,
        tarif: null,
        organisme_id: "",
      },
    };
  },
  computed: {
    organismes() {
      return this.organismeStore.organismes;
    },
    updatedFields() {
      const updatedFields = {};
      for (const key in this.formData) {
        if (this.formData[key] !== this.currentSection[key]) {
          updatedFields[key] = this.formData[key];
        }
      }
      return updatedFields;
    },
  },
  methods: {
    confirmDeletion() {
      this.showConfirmation = true;
    },
    async deleteSection() {
      try {
        await sectionService.deleteSection(this.currentSection.id);
        this.$router.push('/sections');
      } catch (error) {
        this.error = error.message;
      } finally {
        this.showConfirmation = false;
      }
    },
    confirmModification() {
      this.showModificationConfirmation = true;
    },
    async handleUpdateSection() {
      if (Object.keys(this.updatedFields).length > 0) {
        this.confirmModification();
      } else {
        this.$router.push('/sections');
      }
    },
    async applyUpdateSection() {
      try {
        await sectionService.updateSection(this.currentSection.id, this.updatedFields);
        this.$router.push('/sections');
      } catch (error) {
        this.error = error.message;
      } finally {
        this.showModificationConfirmation = false;
      }
    },
    cancelModification() {
      this.showModificationConfirmation = false;
    },
    cancelDeletion() {
      this.showConfirmation = false;
    },
  },
  setup() {
    const organismeStore = useOrganismeStore();
    return { organismeStore };
  },
  async mounted() {
    await this.organismeStore.fetchOrganismes();
  },
};
</script>

<template>
  <div v-if="isVisible" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition p-6">
    <div v-if="error">
      <p class="text-red-500 text-xl">{{ error }}</p>
    </div>
    <form @submit.prevent="handleUpdateSection" class="space-y-4">
      <h1 class="text-2xl font-semibold text-center">Gestion de la section {{ this.currentSection.nom }}</h1>
      <!--Première ligne-->
      <div class="flex space-x-4 justify-center">
        <input id="nom" v-model="formData.nom" type="text" placeholder="Nouveau nom"
          class="w-full p-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <input id="description" v-model="formData.description" type="text" placeholder="Nouvelle description"
          class="w-full p-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <!--Deuxième ligne-->
      <div class="flex space-x-4 justify-center">
        <input id="categorie" v-model="formData.categorie" type="text" placeholder="Nouvelle catégorie"
          class="w-full p-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <input id="capacite" v-model="formData.capacite" type="text" placeholder="Nouvelle capacité"
          class="w-full p-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <!--Troisième ligne-->
      <div class="flex space-x-4 justify-center">
        <input id="tarif" v-model="formData.tarif" type="text" placeholder="Nouveau tarif"
          class="w-full p-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <select id="organisme" v-model="formData.organisme_id"
          class="w-full p-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="" disabled hidden>Sélectionnez un organisme</option>
          <option v-for="organisme in organismes" :key="organisme.id" :value="organisme.id">
            {{ organisme.nom }}
          </option>
        </select>
      </div>

      <div class="flex space-x-4 pt-8">
        <button type="submit" class="w-full bg-blue-600 text-white p-4 rounded-md hover:bg-blue-700 transition">
          <span>Modifier la section</span>
        </button>

        <button @click="confirmDeletion" type="button"
          class="w-full bg-red-600 text-white p-4 rounded-md hover:bg-red-700 transition">
          Supprimer définitivement cette section
        </button>
      </div>
    </form>
    <div v-if="showConfirmation" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg p-6">
        <p class="mb-4">Êtes-vous sûr de vouloir supprimer cette section ?</p>
        <div class="flex justify-end">
          <button @click="cancelDeletion" class="mr-2 text-gray-600 hover:text-gray-800">Annuler</button>
          <button @click="deleteSection"
            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Confirmer</button>
        </div>
      </div>
    </div>
    <!-- Partie Modification Confirmation -->
    <div v-if="showModificationConfirmation"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg p-6">
        <p class="mb-4">Confirmez-vous les modifications suivantes ?</p>
        <ul>
          <li v-for="(value, key) in updatedFields" :key="key">
            {{ key }}: {{ value }}
          </li>
        </ul>
        <div class="flex justify-end">
          <button @click="cancelModification" class="mr-2 text-gray-600 hover:text-gray-800">Annuler</button>
          <button @click="applyUpdateSection"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Confirmer</button>
        </div>
      </div>
    </div>
  </div>
</template>