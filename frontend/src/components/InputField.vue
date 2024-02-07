<template>
  <div>
    <label :for="name" class="block text-sm text-gray-400 mb-1">{{ label }}</label>
    <input
      type="text"
      :name="name"
      v-model="value"
      :placeholder="placeholder"
      class="mt-1 w-full px-4 py-2 border border-zinc-700 bg-zinc-600 rounded-md text-white focus:outline-none focus:border-purple-500 transition-all duration-300"
    />
    <p class="text-red-500 text-sm mt-1">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  name: String,
  label: String,
  placeholder: String,
  error: String
})
</script>
