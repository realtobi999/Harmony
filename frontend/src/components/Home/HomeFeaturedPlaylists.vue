<!-- eslint-disable vue/no-use-v-if-with-v-for -->
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3 h-[400px] md:h-[200px]">
    <home-playlist-panel
      v-if="featuredPlaylists"
      v-for="playlist in featuredPlaylists.playlists.items"
      :key="playlist.id"
      :id="playlist.id"
      :img="playlist.images[0].url"
      :playlistName="playlist.name"
      :playlistOwner="playlist.owner.display_name"
    />
  </div>
  <loading v-if="!featuredPlaylists" />
</template>

<script setup>
import HomePlaylistPanel from '@/components/Home/HomePlaylistPanel.vue'
import Loading from '@/components/Loading.vue'
import { onMounted, ref } from 'vue'
import { makeGetRequest } from '@/composables/makeGetRequest'

const { makeGetRequest: performGetRequest } = makeGetRequest()

const featuredPlaylists = ref(null)

onMounted(async () => {
  try {
    const storedData = localStorage.getItem('featuredPlaylists')
    if (storedData === null) {
      const $url = `http://127.0.0.1:8000/api/spotify/playlist/show/featured-playlists?userId=${localStorage.getItem('user_id')}&limit=4`
      featuredPlaylists.value = await performGetRequest($url, 'featuredPlaylists')
    } else {
      featuredPlaylists.value = JSON.parse(storedData)
    }
  } catch (error) {
    console.error('Error setting featured playlists:', error)
  }
})
</script>
