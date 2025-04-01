<script setup>
import { ref } from 'vue'
import { Card, CardHeader, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { RouterLink } from 'vue-router'

const user = ref({
  nom: 'Dupont',
  prenom: 'Jean',
  telephone: '+33 6 12 34 56 78',
  dateNaissance: '1990-05-15',
  sections: ['Football', 'Natation'],
  paiements: [
    { date: '10/01/2024', montant: '50€' },
    { date: '05/12/2023', montant: '30€' },
  ],
})

// Fonction pour obtenir le slug d'une section à partir de son nom
function getSectionSlug(sectionName) {
  // Générer un slug simple à partir du nom
  return sectionName
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')
}
</script>

<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-center mb-6">{{ user.prenom }} {{ user.nom }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <Card>
        <CardHeader>
          <h2 class="text-lg font-semibold">Informations personnelles</h2>
        </CardHeader>
        <CardContent>
          <p><strong>Nom :</strong> {{ user.nom }}</p>
          <p><strong>Prénom :</strong> {{ user.prenom }}</p>
          <p><strong>Téléphone :</strong> {{ user.telephone }}</p>
          <p><strong>Date de naissance :</strong> {{ user.dateNaissance }}</p>
          <div class="mt-4 flex gap-2">
            <Button variant="outline"> Modifier les informations </Button>
            <Button variant="destructive"> Changer le mot de passe </Button>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <h2 class="text-lg font-semibold">Mes sections</h2>
        </CardHeader>
        <CardContent>
          <ul class="space-y-2">
            <li v-for="section in user.sections" :key="section">
              <RouterLink 
                :to="`/auth/section/${getSectionSlug(section)}`" 
                class="text-blue-600 hover:underline"
              >
                {{ section }}
              </RouterLink>
            </li>
          </ul>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <h2 class="text-lg font-semibold">Historique des paiements</h2>
        </CardHeader>
        <CardContent>
          <ul class="space-y-2">
            <li v-for="paiement in user.paiements" :key="paiement.date">
              {{ paiement.date }} - <strong>{{ paiement.montant }}</strong>
            </li>
          </ul>
        </CardContent>
      </Card>
    </div>
  </div>
</template>
