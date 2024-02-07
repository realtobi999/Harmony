<template>
  <div class="w-[80%] mx-auto mt-10" v-if="album">
    <div class="flex-col md:flex-row gap-3 flex">
      <div class="lg:w-1/4 md:w-1/2">
        <img :src="album.images[0].url" class="rounded" />
      </div>
      <div>
        <p class="text-base text-zinc-400">Album</p>
        <h1 class="text-3xl lg:text-5xl font-semibold">{{ album.name }}</h1>
        <div class="flex items-center gap-2 mt-2 md:mt-5">
          <p class="text-lg text-zinc-400">
            <router-link :to="`/harmony/artist/${album.artists[0].id}`" class="hover:text-white">{{
              album.artists[0].name
            }}</router-link>
            - {{ album.total_tracks }} songs
          </p>
        </div>
        <div class="flex gap-2 items-center mt-5 md:mt-10 items-center">
          <router-link to="#">
            <a
              :href="album.uri"
              class="bg-green-500 text-white rounded-full px-5 py-2 font-bold text-xl hover:bg-green-400"
            >
              Play!
            </a>
          </router-link>
          <div class="flex items-center">
            <album-save-button :albumId="album.id" />
          </div>
        </div>
      </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5 w-full">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-zinc-800 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-3">Title</th>
            <th scope="col" class="px-6 py-3">Album</th>
            <th scope="col" class="px-6 py-3 text-center">Edit</th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="bg-zinc-800 border-b border-zinc-700 hover:bg-zinc-600"
            v-for="track in album.tracks.items"
            :key="track.id"
          >
            <table-playlist-tracks
              :trackName="track.name"
              :artistName="track.artists[0].name"
              :img="album.images[0].url"
              :id="track.id"
              :albumName="album.name"
              :albumId="album.id"
            />
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <loading v-if="!album" />
</template>
<script setup>
import { useRoute, RouterLink } from 'vue-router'
import TablePlaylistTracks from '@/components/Playlists/TablePlaylistTracks.vue'
import { makeGetRequest } from '@/composables/makeGetRequest'
import Loading from '@/components/Loading.vue'
import { onMounted, ref, watch } from 'vue'
import AlbumSaveButton from '@/components/Album/AlbumSaveButton.vue'

const route = useRoute()

route.meta.layout = 'HarmonyLayout'
const { makeGetRequest: performGetRequest } = makeGetRequest()

const album = ref(null)

onMounted(async () => {
  const userId = localStorage.getItem('user_id')
  const albumId = route.params.albumId
  const $url = `http://127.0.0.1:8000/api/spotify/album/show-${albumId}?userId=${userId}`
  album.value = await performGetRequest($url)
})

watch(
  () => route.params.albumId,
  () => {
    location.reload()
  }
)
</script>
