<template>
  <div class="grid place-items-center gap-6 min-h-screen" v-if="track">
    <div>
      <router-link :to="`/harmony/album/${track.album.id}`">
        <img
          :src="track.album.images[0].url"
          class="w-[300px] h-[300px] rounded-lg hover:scale-105 transition-all cursor-pointer shadow-md"
        />
      </router-link>
      <h1 class="text-3xl font-bold mt-6">{{ track.name }}</h1>
      <router-link :to="`/harmony/artist/${track.artists[0].id}`">
        <h2 class="text-2xl text-gray-500 font-bold mt-1 mb-3 hover:underline">
          {{ track.artists[0].name }}
        </h2>
      </router-link>
      <div class="flex gap-3 items-center">
        <a :href="track.uri">
          <button
            class="bg-green-500 text-white rounded-md text-3xl font-bold px-8 py-4 hover:scale-105 hover:bg-green-400 transition-all shadow-xl"
          >
            Play on Spotify
          </button>
        </a>
        <TrackSaveButton :trackId="track.id" />
      </div>
    </div>
  </div>
  <div v-else>
    <div class="flex items-center justify-center min-h-screen">
      <img src="https://i.gifer.com/ZKZg.gif" class="w-[50px]" alt="Loading" />
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import { makeGetRequest } from '@/composables/makeGetRequest.js'
import { onMounted, ref, watch } from 'vue'
import TrackSaveButton from '@/components/Track/TrackSaveButton.vue'

const route = useRoute()

route.meta.layout = 'HarmonyLayout'
const { makeGetRequest: performGetRequest } = makeGetRequest()

const track = ref(null)

onMounted(async () => {
  const $url = `http://127.0.0.1:8000/api/spotify/track/show-${route.params.trackId}?userId=${localStorage.getItem('user_id')}`
  try {
    const storedData = localStorage.getItem(`track_${route.params.trackId}`)
    if (storedData === null) {
      track.value = await performGetRequest($url, `track_${route.params.trackId}`)
    } else {
      track.value = JSON.parse(storedData)
    }
  } catch (error) {
    console.error('Error setting:', error)
  }
})

watch(
  () => route.params.trackId,
  () => {
    location.reload();
  }
);
</script>
