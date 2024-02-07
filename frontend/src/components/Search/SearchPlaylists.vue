<template>
  <router-link
    v-for="playlist in props.playlists.items"
    :to="`/harmony/playlist/${playlist.id}`"
    class="bg-zinc-800 lg:w-1/3 hover:scale-95 transition-all cursor-pointer hover:bg-zinc-600 py-5 px-3 rounded"
  >
    <img :src="playlist.images[0].url" class="rounded" />
    <div>
      <h1 class="text-xl font-semibold truncate mt-1 truncate w-[80%] font-semibold">
        {{ playlist.name }}
      </h1>
      <p class="text-sm text-zinc-400">{{ playlist.owner.display_name }}</p>
    </div>
  </router-link>
</template>
<script setup>
import { RouterLink } from 'vue-router'

const props = defineProps({
  playlists: Object
})
</script>
