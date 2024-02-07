<template>
  <router-link
    v-if="playlists"
    class="flex gap-1 hover:scale-95 transition-all cursor-pointer my-4 hover:bg-zinc-600 rounded p-1"
    v-for="playlist in playlists.items"
    :to="`/harmony/playlist/${playlist.id}`"
  >
    <img :src="playlist.images[0].url" class="w-25 md:w-32 hover:opacity-80 rounded-lg" />
    <div class="hidden md:block">
      <p class="truncate text-xl font-semibold">{{ playlist.name }}</p>
      <p class="text-base text-zinc-400">{{ playlist.owner.display_name }}</p>
    </div>
  </router-link>
  <loading v-if="!playlists" />
</template>

<script setup>
import { RouterLink } from 'vue-router'
import { ref, onMounted } from 'vue'
import Loading from '@/components/Loading.vue'
import { makeGetRequest } from '@/composables/makeGetRequest'

const playlists = ref(null)
const { makeGetRequest: performGetRequest } = makeGetRequest()

onMounted(async () => {
  const storedData = localStorage.getItem('savedPlaylists')
  if (storedData === null) {
    const $url = `http://127.0.0.1:8000/api/spotify/playlist/show/saved?userId=${localStorage.getItem('user_id')}`
    playlists.value = await performGetRequest($url, 'savedPlaylists')
  } else {
    playlists.value = JSON.parse(storedData)
  }
})
</script>
