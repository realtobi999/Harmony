<template>
  <router-link
    v-if="artistAlbums"
    v-for="album in artistAlbums.items"
    class="border w-full lg:w-1/5 border-zinc-800 rounded bg-zinc-800 py-3 hover:scale-95 transition-all cursor-pointer hover:bg-zinc-600"
    :to="`/harmony/album/${album.id}`"
  >
    <img :src="album.images[0].url" class="rounded px-1" />
    <h1 class="text-xl truncate font-semibold mt-1 px-1">{{ album.name }}</h1>
    <p class="text-sm text-zinc-400 px-1">{{ album.artists[0].name }}</p>
  </router-link>
  <loading v-if="!artistAlbums" />
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
const artistAlbums = ref(null)

onMounted(async () => {
  const $url = `http://127.0.0.1:8000/api/spotify/artist/show-${props.artistId}/albums?userId=${localStorage.getItem('user_id')}&limit=5`
  artistAlbums.value = await performGetRequest($url)
})
</script>
