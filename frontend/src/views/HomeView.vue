<template>
  <section>
    <h1 class="text-5xl mb-3 truncate text-purple-300">Welcome - <span class="text-purple-500 truncate">User</span> </h1>
    <div class="py-2 px-1 border-neutral-900 rounded bg-neutral-900">
      <home-featured-playlists />
    </div>

    <div class="mt-7">
      <div class="flex gap-3 items-center"> 
        <h1 class="text-5xl mb-3">Statistics </h1>
        <div @click="refreshStatistics">
          <ion-icon v-pre class="text-3xl mt-1 cursor-pointer hover:text-purple-500 transition-all" name="refresh"></ion-icon>
        </div>
      </div>
      <home-statistics />
    </div>
  </section>
</template>

<script setup>
import { useRoute } from 'vue-router'
import HomeFeaturedPlaylists from '@/components/Home/HomeFeaturedPlaylists.vue'
import HomeStatistics from '@/components/Home/HomeStatistics.vue'

const route = useRoute();

const refreshStatistics = () => {
  localStorage.removeItem('recentTracks')
  localStorage.removeItem('topPlayedTracks')
  localStorage.removeItem('topArtists')
  location.reload()
}

route.meta.layout = 'HarmonyLayout'

</script>


