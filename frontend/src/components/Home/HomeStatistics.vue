<template>
  <div class="flex gap-3 flex-col md:flex-row">
    <div class="py-2 px-1 border-zinc-800 rounded bg-zinc-800 md:w-1/3">
      <h1 class="mb-3 text-xl rounded-lg px-3 font-semibold text-center bg-zinc-700">Recent Played Tracks</h1>
      <home-statistics-recent />
    </div>

    <div class="border py-2 px-1 border-zinc-800 rounded bg-zinc-800 md:w-1/3">
      <h1 class="mb-3 text-xl rounded-lg px-3 font-semibold text-center bg-zinc-700">Most Played Tracks</h1>
      <home-statistics-most-played />
    </div>

    <div class="border py-2 px-1 border-zinc-800 rounded bg-zinc-800 md:w-1/3">
      <h1 class="mb-3 text-xl rounded-lg px-3 font-semibold text-center bg-zinc-700">Favorite Artists</h1>
      <home-statistics-top-artists />
    </div>
  </div>
</template>
<script setup>
import HomeStatisticsRecent from './HomeStatisticsRecent.vue';
import HomeStatisticsMostPlayed from './HomeStatisticsMostPlayed.vue';
import HomeStatisticsTopArtists from './HomeStatisticsTopArtists.vue';
</script>
