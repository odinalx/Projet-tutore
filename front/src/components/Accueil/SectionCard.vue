<template>
  <Card class="w-40 text-center">
    <CardContent class="h-40 flex items-center justify-center bg-gray-200">
      <span class="text-gray-500">Image</span>
    </CardContent>
    <CardFooter class="mt-2">
      <p class="font-semibold">{{ title }}</p>
    </CardFooter>
  </Card>
</template>

<script setup>
import { Card, CardContent, CardFooter } from "@/components/ui/card";
defineProps({ title: String });
</script>
