<template>
  <div class="container mx-auto p-6">
    <div class="flex items-center mb-8">
      <div class="w-1/4 text-left">
        <RouterLink 
          to="/sections" 
          class="text-blue-600 hover:underline flex items-center"
        >
          <span class="mr-1">&larr;</span> Toutes les sections
        </RouterLink>
      </div>
      <h1 class="text-3xl font-bold text-center w-2/4">Inscription</h1>
      <div class="w-1/4"><!-- Espace vide pour équilibrer --></div>
    </div>
    
    <div v-if="isLoading" class="text-center py-12">
      <p class="text-xl">Chargement...</p>
    </div>
    
    <div v-else-if="error" class="text-center py-12">
      <p class="text-red-500 text-xl">{{ error }}</p>
      <button @click="goBack" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
        Retour
      </button>
    </div>
    
    <div v-else class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
      <div v-if="selectedSection" class="mb-6">
        <div class="flex items-center mb-4 justify-between">
          <div class="flex items-center">
            <button @click="clearSelectedSection" class="mr-4 text-blue-500 hover:underline">
              &larr; Choisir une autre section
            </button>
            <h2 class="text-xl font-semibold">Inscription à {{ selectedSection.name }}</h2>
          </div>
          
          <RouterLink 
            :to="`/section/${selectedSection.slug}`" 
            class="text-blue-600 hover:underline"
          >
            Voir les détails
          </RouterLink>
        </div>
        
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
          <p class="text-sm text-gray-700">
            <span class="font-semibold">Tarif:</span> {{ selectedSection.price }}€
          </p>
          <p class="text-sm text-gray-700">
            <span class="font-semibold">Horaires:</span> {{ selectedSection.schedule }}
          </p>
        </div>
      </div>
      
      <div v-else class="mb-6">
        <h2 class="text-xl font-semibold mb-4">Choisissez une section</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div 
            v-for="category in categories" 
            :key="category" 
            class="mb-4"
          >
            <h3 class="font-medium mb-2">{{ category }}</h3>
            <div class="space-y-2">
              <div 
                v-for="section in getSectionsByCategory(category)" 
                :key="section.id"
                class="p-3 border rounded-md hover:bg-gray-50 cursor-pointer"
                @click="selectSection(section)"
              >
                <p class="font-medium">{{ section.name }}</p>
                <p class="text-sm text-gray-600">{{ section.price }}€</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <form v-if="selectedSection" @submit.prevent="submitForm" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
            <input 
              id="firstName" 
              v-model="formData.firstName" 
              type="text" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': formErrors.firstName}"
            />
            <p v-if="formErrors.firstName" class="mt-1 text-sm text-red-600">{{ formErrors.firstName }}</p>
          </div>
          
          <div>
            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input 
              id="lastName" 
              v-model="formData.lastName" 
              type="text" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': formErrors.lastName}"
            />
            <p v-if="formErrors.lastName" class="mt-1 text-sm text-red-600">{{ formErrors.lastName }}</p>
          </div>
          
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input 
              id="email" 
              v-model="formData.email" 
              type="email" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': formErrors.email}"
            />
            <p v-if="formErrors.email" class="mt-1 text-sm text-red-600">{{ formErrors.email }}</p>
          </div>
          
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
            <input 
              id="phone" 
              v-model="formData.phone" 
              type="tel" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': formErrors.phone}"
            />
            <p v-if="formErrors.phone" class="mt-1 text-sm text-red-600">{{ formErrors.phone }}</p>
          </div>
          
          <div>
            <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
            <input 
              id="birthdate" 
              v-model="formData.birthdate" 
              type="date" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': formErrors.birthdate}"
            />
            <p v-if="formErrors.birthdate" class="mt-1 text-sm text-red-600">{{ formErrors.birthdate }}</p>
          </div>
          
          <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
            <input 
              id="address" 
              v-model="formData.address" 
              type="text" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': formErrors.address}"
            />
            <p v-if="formErrors.address" class="mt-1 text-sm text-red-600">{{ formErrors.address }}</p>
          </div>
        </div>
        
        <!-- Champs spécifiques à la section -->
        <div v-if="selectedSection.category === 'Sport'" class="space-y-4">
          <h3 class="text-lg font-medium">Informations spécifiques pour le sport</h3>
          
          <div>
            <label for="medicalCertificate" class="block text-sm font-medium text-gray-700 mb-1">
              Certificat médical
            </label>
            <div class="flex items-center">
              <input 
                id="medicalCertificate" 
                v-model="formData.medicalCertificate" 
                type="checkbox" 
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label for="medicalCertificate" class="ml-2 block text-sm text-gray-700">
                Je confirme avoir un certificat médical valide (moins d'un an)
              </label>
            </div>
            <p v-if="formErrors.medicalCertificate" class="mt-1 text-sm text-red-600">
              {{ formErrors.medicalCertificate }}
            </p>
          </div>
          
          <div>
            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
            <select 
              id="level" 
              v-model="formData.level" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': formErrors.level}"
            >
              <option value="">Sélectionnez votre niveau</option>
              <option value="debutant">Débutant</option>
              <option value="intermediaire">Intermédiaire</option>
              <option value="avance">Avancé</option>
            </select>
            <p v-if="formErrors.level" class="mt-1 text-sm text-red-600">{{ formErrors.level }}</p>
          </div>
        </div>
        
        <div v-else-if="selectedSection.category === 'Activité manuelle'" class="space-y-4">
          <h3 class="text-lg font-medium">Informations spécifiques pour l'activité manuelle</h3>
          
          <div>
            <label for="materials" class="block text-sm font-medium text-gray-700 mb-1">
              Matériel personnel
            </label>
            <div class="flex items-center">
              <input 
                id="materials" 
                v-model="formData.hasMaterials" 
                type="checkbox" 
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label for="materials" class="ml-2 block text-sm text-gray-700">
                J'apporterai mon propre matériel
              </label>
            </div>
          </div>
          
          <div>
            <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Expérience</label>
            <textarea 
              id="experience" 
              v-model="formData.experience" 
              rows="3" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Décrivez brièvement votre expérience dans ce domaine..."
            ></textarea>
          </div>
        </div>
        
        <!-- Champs spécifiques pour les activités culturelles -->
        <div v-else-if="selectedSection.category === 'Culture'" class="space-y-4">
          <h3 class="text-lg font-medium">Informations spécifiques pour l'activité culturelle</h3>
          
          <div>
            <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Expérience</label>
            <textarea 
              id="experience" 
              v-model="formData.experience" 
              rows="3" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Avez-vous déjà pratiqué cette activité ? Si oui, décrivez votre expérience..."
            ></textarea>
          </div>
          
          <div>
            <label for="motivation" class="block text-sm font-medium text-gray-700 mb-1">Motivation</label>
            <textarea 
              id="motivation" 
              v-model="formData.motivation" 
              rows="3" 
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Pourquoi souhaitez-vous rejoindre cette activité ?"
            ></textarea>
          </div>
        </div>
        
        <div class="pt-4 border-t">
          <div class="flex items-center mb-4">
            <input 
              id="terms" 
              v-model="formData.termsAccepted" 
              type="checkbox" 
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              :class="{'border-red-500': formErrors.termsAccepted}"
            />
            <label for="terms" class="ml-2 block text-sm text-gray-700">
              J'accepte les conditions générales d'inscription
            </label>
          </div>
          <p v-if="formErrors.termsAccepted" class="mt-1 text-sm text-red-600">
            {{ formErrors.termsAccepted }}
          </p>
          
          <button 
            type="submit" 
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting">Traitement en cours...</span>
            <span v-else>S'inscrire à {{ selectedSection.name }}</span>
          </button>
        </div>
      </form>
      
      <div v-if="formSuccess" class="mt-6 p-4 bg-green-50 text-green-800 rounded-lg">
        <p class="font-medium">Inscription réussie !</p>
        <p>Votre inscription à la section {{ selectedSection.name }} a bien été enregistrée.</p>
        <p class="mt-2">Un email de confirmation vous a été envoyé.</p>
        <div class="mt-4">
          <RouterLink to="/profil" class="text-blue-600 hover:underline">
            Voir mon profil
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { z } from 'zod'
import { useSectionStore } from '@/stores/sectionStore'
import type { Section } from '@/stores/sectionStore'

const route = useRoute()
const router = useRouter()
const sectionStore = useSectionStore()

const isLoading = ref(true)
const error = ref<string | null>(null)
const selectedSection = ref<Section | null>(null)
const isSubmitting = ref(false)
const formSuccess = ref(false)

// Récupérer les catégories depuis le store
const categories = computed(() => sectionStore.getCategories)

// Fonction pour récupérer les sections par catégorie
const getSectionsByCategory = (category: string) => {
  return sectionStore.getSectionsByCategory(category)
}

// Fonction pour sélectionner une section
function selectSection(section: Section) {
  selectedSection.value = section
}

// Fonction pour désélectionner une section
function clearSelectedSection() {
  selectedSection.value = null
}

// Fonction pour revenir en arrière
function goBack() {
  router.back()
}

// Schéma de validation Zod pour le formulaire de base
const baseSchema = z.object({
  firstName: z.string().min(2, "Le prénom doit contenir au moins 2 caractères"),
  lastName: z.string().min(2, "Le nom doit contenir au moins 2 caractères"),
  email: z.string().email("L'email n'est pas valide"),
  phone: z.string().min(10, "Le numéro de téléphone doit contenir au moins 10 chiffres"),
  birthdate: z.string().refine(val => {
    const date = new Date(val)
    return !isNaN(date.getTime())
  }, "La date de naissance n'est pas valide"),
  address: z.string().min(5, "L'adresse doit contenir au moins 5 caractères"),
  termsAccepted: z.boolean().refine(val => val === true, "Vous devez accepter les conditions générales")
})

// Schéma spécifique pour les sports
const sportSchema = baseSchema.extend({
  medicalCertificate: z.boolean().refine(val => val === true, "Le certificat médical est obligatoire"),
  level: z.string().min(1, "Veuillez sélectionner un niveau")
})

// Schéma spécifique pour les activités manuelles
const manualActivitySchema = baseSchema.extend({
  hasMaterials: z.boolean().optional(),
  experience: z.string().optional()
})

// Données du formulaire
const formData = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  birthdate: '',
  address: '',
  termsAccepted: false,
  // Champs spécifiques aux sports
  medicalCertificate: false,
  level: '',
  // Champs spécifiques aux activités manuelles
  hasMaterials: false,
  experience: '',
  // Champs spécifiques pour les activités culturelles
  motivation: ''
})

