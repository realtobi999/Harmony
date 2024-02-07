<template>
  <div class="space-y-2" v-if="recentTracks" v-for="track in recentTracks.items" :key="track">
    <router-link
      :to="`/harmony/track/${track.track.id}`"
      class="flex w-full items-center gap-3 hover:bg-zinc-700 hover:rounded-md transition-all px-3 py-2 cursor-pointer"
    >
      <img :src="track.track.album.images[0].url" class="w-12 h-12 rounded-lg shadow-md" />
      <div class="text-left truncate">
        <h1 class="text-xl font-semibold h-5">{{ track.track.name }}</h1>
        <h1 class="text-xs mt-2 font-semibold text-gray-500">{{ track.track.artists[0].name }}</h1>
      </div>
    </router-link>
  </div>
  <loading v-if="!recentTracks" />
</template>
<script setup>
import { RouterLink } from 'vue-router'
import Loading from '@/components/Loading.vue'
import { onMounted, ref } from 'vue'
import { makeGetRequest } from '@/composables/makeGetRequest'

const { makeGetRequest: performGetRequest } = makeGetRequest()
const recentTracks = ref(null)

onMounted(async () => {
  try {
    const storedData = localStorage.getItem('recentTracks')
    if (storedData === null) {
      const $url = `http://127.0.0.1:8000/api/spotify/track/show/recent?userId=${localStorage.getItem('user_id')}&limit=10`
      recentTracks.value = await performGetRequest($url, 'recentTracks')
    } else {
      recentTracks.value = JSON.parse(storedData)
    }
  } catch (error) {
    console.error('Error setting featured tracks:', error)
  }
})
</script>
