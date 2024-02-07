<template>
  <button
    @click="followArtist"
    class="border text-lg px-3 py-1 rounded-full border-gray-500 hover:border-white transition-all"
    :class="followed ? 'text-white bg-purple-500' : 'text-purple-500 bg-white'"
  >
    {{ followed ? 'Following' : 'Follow' }}
  </button>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { makeGetRequest } from '@/composables/makeGetRequest'

const props = defineProps({
  artistId: String
})

const {
  makeGetRequest: performGetRequest,
  makePutRequest: performPutRequest,
  makeDeleteRequest: performDeleteRequest
} = makeGetRequest()
const followed = ref(false)

const followArtist = async () => {
  followed.value = !followed.value

  const userId = localStorage.getItem('user_id')
  const artistId = props.artistId
  const storageKey = `artist_followed_${artistId}`

  if (followed.value === true) {
    const url = `http://127.0.0.1:8000/api/spotify/users/follow-artist?userId=${userId}&ids=${artistId}`
    performPutRequest(url)
    localStorage.removeItem('savedArtists')
  }
  
  if (followed.value === false) {
    const url = `http://127.0.0.1:8000/api/spotify/users/unfollow-artist?userId=${userId}&ids=${artistId}`
    performDeleteRequest(url)
    localStorage.removeItem('savedArtists')
  }

  localStorage.setItem(storageKey, followed.value.toString())
}

onMounted(async () => {
  const artistId = props.artistId
  const storageKey = `artist_followed_${artistId}`

  if (localStorage.getItem(storageKey) !== null) {
    const storedData = JSON.parse(localStorage.getItem(storageKey))
    followed.value = storedData
    return
  }
  const userId = localStorage.getItem('user_id')
  const url = `http://127.0.0.1:8000/api/spotify/users/following-artist?ids=${artistId}&userId=${userId}`

  const isAlreadyFollowed = await performGetRequest(url)
  followed.value = isAlreadyFollowed[0]
  localStorage.setItem(storageKey, followed.value.toString())
})

watch(
  () => props.artistId,
  () => {
    followed.value = false
  }
)
</script>
