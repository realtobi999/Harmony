<template>
  <router-link
    v-if="artistTracks"
    v-for="track in artistTracks.tracks.slice(0, 5)"
    :to="`/harmony/track/${track.id}`"
    class="flex text-xl items-center gap-3 hover:bg-zinc-700 hover:rounded-md transition-all md:px-3 py-2 cursor-pointer"
  >
    <img
      :src="[track.album.images[0].url ?? '/album.png']"
      class="w-12 h-12 rounded-lg shadow-md"
    />
    <div class="text-left truncate">
      <h1 class="text-md font-semibold h-5">{{ track.name }}</h1>
      <h1 class="text-xs font-semibold text-gray-500 mt-1">{{ track.artists[0].name }}</h1>
    </div>
  </router-link>
  <loading v-if="!artistTracks" />
</template>
<script setup>
import { RouterLink } from 'vue-router'
import Loading from '@/components/Loading.vue'
import { onMounted, ref } from 'vue'
import { makeGetRequest } from '@/composables/makeGetRequest'

const props = defineProps({
  artistId: String
})


const { makeGetRequest: performGetRequest } = makeGetRequest()
const artistTracks = ref(null)

onMounted(async () => {
  const $url = `http://127.0.0.1:8000/api/spotify/artist/show-${props.artistId}/top-tracks?userId=${localStorage.getItem('user_id')}`
  artistTracks.value = await performGetRequest($url)
})
</script>
