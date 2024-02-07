<template>
  <div class="space-y-2" v-if="topArtists" v-for="artist in topArtists.items">
    <router-link
      :to="`/harmony/artist/${artist.id}`"
      class="flex w-full items-center gap-3 hover:bg-zinc-700 hover:rounded-md transition-all px-3 py-2 cursor-pointer"
    >
      <img :src="artist.images[0].url" class="w-12 h-12 rounded-lg shadow-md" />
      <div class="text-left truncate">
        <h1 class="text-xl font-semibold h-15">{{ artist.name }}</h1>
        <h1 class="text-xs font-semibold text-gray-500 mt-1">{{ capitalizeFirstLetter(artist.genres[0] || 'N/A') }}</h1>
      </div>
    </router-link>
  </div>
  <loading v-if="!topArtists" />
</template>
<script setup>
import { RouterLink } from 'vue-router'
import Loading from '@/components/Loading.vue';
import { onMounted, ref } from 'vue';
import { makeGetRequest } from '@/composables/makeGetRequest';

const topArtists = ref(null)
const { makeGetRequest: performGetRequest } = makeGetRequest()

function capitalizeFirstLetter(text) {
  if (text && typeof text === 'string') {
    return text.charAt(0).toUpperCase() + text.slice(1)
  } else {
    return ''
  }
}

onMounted(async () => {
  try {
    const storedData = localStorage.getItem('topArtists')
    if (storedData === null) {
        const $url = `http://127.0.0.1:8000/api/spotify/users/top-items?userId=${localStorage.getItem('user_id')}&type=artists&limit=10&time_range=short_term`
      topArtists.value = await performGetRequest($url, 'topArtists')
    } else {
      topArtists.value = JSON.parse(storedData)
    }
  } catch (error) {
    console.error('Error setting:', error)
  }
})
</script>
