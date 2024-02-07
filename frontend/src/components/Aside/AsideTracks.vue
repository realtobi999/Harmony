<!-- eslint-disable vue/no-use-v-if-with-v-for -->
<template>
  <router-link
    v-if="tracks"
    v-for="track in tracks.items"
    :to="`/harmony/track/${track.track.id}`"
    :key="track.track.id"
    class="flex w-full items-center gap-3 hover:bg-zinc-700 hover:rounded-md transition-all mt-5 px-3 py-2 cursor-pointer"
  >
    <img :src="track.track.album.images[0].url" class="w-12 h-12 rounded-lg shadow-md" />
    <div class="text-left truncate">
      <h1 class="text-xl font-semibold h-5">{{ track.track.name }}</h1>
      <h1 class="text-sm mt-2 font-semibold text-gray-500">
        {{ track.track.artists[0].name }}
      </h1>
    </div>
  </router-link>
  <loading v-if="!tracks" />
</template>
<script setup>
import { RouterLink } from 'vue-router'
import { ref, onMounted } from 'vue'
import Loading from '@/components/Loading.vue'
import { makeGetRequest } from '@/composables/makeGetRequest'

const tracks = ref(null)
const { makeGetRequest: performGetRequest } = makeGetRequest()

onMounted(async () => {
  const storedData = localStorage.getItem('savedTracks')
  if (storedData === null) {
    const $url = `http://127.0.0.1:8000/api/spotify/track/show/saved?userId=${localStorage.getItem('user_id')}&limit=50`
    tracks.value = await performGetRequest($url, 'savedTracks')
  } else {
    tracks.value = JSON.parse(storedData)
  }
})
</script>
