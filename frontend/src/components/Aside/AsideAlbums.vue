<template>
    <router-link
      v-if="albums"
      class="flex gap-1 hover:scale-95 transition-all cursor-pointer my-4 hover:bg-zinc-600 rounded p-1"
      v-for="album in albums.items"
      :to="`/harmony/album/${album.album.id}`"
    >
      <img :src="album.album.images[0].url" class="w-25 md:w-32 hover:opacity-80 rounded-lg" />
      <div class="hidden md:block">
        <p class="truncate text-xl font-semibold">{{ album.album.name }}</p>
        <p class="text-base text-zinc-400">{{ album.album.artists[0].name }}</p>
      </div>
    </router-link>
    <loading v-if="!albums" />
  </template>
  
  <script setup>
  import { RouterLink } from 'vue-router'
  import { ref, onMounted } from 'vue'
  import Loading from '@/components/Loading.vue'
  import { makeGetRequest } from '@/composables/makeGetRequest'
  
  const albums = ref(null)
  const { makeGetRequest: performGetRequest } = makeGetRequest()
  
  onMounted(async () => {
    const storedData = localStorage.getItem('savedAlbums')
    if (storedData === null) {
      const $url = `http://127.0.0.1:8000/api/spotify/album/show/saved?userId=${localStorage.getItem('user_id')}`
      albums.value = await performGetRequest($url, 'savedAlbums')
    } else {
      albums.value = JSON.parse(storedData)
    }
  })
  </script>
  