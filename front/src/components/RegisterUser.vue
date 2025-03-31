<script>
import { sectionService } from '@/services/sectionService';
import { useSectionStore } from '@/stores/sectionStore';
import { useOrganismeStore } from '@/stores/organismeStore';
import { useAuthStore } from "@/stores/authStore";
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
            users: [],
            selectedUserId: null,
            selectedRole: null,
            selectedSection: null,
            roles: {
                0: "Adhérent",
                5: "Encadrant",
                10: "Responsable"
            },
            error: null,
            isLoading: false,
        };
    },
    computed : {
        filteredRoles() {
            const userRole = this.authStore.user.role; 

            if (userRole === 15) return this.roles; 
            if (userRole === 10) return { 0: "Adhérent", 5: "Encadrant" }; 
            if (userRole === 5) return { 0: "Adhérent" }; 

            return {};
        }
    },
    
    methods: {
        async fetchUsers() {
            try {
                // Utilisateur de la BD en dur. TODO : ces utilisateurs doivent etre ceux qui font une demande d'inscription pour cette section
                this.users = [
                    { id: "3ea9853e-fbf3-4fdc-9293-a8c9cc394953", mail: "encadrant@test.com" },
                    { id: "11363c2b-cd17-4620-bee8-5c86b0c0515c", mail: "adherent@test.com" }
                ];
            } catch (error) {
                this.error = error.message;
            } finally {
                this.isLoading = false;
            }
        },
        async fetchSections() {
            try {
                await this.sectionStore.fetchSectionsByOrganisme(this.organismeStore.currentOrganisme.id);
            } catch (err) {
                this.error = this.sectionStore.error;
            } finally {
                this.isLoading = false;
            }
        },

        async addUserToSection(sectionId) {
            this.isLoading = true;
            this.error = null;
            try {
                await sectionService.addUserToSection(sectionId, this.selectedUserId, this.selectedRole);
                alert("Utilisateur ajouté avec succès !");
                this.selectedUserId = null;
                this.selectedRole = null;
            } catch (error) {
                this.error = error.message;
            } finally {
                this.isLoading = false;
            }
        }
    },
    setup() {
        const sectionStore = useSectionStore();
        const organismeStore = useOrganismeStore();
        const authStore = useAuthStore();
        return { sectionStore, organismeStore, authStore };
    },
    async mounted() {
        await this.fetchUsers();
        await this.fetchSections();
    },
};
</script>

<template>
    <div v-if="isVisible" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition p-6 mt-6">
        <h2 class="text-xl font-semibold mb-4">Inscrire un nouvel utilisateur</h2>

        <LoadingBox :isVisible="isLoading" />
        <ErrorBox v-if="error && !isLoading" :message="error" />

        <div v-if="!isLoading && !error" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Utilisateur</label>
                <select v-model="selectedUserId" class="mt-1 block w-full p-2 border rounded">
                    <option disabled value="">Sélectionner un utilisateur</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.mail }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Section</label>
                <select v-model="selectedSection" class="mt-1 block w-full p-2 border rounded">
                    <option disabled value="">Sélectionner une section</option>
                    <option v-for="section in sectionStore.sections" :key="section.id" :value="section.id">
                        {{ section.nom }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rôle</label>
                <select v-model="selectedRole" class="mt-1 block w-full p-2 border rounded">
                    <option disabled value="">Sélectionner un rôle</option>
                    <option v-for="(roleName, roleId) in filteredRoles" :key="roleId" :value="Number(roleId)">
                        {{ roleName }}
                    </option>
                </select>
            </div>

            <button @click="addUserToSection(selectedSection)"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Inscrire l'utilisateur
            </button>
        </div>
    </div>
</template>
