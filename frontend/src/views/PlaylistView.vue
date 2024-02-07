<template>
  <div class="w-[80%] mx-auto mt-10" v-if="playlist">
    <div class="flex-col md:flex-row gap-3 flex">
      <div class="w-[70%] lg:w-[20%]">
        <img :src="playlist.images[0].url" class="rounded-xl" />
      </div>
      <div>
        <p class="text-base text-zinc-400">Playlist</p>
        <h1 class="text-3xl lg:text-5xl font-semibold">{{ playlist.name }}</h1>
        <div class="flex items-center gap-2 mt-2 md:mt-5">
          <h1 to="/harmony/artist/1" class="text-lg text-white">
            {{ playlist.owner.display_name }}
            <span class="text-zinc-400">- {{ playlist.tracks.items.length }} songs</span>
          </h1>
        </div>
        <div class="flex gap-2 items-center mt-5 md:mt-10 items-center">
          <router-link to="#">
            <a
              :href="playlist.uri"
              class="bg-green-500 text-white rounded-full px-5 py-2 font-bold text-xl hover:bg-green-400"
            >
              Play!
            </a>
          </router-link>
          <div class="flex items-center">
            <playlist-save-button :playlistId="playlist.id" />
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
            <th scope="col" class="px-6 py-3">Date Added</th>
            <th scope="col" class="px-6 py-3 text-center">Edit</th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="bg-zinc-800 border-b border-zinc-700 hover:bg-zinc-600"
            v-for="(track, index) in playlist.tracks.items"
            :key="index"
          >
            <table-playlist-tracks
              includeDate="true"
              :trackName="track.track.name"
              :artistName="track.track.artists[0].name"
              :img="track.track.album.images[0].url"
              :id="track.track.id"
              :albumName="track.track.album.name"
              :date="track.added_at"
              :albumId="track.track.album.id"
            />
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <Loading v-if="!playlist" />
</template>
<script setup>
import { useRoute, RouterLink } from 'vue-router'
import TablePlaylistTracks from '@/components/Playlists/TablePlaylistTracks.vue'
import { makeGetRequest } from '@/composables/makeGetRequest'
import Loading from '@/components/Loading.vue'
import { onMounted, ref, watch } from 'vue'
import PlaylistSaveButton from '@/components/Playlists/PlaylistSaveButton.vue'

const route = useRoute()

route.meta.layout = 'HarmonyLayout'
const { makeGetRequest: performGetRequest } = makeGetRequest()

const playlist = ref(null)

onMounted(async () => {
  const userId = localStorage.getItem('user_id')
  const playlistId = route.params.playlistId
  const $url = `http://127.0.0.1:8000/api/spotify/playlist/show-${playlistId}?userId=${userId}`
  playlist.value = await performGetRequest($url)
})

watch(
  () => route.params.playlistId,
  () => {
    location.reload()
  }
)
</script>
