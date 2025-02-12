<script setup>
import { ref } from "vue";
import { Card, CardHeader, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";

const section = ref({
  nom: "Football",
  etat: "payé",
  seances: [
    { jour: "lundi", heure: "18:00" },
    { jour: "mardi", heure: "17:00" },
    { jour: "vendredi", heure: "19:00" },
  ],
  telephone: "+33 6 12 34 56 78",
  email: "johanne.football@me.com",
  responsable: "Johanne S.",
  lieu: "Salle 101",
});
</script>

<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-center mb-6">
      {{ section.nom }}
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Card: Mon planning -->
      <Card>
        <CardHeader>
          <h2 class="text-lg font-semibold">Mon planning</h2>
        </CardHeader>
        <CardContent>
          <ul class="space-y-2">
            <li v-for="(seance, index) in section.seances" :key="index" class="flex justify-between">
              <span>{{ seance.jour }}:</span>
              <span class="font-medium">{{ seance.heure }}</span>
            </li>
          </ul>
        </CardContent>
      </Card>

      <!-- Card: Mes Informations -->
      <Card>
        <CardHeader>
          <h2 class="text-lg font-semibold">Mes Informations</h2>
        </CardHeader>
        <CardContent>
          <ul class="space-y-2">
            <li><strong>État:</strong> {{ section.etat }}</li>
            <li><strong>Lieu:</strong> {{ section.lieu }}</li>
          </ul>
        </CardContent>
      </Card>

      <!-- Card: Contact -->
      <Card>
        <CardHeader>
          <h2 class="text-lg font-semibold">Contact</h2>
        </CardHeader>
        <CardContent>
          <ul class="space-y-2">
            <li><strong>Responsable:</strong> {{ section.responsable }}</li>
            <li><strong>Email:</strong> <a :href="`mailto:${section.email}`" class="text-blue-500 hover:underline">{{ section.email }}</a></li>
            <li><strong>Numéro:</strong> <a :href="`tel:${section.telephone}`" class="text-blue-500 hover:underline">{{ section.telephone }}</a></li>
          </ul>
        </CardContent>
      </Card>
    </div>
  </div>
</template>

