<template>
  <router-link
    class="border w-full lg:w-1/6 border-zinc-800 rounded bg-zinc-800 py-3 hover:scale-95 transition-all cursor-pointer hover:bg-zinc-600"
    v-for="album in props.albums.items"
    :to="`/harmony/album/${album.id}`"
  >
    <img :src="album.images[0].url" class="w-50 rounded px-1" />
    <h1 class="text-xl font-semibold truncate mt-1 px-1 truncate">{{ album.name }}</h1>
    <p class="text-sm text-zinc-400 px-1">Albums</p>
  </router-link>
</template>
<script setup>
import { RouterLink } from 'vue-router'

const props = defineProps({
  albums: Object
})
</script>
