<template>
  <router-link :to="`/harmony/playlist/${id}`">
  <div
    class="h-full rounded pr-5 py-1 bg-zinc-800 gap-1 flex w-full hover:scale-95 md:hover:bg-zinc-600 transition-all"
  >
      <img :src="img" class="w-[100px] rounded" />
      <div>
        <p class="text-lg truncate font-semibold">{{ playlistName }}</p>
        <p class="text-sm text-zinc-400">{{ playlistOwner }}</p>
      </div>
    </div>
  </router-link>
</template>
<script setup>
import { RouterLink } from 'vue-router'

defineProps({
  img: String,
  id: String,
  playlistName: String,
  playlistOwner: String
})
</script>
