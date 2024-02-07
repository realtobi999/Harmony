<template>
  <router-link
    v-if="relatedArtists"
    v-for="artist in relatedArtists.artists.slice(0, 5)"
    :to="`/harmony/artist/${artist.id}`"
    class="border w-full lg:w-1/5 border-zinc-800 rounded bg-zinc-800 py-3 hover:scale-95 transition-all cursor-pointer hover:bg-zinc-600"
  >
    <img :src="artist.images[0].url" class="rounded px-1" />
    <h1 class="text-xl truncate font-semibold mt-1 px-1">{{ artist.name }}</h1>
    <p class="text-sm text-zinc-400 px-1">Artist</p>
  </router-link>
  <loading v-if="!relatedArtists" />
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
const relatedArtists = ref(null)

onMounted(async () => {
  const $url = `http://127.0.0.1:8000/api/spotify/artist/show-${props.artistId}/related-artists?userId=${localStorage.getItem('user_id')}`
  relatedArtists.value = await performGetRequest($url)
})
</script>
