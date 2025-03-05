import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { api } from '@/lib/api/apiClient'

export interface Organisme {
  id: string
  nom: string
  description: string
  adresse: string
  sections?: Section[]
}

export interface Section {
  id: string
  name: string
  slug: string
  description: string
  category: string
  organisme_id: string
  image?: string
  schedule?: string
  price?: number
  members?: number
  requirements?: string[]
  coach?: string
  location?: string
  materials?: string[]
  instructor?: string
}

function generateSlug(name: string): string {
  return name
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '') // Supprimer les accents
    .replace(/[^a-z0-9]+/g, '-')     // Remplacer les caractères non alphanumériques par des tirets
    .replace(/^-|-$/g, '')           // Supprimer les tirets au début et à la fin
}

export const useSectionStore = defineStore('section', () => {
  const sections = ref<Section[]>([])
  const organismes = ref<Organisme[]>([])
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  // Getters
  const getSectionById = computed(() => (id: string) => {
    return sections.value.find(section => section.id === id) || null
  })

  const getSectionsByCategory = computed(() => (category: string) => {
    return sections.value.filter(section => section.category === category)
  })

  const getCategories = computed(() => {
    const categories = new Set(sections.value.map(section => section.category))
    return Array.from(categories)
  })

  const getSectionBySlug = computed(() => (slug: string) => {
    return sections.value.find(section => section.slug === slug) || null
  })

  // Actions
  async function fetchSections() {
    isLoading.value = true
    error.value = null

    try {
      // Utiliser directement des données mockées
      mockSections()
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Une erreur est survenue lors du chargement des sections'
      console.error(error.value)
    } finally {
      isLoading.value = false
    }
  }

  async function fetchSectionById(id: string) {
    isLoading.value = true
    error.value = null

    try {
      // Retourner la section mockée correspondante
      return sections.value.find(section => section.id === id) || null
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Une erreur est survenue lors du chargement de la section'
      console.error(error.value)
      return null
    } finally {
      isLoading.value = false
    }
  }

  async function inscriptionSection(sectionId: string, userData: any) {
    isLoading.value = true
    error.value = null

    try {
      // Simuler une inscription réussie
      console.log(`Inscription à la section ${sectionId} avec les données:`, userData)
      return { success: true, message: 'Inscription réussie' }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Une erreur est survenue lors de l\'inscription à la section'
      console.error(error.value)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  // Fonction pour obtenir des données mockées
  function mockSections() {
    sections.value = [
      {
        id: '500e3904-c41d-4d49-8d58-539f9e0d8b29',
        name: 'Football',
        slug: 'football',
        description: 'Section football pour tous les âges. Entraînements hebdomadaires et participation à des tournois locaux et régionaux. Équipement fourni pour les débutants.',
        category: 'Sport',
        organisme_id: '12345678-1234-1234-1234-123456789012',
        schedule: 'Lundi et Mercredi 18h-20h',
        price: 150,
        members: 25,
        image: 'https://placehold.co/600x400?text=Football',
        requirements: ['Certificat médical obligatoire', 'Chaussures de foot', 'Short et maillot'],
        coach: 'Jean Dupont',
        location: 'Stade municipal'
      },
      {
        id: '22222222-2222-2222-2222-222222222222',
        name: 'Basketball',
        slug: 'basketball',
        description: 'Section basketball pour tous les niveaux. Entraînements réguliers et compétitions amicales.',
        category: 'Sport',
        organisme_id: '12345678-1234-1234-1234-123456789012',
        schedule: 'Mardi et Jeudi 19h-21h',
        price: 140,
        members: 20,
        image: 'https://placehold.co/600x400?text=Basketball',
        requirements: ['Certificat médical obligatoire', 'Chaussures de sport'],
        coach: 'Marie Durand',
        location: 'Gymnase municipal'
      },
      {
        id: '33333333-3333-3333-3333-333333333333',
        name: 'Yoga',
        slug: 'yoga',
        description: 'Cours de yoga pour tous les niveaux. Séances de relaxation et de méditation incluses.',
        category: 'Bien-être',
        organisme_id: '12345678-1234-1234-1234-123456789012',
        schedule: 'Lundi et Vendredi 10h-12h',
        price: 130,
        members: 15,
        image: 'https://placehold.co/600x400?text=Yoga',
        requirements: ['Tapis de yoga', 'Vêtements confortables'],
        instructor: 'Lucie Martin',
        location: 'Salle de yoga'
      },
      {
        id: '44444444-4444-4444-4444-444444444444',
        name: 'Échecs',
        slug: 'echecs',
        description: 'Club d\'échecs pour tous les niveaux. Tournois internes et externes.',
        category: 'Loisir',
        organisme_id: '12345678-1234-1234-1234-123456789012',
        schedule: 'Samedi 14h-17h',
        price: 80,
        members: 18,
        image: 'https://placehold.co/600x400?text=Echecs',
        coach: 'Pierre Lefebvre',
        location: 'Salle communale'
      },
      {
        id: '55555555-5555-5555-5555-555555555555',
        name: 'Peinture',
        slug: 'peinture',
        description: 'Atelier de peinture et arts plastiques. Exploration de différentes techniques et styles. Matériel fourni pour les séances.',
        category: 'Activité manuelle',
        organisme_id: '12345678-1234-1234-1234-123456789012',
        schedule: 'Samedi 10h-12h',
        price: 120,
        members: 12,
        image: 'https://placehold.co/600x400?text=Peinture',
        materials: ['Pinceaux', 'Toiles', 'Peintures acryliques', 'Palette'],
        instructor: 'Sophie Moreau',
        location: 'Salle des arts'
      }
    ]

    // Simuler des organismes
    organismes.value = [
      {
        id: '12345678-1234-1234-1234-123456789012',
        nom: 'Club Sportif Municipal',
        description: 'Club sportif proposant diverses activités pour tous les âges',
        adresse: '123 Rue du Sport, 75000 Paris'
      }
    ]
  }

  return {
    sections,
    organismes,
    isLoading,
    error,
    getSectionById,
    getSectionsByCategory,
    getCategories,
    getSectionBySlug,
    fetchSections,
    fetchSectionById,
    inscriptionSection
  }
}) 