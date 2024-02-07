<template>
  <router-link
  class="border w-full lg:w-1/6 border-zinc-800 rounded bg-zinc-800 py-3 hover:scale-95 transition-all cursor-pointer hover:bg-zinc-600"
  v-for="artist in props.artists.items"
  :to="`/harmony/artist/${artist.id}`"
  >
    <img
      v-if="artist.images[0] && artist.images[0].url"
      :src="artist.images[0].url"
      class="rounded px-1"
    />
    <img v-else src="/album.png" class="rounded px-1" />
    <h1 class="text-xl font-semibold truncate mt-1 px-1">{{ artist.name }}</h1>
    <p class="text-sm text-zinc-400 px-1">Artist</p>
  </router-link>
</template>
<script setup>
import { RouterLink } from 'vue-router'

const props = defineProps({
  artists: Object
})
</script>
