import { createRouter, createWebHistory } from 'vue-router';
import HarmonyLayout from '@/layouts/HarmonyLayout.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/harmony',
      component: HarmonyLayout,
      meta: {
        requiresAuth: true
      },
      children: [
        {
          path: 'home',
          name: 'home',
          component: () => import('../views/HomeView.vue'),
        },
        {
          path: 'search',
          name: 'search',
          component: () => import('../views/SearchView.vue'),
        },
        {
          path: 'playlist/:playlistId',
          name: 'playlist',
          component: () => import('../views/PlaylistView.vue'),
        },
        {
          path: 'album/:albumId',
          name: 'album',
          component: () => import('../views/AlbumView.vue'),
        },
        {
          path: 'artist/:artistId',
          name: 'artist',
          component: () => import('../views/ArtistView.vue'),
        },
        {
          path: 'track/:trackId',
          name: 'track',
          component: () => import('../views/TrackView.vue'),
        },
      ],
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('../views/LogoutView.vue'),
    },
    {
      path: '/harmony/callback',
      name: 'callback',
      component: () => import('../views/CallbackView.vue'),
    },
    {
      path: '/',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
    },
  ],
});

router.beforeEach((to, from, next) => {
  if(to.matched.some(record => record.meta.requiresAuth)) {
    if (localStorage.getItem('harmony_token') === null) {
      next({ name: 'login' });
    } else {
      next();
    }
  } else {
    next();
  }
})

export default router;
