<template>
    <div class="p-8 max-w-lg mx-auto">
      <Card>
        <CardContent class="space-y-4">
          <h2 class="text-xl font-bold">Inscription à une Section</h2>
          
          <div>
            <Label for="weight">Poids (kg)</Label>
            <Input id="weight" type="number" v-model="weight" placeholder="Entrez votre poids" />
          </div>
          
          <div>
            <Label for="schedule">Choisissez un horaire</Label>
            <Select v-model="schedule">
              <SelectTrigger>
                <SelectValue placeholder="Sélectionnez un horaire" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="option in schedules" :key="option" :value="option">{{ option }}</SelectItem>
              </SelectContent>
            </Select>
          </div>
          
          <div>
            <Label for="level">Niveau</Label>
            <Select v-model="level">
              <SelectTrigger>
                <SelectValue placeholder="Sélectionnez un niveau" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="option in levels" :key="option" :value="option">{{ option }}</SelectItem>
              </SelectContent>
            </Select>
          </div>
          
          <div>
            <Label for="medical">Certificat médical</Label>
            <Input id="medical" type="file" @change="handleFileUpload" />
          </div>
          
          <p class="text-lg font-semibold">Prix d'inscription : 50€</p>
          
          <Button @click="submitForm">S'inscrire</Button>
        </CardContent>
      </Card>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import { Card, CardContent } from "@/components/ui/card";
  import { Button } from "@/components/ui/button";
  import { Input } from "@/components/ui/input";
  import { Label } from "@/components/ui/label";
  import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select";
  
  const weight = ref(null);
  const schedule = ref(null);
  const level = ref(null);
  const medicalCertificate = ref(null);
  
  const schedules = ["Matin", "Après-midi", "Soir"];
  const levels = ["Débutant", "Intermédiaire", "Avancé"];
  
  const handleFileUpload = (event) => {
    medicalCertificate.value = event.target.files[0];
  };
  
  const submitForm = () => {
    console.log({ weight: weight.value, schedule: schedule.value, level: level.value, medicalCertificate: medicalCertificate.value });
  };
  </script>
  