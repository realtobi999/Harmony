<template>
  <button
    class="hover:scale-95  hover:bg-purple-800 text-white text-base font-semibold rounded-lg transition-all duration-300 border border-neutral-800 focus:outline-none focus:border-zinc-700"
    :class="currentFilter === name ? 'bg-purple-500' : 'bg-neutral-800'"
  >
    {{ name }}
  </button>
</template>

<script setup>

defineProps({
  name: String,
  currentFilter: String,
});

</script>
