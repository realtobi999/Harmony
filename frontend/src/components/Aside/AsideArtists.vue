<template>
    <router-link
      v-if="artists"
      class="flex gap-1 hover:scale-95 transition-all cursor-pointer my-4 hover:bg-zinc-600 rounded p-1"
      v-for="artist in artists.artists.items"
      :to="`/harmony/artist/${artist.id}`"
    >
      <img :src="artist.images[0].url" class="w-25 md:w-32 hover:opacity-80 rounded-lg" />
      <div class="hidden md:block">
        <p class="truncate text-xl font-semibold">{{ artist.name }}</p>
        <p class="text-base text-zinc-400">Artist</p>
      </div>
    </router-link>
    <loading v-if="!artists" />
  </template>
  
  <script setup>
  import { RouterLink } from 'vue-router'
  import { ref, onMounted } from 'vue'
  import Loading from '@/components/Loading.vue'
  import { makeGetRequest } from '@/composables/makeGetRequest'
  
  const artists = ref(null)
  const { makeGetRequest: performGetRequest } = makeGetRequest()
  
  onMounted(async () => {
    const storedData = localStorage.getItem('savedArtists')
    if (storedData === null) {
      const $url = `http://127.0.0.1:8000/api/spotify/users/followed-artists?userId=${localStorage.getItem('user_id')}`
      artists.value = await performGetRequest($url, 'savedArtists')
    } else {
      artists.value = JSON.parse(storedData)
    }
  })
  </script>
  