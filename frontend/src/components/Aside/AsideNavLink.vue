<template>
  <div class="flex gap-4 items-center hover:scale-105 transition-all hover:text-purple-500">
    <ion-icon class="text-2xl" :name="icon"></ion-icon>
    <router-link :to="link" class="text-white transition-all">{{ name }}</router-link>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'

defineProps({
  name: String,
  icon: String,
  link: String,
})
</script>
