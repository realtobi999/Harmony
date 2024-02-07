<template>
  <main>
    <div class="flex text-2xl lg:flex-row max-h-screen" :class="[isAsideOpen ? 'flex-row' : 'flex-col']">
      <div
        :class="[isAsideOpen ? 'hidden' : 'block']"
        class="lg:hidden sticky top-0 z-50"
      >
        <ion-icon @click="toggleAside" name="menu-outline" class="p-5 cursor-pointer text-5xl"></ion-icon>
      </div>
      <aside-panel
        :class="[isAsideOpen ? 'absolute w-1/2 h-screen z-50 overflow-y-auto' : 'hidden']"
        class="lg:block lg:overflow-y-auto lg:max-h-screen"
      />
      <div class="max-h-screen overflow-y-auto w-full px-5 lg:p-10">
        <div @click="handleContentClick" :class="[isAsideOpen ? 'py-10 opacity-25 pointer-events-auto' : 'py-0']">
          <router-view />
        </div>
        <div class="p-5 lg:p-10 right-0 top-0 fixed" :class="[isAsideOpen ? 'hidden' : 'block']">
          <img src="/album.png" class="rounded-full w-12" />
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { ref } from 'vue'
import AsidePanel from '@/components/Aside/AsidePanel.vue'

const isAsideOpen = ref(false)

const toggleAside = () => {
  isAsideOpen.value = !isAsideOpen.value
}

const handleContentClick = () => {
  if (isAsideOpen.value) {
    isAsideOpen.value = false
  }
}
</script>

<script>
export default {
  name: 'HarmonyLayout'
}
</script>

<style>
::-webkit-scrollbar {
  width: 12px;
}

::-webkit-scrollbar-thumb {
  background-color: rgba(9, 9, 11, 0.371);
  border-radius: 6px;
}

::-webkit-scrollbar-track {
  background-color: rgb(35, 35, 37);
}

#scrollable-content {
  scrollbar-width: thin;
  scrollbar-color: rgb(24 24 27);
}
</style>
