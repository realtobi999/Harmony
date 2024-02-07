<template>
  <div class="mt-4 w-full md:w-1/2 mx-auto shadow-xl">
    <input
      v-model="search"
      type="text"
      class="border border-zinc-700 bg-zinc-800 p-2 rounded-md w-full mx-auto font-semibold"
      placeholder="Search"
    />
  </div>

  <div v-if="search != '' && searchResults.isComplete" class="mt-10 mx-auto">
    <div class="flex gap-5 flex-col md:flex-row">
      <div class="w-full md:w-1/2 lg:w-full grid">
        <h1 class="text-5xl text-purple-300 mb-3 font-semibold">Top Playlist</h1>
        <div class="grid grid-cols-3 md:grid-cols-2 gap-2 lg:flex">
          <search-playlists :playlists="searchResults.playlists.playlists" />
        </div>
      </div>
      <div class="w-full md:w-1/2">
        <h1 class="w-full text-5xl text-purple-300 mb-3 font-semibold">Tracks</h1>
        <div class="w-full mt-2">
          <search-tracks :tracks="searchResults.tracks.tracks" />
        </div>
      </div>
    </div>
    <div class="mt-3">
      <div class="w-full">
        <h1 class="text-5xl text-purple-500 mb-3 font-semibold">Artists</h1>
        <div class="grid grid-cols-3 gap-2 lg:flex">
          <search-artists :artists="searchResults.artists.artists" />
        </div>
      </div>
      <div class="mt-3">
        <h1 class="text-5xl text-purple-500 mb-3 font-semibold">Albums</h1>
        <div class="grid grid-cols-3 gap-2 lg:flex">
          <search-albums :albums="searchResults.albums.albums" />
        </div>
      </div>
    </div>
  </div>
  <h1 class="text-3xl text-gray-400 text-center mt-9" v-else-if="search == ''">
    Search for something!
  </h1>
  <loading v-if="!searchResults.isComplete && search != ''" />
</template>
<script setup>
import { useRoute } from 'vue-router'
import { ref, watch } from 'vue'
import SearchPlaylists from '@/components/Search/SearchPlaylists.vue'
import SearchTracks from '@/components/Search/SearchTracks.vue'
import SearchArtists from '@/components/Search/SearchArtists.vue'
import SearchAlbums from '@/components/Search/SearchAlbums.vue'
import debounce from 'lodash/debounce'
import { makeGetRequest } from '@/composables/makeGetRequest'
import Loading from '@/components/Loading.vue'

const search = ref('')
const route = useRoute()
let searchResults = ref({
  isComplete: false
})

const { makeGetRequest: performGetRequest } = makeGetRequest()

route.meta.layout = 'HarmonyLayout'

watch(
  search,
  debounce(async function (value) {
    searchResults.value = {
      isComplete: false
    }
    if (value == '') {
      searchResults.value = {
        isComplete: false
      }
      return
    }
    searchResults.value = await getSearchData(value)
  }, 500)
)

let searchTimeout

const getSearchData = async (value) => {
  clearTimeout(searchTimeout)

  return new Promise((resolve) => {
    searchTimeout = setTimeout(async () => {
      const $url = {
        playlists: `http://127.0.0.1:8000/api/spotify/search-playlist?q=${value}&limit=3&userId=${localStorage.getItem('user_id')}`,
        tracks: `http://127.0.0.1:8000/api/spotify/search-track?q=${value}&limit=6&userId=${localStorage.getItem('user_id')}`,
        artists: `http://127.0.0.1:8000/api/spotify/search-artist?q=${value}&limit=6&userId=${localStorage.getItem('user_id')}`,
        albums: `http://127.0.0.1:8000/api/spotify/search-album?q=${value}&limit=6&userId=${localStorage.getItem('user_id')}`
      }

      const requests = Object.values($url).map((url) => performGetRequest(url))
      const results = await Promise.allSettled(requests)

      const searchData = {
        playlists: results[0].value,
        tracks: results[1].value,
        artists: results[2].value,
        albums: results[3].value,
        isComplete: true
      }

      resolve(searchData)
    }, 500) // 300ms debounce time
  })
}
</script>
