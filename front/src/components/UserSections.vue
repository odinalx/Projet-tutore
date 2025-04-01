<script>
import { sectionService } from '@/services/sectionService';
import { useAuthStore } from '@/stores/authStore';
import LoadingBox from './LoadingBox.vue';
import ErrorBox from './ErrorBox.vue';
import utilsMixin from '@/mixins/utilsMixin';

export default {
    mixins: [utilsMixin],
    components: {
        LoadingBox,
        ErrorBox,
    },
    data() {
        return {
            sections: [],
            error: null,
            isLoading: true,
        };
    },
    methods: {
        async fetchSections() {
            try {
                this.sections = await sectionService.getSectionsByUser(this.authStore.user.id);
            } catch (error) {
                this.error = error.message;
            } finally {
                this.isLoading = false;
            }
        },
        getRoleStyle(role) {
            switch (role) {
                case 0: return "bg-blue-100 text-blue-700 border-blue-500";
                case 10: return "bg-green-100 text-green-700 border-green-500";
                case 20: return "bg-yellow-100 text-yellow-700 border-yellow-500";
                default: return "bg-gray-100 text-gray-700 border-gray-500";
            }
        },
        truncateText(text, maxLength) {
            return text.length > maxLength ? text.slice(0, maxLength) + '...' : text;
        }
    },
    async mounted() {
        await this.fetchSections();
    },
    setup() {
        const authStore = useAuthStore();
        return { authStore };
    },
};
</script>


<template>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Mes sections</h2>
            <RouterLink to="/sections" class="text-blue-600 hover:underline">
                Voir toutes les sections
            </RouterLink>
        </div>

        <LoadingBox :isVisible="isLoading" />
        <ErrorBox v-if="error && !isLoading" :message="error" />

        <div v-if="sections.length && !isLoading && !error"
            class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[500px] overflow-y-auto mt-8">
            <RouterLink :to="`/sections/${section.id}`" v-for="section in sections" :key="section.id"
                class="p-4 border rounded-lg shadow-sm bg-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg">{{ section.nom }}</h3>
                    <p class="text-gray-600">{{ truncateText(section.description, 35) }}</p>
                </div>
                <div
                    :class="`inline-block px-3 py-1 text-sm font-semibold rounded-full border ${getRoleStyle(section.role)}`">
                    {{ getRoleLabel(section.role) }}
                </div>
            </RouterLink>
        </div>

        <div v-else-if="!isLoading && !error" class="text-center text-gray-500">
            Vous ne faites partis d'aucune section.
        </div>
    </div>
</template>
