<template>
  <div class="w-[85%] mx-auto mt-10" v-if="artist">
    <div class="flex flex-col lg:flex-row items-center gap-3">
      <div class="w-[70%] lg:w-[20%]">
        <img :src="artist.images[0].url" class="rounded-xl" />
      </div>
      <div>
        <h1 class="text-5xl font-semibold text-purple-500 mb-2">{{ artist.name }}</h1>
        <p class="text-xl text-zinc-400">
          {{ formatFollowersWithCommas(artist.followers.total) }} Followers
        </p>
        <div class="flex mt-3 lg:mt-10 gap-2">
          <a
            :href="artist.uri"
            class="bg-green-500 text-white rounded-full px-5 py-2 font-bold text-2xl hover:bg-green-400"
          >
            Play!
          </a>
          <artist-follow-button :artistId="artist.id" />
        </div>
      </div>
    </div>
    <div>
      <h1 class="text-2xl text-purple-300 font-semibold mt-10 mb-1">Top Songs</h1>
      <div>
        <artist-tracks :artistId="artist.id"/>
      </div>
      <h1 class="text-2xl text-purple-300 font-semibold mt-4 mb-1">Top Albums</h1>
      <div class="grid grid-cols-3 gap-2 lg:flex">
        <artist-albums :artistId="artist.id" />
      </div>
      <h1 class="text-2xl text-purple-300 font-semibold mt-4 mb-1">Related Artists</h1>
      <div class="grid grid-cols-3 gap-2 lg:flex">
        <artist-related-artists :artistId="artist.id" />
      </div>
    </div>
  </div>
  <loading v-if="!artist" />
</template>
<script setup>
import { useRoute } from 'vue-router'
import Loading from '@/components/Loading.vue'
import ArtistTracks from '@/components/Artist/ArtistTracks.vue'
import ArtistAlbums from '@/components/Artist/ArtistAlbums.vue'
import ArtistRelatedArtists from '@/components/Artist/ArtistRelatedArtists.vue'
import { onMounted, ref, watch } from 'vue'
import { makeGetRequest } from '@/composables/makeGetRequest'
import ArtistFollowButton from '@/components/Artist/ArtistFollowButton.vue'

const route = useRoute()

route.meta.layout = 'HarmonyLayout'
const { makeGetRequest: performGetRequest } = makeGetRequest()

const artist = ref(null)


onMounted(async () => {
  const userId = localStorage.getItem('user_id')
  const artistId = route.params.artistId
  const $url = `http://127.0.0.1:8000/api/spotify/artist/show-${artistId}?userId=${userId}`
  artist.value = await performGetRequest($url)
})
function formatFollowersWithCommas(input) {
  if (typeof input !== 'number') {
    // Handle non-numeric input
    console.error('Input must be a number')
    return null
  }

  const formattedNumber = input.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
  return formattedNumber
}

watch(
  () => route.params.artistId,
  () => {
    location.reload()
  }
)
</script>
