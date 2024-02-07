<template>
    <aside class="w-64 h-full overflow-y-auto min-h-screen bg-neutral-800 md:w-[60%] lg:w-[25%] rounded-lg">
    <div>
      <h1 class="text-4xl font-semibold text-purple-500 px-5 pt-5 text-3xl md:text-5xl">
        <span class="hover:text-purple-300 font-semibold transition-all">Harmony</span>
      </h1>
      <hr class="my-3 w-[90%] mx-auto border-zinc-700 border" />
      <div class="space-y-3 px-5 md:px-6">
        <aside-nav-link name="Home" icon="home" link="/harmony/home" />
        <aside-nav-link name="Search" icon="search" link="/harmony/search" />
        <aside-nav-link name="Exit" icon="exit" link="/logout" />
      </div>
      <hr class="my-3 w-[90%] mx-auto border-zinc-700 border" />
      <div class="px-5 md:px-6">
        <h1 class="text-3xl font-semibold text-purple-500">
          <span class="hover:text-purple-300 transition-all font-semibold">Library</span>
        </h1>
        <hr class="my-3 w-[100%] mx-auto border-zinc-700 border" />
        <div class="grid grid-cols-2 gap-1 mt-3">
          <filter-button name="Playlists" :currentFilter="currentFilter" @click="setFilter('Playlists')"/>
          <filter-button name="Albums" :currentFilter="currentFilter" @click="setFilter('Albums')"/>
          <filter-button name="Artists" :currentFilter="currentFilter" @click="setFilter('Artists')"/>
          <filter-button name="Tracks" :currentFilter="currentFilter" @click="setFilter('Tracks')"/>
        </div>
      </div>
      <div class="px-5 md:px-6">
        <aside-playlists v-if="currentFilter === 'Playlists'" />
        <aside-albums v-if="currentFilter === 'Albums'" />
        <aside-artists v-if="currentFilter === 'Artists'" />
        <aside-tracks v-if="currentFilter === 'Tracks'" />
      </div>
    </div>
  </aside>
</template>

<script setup>
import FilterButton from '@/components/Aside/FilterButton.vue'
import AsidePlaylists from '@/components/Aside/AsidePlaylists.vue'
import AsideAlbums from '@/components/Aside/AsideAlbums.vue'
import AsideNavLink from '@/components/Aside/AsideNavLink.vue'
import AsideTracks from '@/components/Aside/AsideTracks.vue'
import AsideArtists from './AsideArtists.vue'
import { ref, onMounted } from 'vue'

const currentFilter = ref('Playlists');

const setFilter = (filter) => {
  currentFilter.value = filter
  localStorage.setItem('currentFilter', filter)
}

onMounted(() => {
  const storedFilter = localStorage.getItem('currentFilter')
  if (storedFilter) {
    currentFilter.value = storedFilter
  }
})

</script>
