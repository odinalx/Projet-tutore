<script>
import { encadrantService } from '@/services/encadrantService';
import { sectionService } from '@/services/sectionService';
import { useAuthStore } from '@/stores/authStore';
import ErrorBox from './ErrorBox.vue';
import LoadingBox from './LoadingBox.vue';

export default {
    props: {
        isVisible: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        ErrorBox,
        LoadingBox,
    },
    data() {
        return {
            encadrants: [],
            error: null,
            isLoading: true,
        };
    },
    methods: {
        async fetchEncadrants() {
            try {
                this.encadrants = await encadrantService.getEncadrantsByUser(this.authStore.user.id);

                for (let encadrant of this.encadrants) {
                    const section = await sectionService.getSectionById(encadrant.section_id);
                    encadrant.section_nom = section.nom; 
                }
            } catch (error) {
                this.error = error.message;
            } finally {
                this.isLoading = false;
            }
        },

        async removeEncadrantFromSection(encadrantId, sectionId) {
            try {
                await encadrantService.removeEncadrantFromSection(encadrantId, sectionId);
                await this.fetchEncadrants();
            } catch (error) {
                this.error = error.message;
            }
        },
    },
    async mounted() {
        await this.fetchEncadrants();
    },
    setup() {
        const authStore = useAuthStore();
        return { authStore };
    },
}
</script>

<template>
    <div v-if="isVisible" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition p-6">
        <h2 class="text-xl font-semibold mb-4">Mes encadrants</h2>

        <LoadingBox :isVisible="isLoading" />
        <ErrorBox v-if="error && !isLoading" :message="error" />

        <div v-if="!isLoading && !error" class="space-y-4">
            <div 
                v-for="encadrant in encadrants"
                :key="encadrant.id"
                class="p-4 border rounded-lg shadow-sm bg-gray-50 grid grid-cols-1 md:grid-cols-5 gap-4">

                <div class="flex flex-col items-start">
                    <p class="text-sm text-gray-500">Nom</p>
                    <p class="font-medium">{{ encadrant.nom }}</p>
                </div>

                <div class="flex flex-col items-start">
                    <p class="text-sm text-gray-500">Prénom</p>
                    <p class="font-medium">{{ encadrant.prenom }}</p>
                </div>

                <div class="flex flex-col items-start break-words">
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ encadrant.email }}</p>
                </div>

                <div class="flex flex-col items-start break-words">
                    <p class="text-sm text-gray-500">Section</p>
                    <p class="font-medium">{{ encadrant.section_nom }}</p>
                </div>

                <div class="flex justify-center items-center">
                    <button 
                        @click="removeEncadrantFromSection(encadrant.section_id, encadrant.id)"
                        class="text-red-600 hover:text-red-800 bg-red-100 py-2 px-4 rounded-lg transition duration-300 w-full md:w-auto">
                        Retirer
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.break-words {
    word-break: break-word; 
    overflow-wrap: break-word; 
    white-space: normal;  
}
</style>



