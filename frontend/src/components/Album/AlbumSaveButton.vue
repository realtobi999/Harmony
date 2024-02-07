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
  albumId: String
})

const saved = ref(false)
const { makeGetRequest: performGetRequest, makePutRequest: performPutRequest, makeDeleteRequest: performDeleteRequest } = makeGetRequest()

const save = () => {
  saved.value = !saved.value

  if (saved.value == true) {
    const $url = `http://127.0.0.1:8000/api/spotify/album/save?ids=${props.albumId}&userId=${localStorage.getItem('user_id')}`
    performPutRequest($url)
    localStorage.removeItem('savedAlbums')
  }

  if (saved.value == false) {
    const $url = `http://127.0.0.1:8000/api/spotify/album/unsave?ids=${props.albumId}&userId=${localStorage.getItem('user_id')}`
    performDeleteRequest($url)
    localStorage.removeItem('savedAlbums')
  }

  localStorage.setItem(`album_saved_${props.albumId}`, saved.value.toString())
}

onMounted(async () => {
  if (localStorage.getItem(`album_saved_${props.albumId}`) !== null) {
    saved.value = JSON.parse(localStorage.getItem(`album_saved_${props.albumId}`))
    return
  }

  const $url = `http://127.0.0.1:8000/api/spotify/album/is-saved?ids=${props.albumId}&userId=${localStorage.getItem('user_id')}`
  const isSaved = await performGetRequest($url)
  saved.value = isSaved[0]
  if(saved.value == true){
    localStorage.setItem(`album_saved_${props.albumId}`, saved.value.toString())
  }
})
</script>
