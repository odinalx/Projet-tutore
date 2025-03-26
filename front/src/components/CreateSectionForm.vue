<script>
import { sectionService } from '@/services/sectionService';
import utilsMixin from '@/mixins/utilsMixin';
import ErrorBox from './ErrorBox.vue';
import { useOrganismeStore } from '@/stores/organismeStore';

export default {
    mixins: [utilsMixin],
    components: {
        ErrorBox,
    },
    data() {
        return {
            error: null,
            formData: {
                nom: '',
                description: '',
                categorie: '',
                capacite: '',
                tarif: '',
                organisme_id: '',
            },
        };
    },
    methods: {
        async handleSubmit() {
            try {
                await sectionService.createSection(this.formData);
                this.$router.push({ name: 'sections' });
            } catch (err) {
                this.error = err.message;
            }
        },
    },
    computed: {
        organismes() {
            return this.organismeStore.organismes;
        },
    },
    async mounted() {
        await this.organismeStore.fetchOrganismes();

        if (this.organismeStore.currentOrganisme) {
            this.formData.organisme_id = this.organismeStore.currentOrganisme.id;
        }
    },

    setup() {
        const organismeStore = useOrganismeStore();
        return { organismeStore };
    },
};
</script>


<template>
    <ErrorBox v-if="error" :message="error" class="mt-24" />
    <div v-if="checkUserRole() && !error"
        class="bg-white shadow-lg rounded-2xl p-8 max-w-lg mx-auto mt-10 border border-gray-200">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Créer une nouvelle section</h2>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div class="flex flex-col">
                <label for="nom" class="text-sm font-medium text-gray-700 mb-1">Nom de la section</label>
                <input type="text" id="nom" v-model="formData.nom" required class="input-field"
                    placeholder="Ex: Section Sport" />
            </div>

            <div class="flex flex-col">
                <label for="description" class="text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" v-model="formData.description" required class="input-field" rows="3"
                    placeholder="Détaillez votre section ici..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <label for="categorie" class="text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <input type="text" id="categorie" v-model="formData.categorie" required class="input-field"
                        placeholder="Ex: Sport, Musique" />
                </div>

                <div class="flex flex-col">
                    <label for="capacite" class="text-sm font-medium text-gray-700 mb-1">Capacité</label>
                    <input type="number" id="capacite" v-model="formData.capacite" required class="input-field"
                        placeholder="Ex: 30" />
                </div>
            </div>

            <div class="grid gap-4">
                <div class="flex flex-col">
                    <label for="tarif" class="text-sm font-medium text-gray-700 mb-1">Tarif</label>
                    <input type="number" id="tarif" v-model="formData.tarif" required class="input-field"
                        placeholder="Ex: 150" />
                </div>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="bg-blue-600 text-white py-2 px-4 rounded-md w-full hover:bg-blue-700 transition duration-300">
                    Ajouter la section
                </button>
            </div>
        </form>
    </div>
    <div v-else-if="!error" class="container mx-auto p-6">
        <div class="flex items-center justify-center space-x-12">
            <RouterLink to="/" class="text-blue-600 hover:underline flex items-center">
                <span class="mr-1">&larr;</span> Retour à l'accueil
            </RouterLink>
            <ErrorBox :message="'Vous n\'avez pas les droits pour accéder à cette page.'" />
        </div>
    </div>
</template>


<style scoped>
.input-field {
    @apply w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200;
}
</style>
