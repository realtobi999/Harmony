<template>
    <div :class="saved ? 'text-green-800' : 'text-zinc-400'" @click="save">
      <ion-icon
        v-pre
        class="text-5xl cursor-pointer hover:text-green-800 transition-all"
        name="bookmark"
      ></ion-icon>
    </div>
  </template>
  <script setup>
  import { ref, onMounted } from 'vue'
  import { makeGetRequest } from '@/composables/makeGetRequest'
  
  const props = defineProps({
    trackId: String,
  })
  
  const saved = ref(false)
  const {
    makePutRequest: performPutRequest,
    makeDeleteRequest: performDeleteRequest
  } = makeGetRequest()
  
  const save = () => {
    saved.value = !saved.value
  
    if (saved.value == true) {
      const $url = `http://127.0.0.1:8000/api/spotify/track/save?ids=${props.trackId}&userId=${localStorage.getItem('user_id')}`
      performPutRequest($url)
      localStorage.removeItem('savedTracks')
    }
  
    if (saved.value == false) {
      const $url = `http://127.0.0.1:8000/api/spotify/track/unsave?ids=${props.trackId}&userId=${localStorage.getItem('user_id')}`
      performDeleteRequest($url)
      localStorage.removeItem('savedTracks')
    }
  
    localStorage.setItem(`track_saved_${props.trackId}`, saved.value.toString())
  }
  
  onMounted(async () => {
    if (localStorage.getItem(`track_saved_${props.trackId}`) !== null) {
      saved.value = JSON.parse(localStorage.getItem(`track_saved_${props.trackId}`))
    }
  })
  </script>
  