// Erreurs du formulaire
const formErrors = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  birthdate: '',
  address: '',
  termsAccepted: '',
  medicalCertificate: '',
  level: ''
})

// Fonction pour soumettre le formulaire
async function submitForm() {
  // Réinitialiser les erreurs
  Object.keys(formErrors).forEach(key => {
    formErrors[key] = ''
  })
  
  isSubmitting.value = true
  
  try {
    let validationSchema
    
    // Sélectionner le schéma en fonction de la catégorie de la section
    if (selectedSection.value?.category === 'Sport') {
      validationSchema = sportSchema
    } else if (selectedSection.value?.category === 'Activité manuelle') {
      validationSchema = manualActivitySchema
    } else if (selectedSection.value?.category === 'Culture') {
      validationSchema = baseSchema.extend({
        experience: z.string().optional(),
        motivation: z.string().optional()
      })
    } else {
      validationSchema = baseSchema
    }
    
    // Valider les données du formulaire
    validationSchema.parse(formData)
    
    // Simuler un appel API
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Afficher le message de succès
    formSuccess.value = true
    
    // Réinitialiser le formulaire après 3 secondes
    setTimeout(() => {
      router.push('/profil')
    }, 3000)
    
  } catch (err) {
    if (err instanceof z.ZodError) {
      // Traiter les erreurs de validation
      err.errors.forEach(error => {
        const field = error.path[0]
        formErrors[field] = error.message
      })
    } else {
      // Traiter les autres erreurs
      error.value = "Une erreur est survenue lors de l'inscription"
      console.error(err)
    }
  } finally {
    isSubmitting.value = false
  }
}

// Fonction pour charger la section depuis l'URL
async function loadSectionFromUrl() {
  const sectionSlug = route.query.section as string
  if (sectionSlug) {
    // Si les sections sont déjà chargées
    if (sectionStore.sections.length > 0) {
      const section = sectionStore.getSectionBySlug(sectionSlug)
      if (section) {
        selectedSection.value = section
      }
    } else {
      // Sinon, attendre que les sections soient chargées
      await sectionStore.fetchSections()
      const section = sectionStore.getSectionBySlug(sectionSlug)
      if (section) {
        selectedSection.value = section
      }
    }
  }
}

onMounted(async () => {
  try {
    isLoading.value = true
    await loadSectionFromUrl()
  } catch (err) {
    error.value = "Erreur lors du chargement des sections"
    console.error(err)
  } finally {
    isLoading.value = false
  }
})
</script>
