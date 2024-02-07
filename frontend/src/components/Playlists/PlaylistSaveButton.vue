<template>
  <div :class="saved ? 'text-green-600' : 'text-zinc-400'" class="mt-3">
    <ion-icon
      @click="save"
      class="text-4xl cursor-pointer hover:text-green-600 transition-all"
      name="checkmark-circle"
    ></ion-icon>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { makeGetRequest } from '@/composables/makeGetRequest'

const props = defineProps({
  playlistId: String
})

const saved = ref(false)
const {
  makeGetRequest: performGetRequest,
  makePutRequest: performPutRequest,
  makeDeleteRequest: performDeleteRequest
} = makeGetRequest()

const save = () => {
  saved.value = !saved.value

  if (saved.value == true) {
    const $url = `http://127.0.0.1:8000/api/spotify/users/follow-playlist?playlistId=${props.playlistId}&userId=${localStorage.getItem('user_id')}`
    performPutRequest($url)
    localStorage.removeItem('savedPlaylists')
  }

  if (saved.value == false) {
    const $url = `http://127.0.0.1:8000/api/spotify/users/unfollow-playlist?playlistId=${props.playlistId}&userId=${localStorage.getItem('user_id')}`
    performDeleteRequest($url)
    localStorage.removeItem('savedPlaylists')
  }

  localStorage.setItem(`album_saved_${props.playlistId}`, saved.value.toString())
}

onMounted(async () => {
  if (localStorage.getItem(`album_saved_${props.playlistId}`) !== null) {
    saved.value = JSON.parse(localStorage.getItem(`album_saved_${props.playlistId}`))
    return
  }

  const $url = `http://127.0.0.1:8000/api/spotify/users/following/playlist?playlistId=${props.playlistId}&userId=${localStorage.getItem('user_id')}`
  const isSaved = await performGetRequest($url)
  console.log(isSaved)
  saved.value = isSaved[0]
  if (saved.value == true) {
    localStorage.setItem(`album_saved_${props.playlistId}`, saved.value.toString())
  }
})
</script>
