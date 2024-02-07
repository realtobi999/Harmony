<!-- eslint-disable vue/valid-v-for -->
<template>
    <router-link
        :to="`/harmony/track/${track.id}`"
        class="flex w-full items-center gap-3 hover:bg-zinc-700 hover:rounded-md transition-all px-3 py-2 cursor-pointer"
        v-for="track in props.tracks.items"
    >
        <img :src="track.album.images[0].url" class="w-12 h-12 rounded-lg shadow-md" />
        <div class="text-left truncate">
            <h1 class="text-md font-semibold h-5"> {{ track.name }}</h1>
            <h1 class="text-xs mt-2 font-semibold text-gray-500">
                {{ track.artists[0].name }}
            </h1>
        </div>
    </router-link>
</template>
<script setup>
import { RouterLink } from 'vue-router'
const props = defineProps({
  tracks: Object
})
</script>
