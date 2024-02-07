<!-- eslint-disable no-unused-vars -->
<template>
    <router-link :to="`/harmony/track/${id}`">
      <th
        scope="row"
        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white truncate w-full max-w-[300px]"
      >
        <div class="flex items-center gap-2">
          <img :src="img" class="w-[45px] rounded-md shadow-md" />
          <div class="truncate w-full">
            <h2 class="text-lg font-bold h-6 truncate" title="{{ trackName }}">{{ trackName }}</h2>
            <p class="text-sm text-gray-500 truncate" title="{{ artistName }}">{{ artistName }}</p>
          </div>
        </div>
      </th>
    </router-link>
  
    <td class="px-6 py-4 truncate w-1/4 max-w-[300px]"><router-link :to="`/harmony/album/${albumId}`" class="hover:text-white">{{ albumName }}</router-link></td>

    <td v-if="includeDate" class="px-6 py-4 truncate w-1/4 max-w-[300px]">{{ formatTimestamp(date) }}</td>

    <td class="py-4">
      <div class="flex items-center justify-center">
        <playlist-track-save-button :trackId="id" />
      </div>
    </td>
</template>

<script setup>
import { RouterLink } from 'vue-router'
import PlaylistTrackSaveButton from '@/components/Playlists/PlaylistTrackSaveButton.vue';

defineProps({
  trackName : String,
  artistName : String,
  img: String,
  id: String,
  albumName: String,
  includeDate: String,
  date: String,
  albumId: String,
})

function formatTimestamp(timestamp) {
  const now = new Date();
  const timeAgo = new Date(timestamp);
  const timeDifference = now - timeAgo;
  
  const minutes = Math.floor(timeDifference / (1000 * 60));
  if (minutes < 60) {
    return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
  }

  const hours = Math.floor(timeDifference / (1000 * 60 * 60));
  if (hours < 24) {
    return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
  }

  const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
  if (days < 7) {
    return `${days} day${days !== 1 ? 's' : ''} ago`;
  }

  const weeks = Math.floor(days / 7);
  if (weeks < 52) {
    return `${weeks} week${weeks !== 1 ? 's' : ''} ago`;
  }

  const years = Math.floor(weeks / 52);
  return `${years} year${years !== 1 ? 's' : ''} ago`;
}

</script>
