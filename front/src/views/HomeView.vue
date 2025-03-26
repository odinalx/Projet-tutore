<script>
import { useSectionStore } from '@/stores/sectionStore';
import { useOrganismeStore } from '@/stores/organismeStore';
import LoadingBox from '@/components/LoadingBox.vue';
import ErrorBox from '@/components/ErrorBox.vue';

export default {
    components: {
        LoadingBox,
        ErrorBox
    },
    data() {
        return {
            currentIndex: 0,
            error: null,
            isLoading: true
        };
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
        nextSlide() {
            this.currentIndex = (this.currentIndex + 1) % Math.ceil(this.sectionStore.sections.length / 3);
        }
    },
    mounted() {
        this.fetchSections();
        setInterval(this.nextSlide, 7000);
    },
    setup() {
        const sectionStore = useSectionStore();
        const organismeStore = useOrganismeStore();
        return { sectionStore, organismeStore };
    },
};
</script>

<template>
    <div class="container mx-auto p-8 text-center">
        <img src="/sports.jpg" alt="Photo de sport" class="mx-auto w-full max-w-lg rounded-lg shadow-md mb-4" />

        <div class="border-t-4 border-blue-600 w-1/4 mx-auto my-6"></div>

        <h1 class="text-3xl font-bold text-blue-700 mb-4">
            Bienvenue dans notre espace sportif !
        </h1>
        <p class="text-lg text-gray-600 mb-8">
            Découvrez nos différentes sections et rejoignez la communauté qui partage votre passion.
        </p>

        <div class="overflow-hidden relative w-full max-w-4xl mx-auto">
            <LoadingBox :isVisible="isLoading" class="mt-24" />
            <ErrorBox v-if="error && !isLoading" :message="error" class="mt-24" />
            <div v-if="!isLoading && !error" class="carousel" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
                <div v-for="(section, index) in sectionStore.sections" :key="section.id"
                    class="w-full flex-shrink-0 grid grid-cols-1 md:grid-cols-3 gap-6 bg-white shadow-lg rounded-lg p-6"
                    v-show="index % 3 === 0">
                    <RouterLink v-for="innerSection in sectionStore.sections.slice(index, index + 3)" :key="innerSection.id"
                        class="bg-blue-50 p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-300"
                        :to="`/sections/${innerSection.id}`">
                        <h2 class="text-xl font-semibold text-blue-600">{{ innerSection.nom }}</h2>
                        <p class="text-sm text-gray-500">{{ innerSection.description }}</p>
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.carousel {
    display: flex;
    transition: transform 0.8s ease-in-out;
}
</style>
