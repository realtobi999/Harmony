import { createRouter, createWebHistory } from 'vue-router'
import HarmonyLayout from '@/layouts/HarmonyLayout.vue'

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
          component: () => import('../views/HomeView.vue')
        },
        {
          path: 'search',
          name: 'search',
          component: () => import('../views/SearchView.vue')
        },
        {
          path: 'playlist/:playlistId',
          name: 'playlist',
          component: () => import('../views/PlaylistView.vue')
        },
        {
          path: 'album/:albumId',
          name: 'album',
          component: () => import('../views/AlbumView.vue')
        },
        {
          path: 'artist/:artistId',
          name: 'artist',
          component: () => import('../views/ArtistView.vue')
        },
        {
          path: 'track/:trackId',
          name: 'track',
          component: () => import('../views/TrackView.vue')
        }
      ]
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('../views/LogoutView.vue')
    },
    {
      path: '/harmony/callback',
      name: 'callback',
      component: () => import('../views/CallbackView.vue')
    },
    {
      path: '/',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue')
    }
  ]
})

router.beforeEach(async (to, from, next) => {
  const cookies = document.cookie.split('; ')
  let harmonyToken

  const verifyToken = async (token) => {
    const userId = localStorage.getItem('user_id')

    if (!userId) {
      console.error('User ID is null or undefined.')
      return false
    }

    try {
      const response = await fetch(
        `http://127.0.0.1:8000/api/auth/verify-token?token=${token}&userId=${userId}`,
        {
          method: 'GET'
        }
      )

      if (!response.ok) {
        console.error('Failed to verify token.')
        return false
      }

      return true
    } catch (error) {
      console.error('Error verifying token:', error)
      return false
    }
  }

  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].split('=')
    if (cookie[0] === 'harmony_token') {
      harmonyToken = cookie[1]
      break
    }
  }

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!harmonyToken) {
      next({ name: 'login' })
    } else {
      if (!(await verifyToken(harmonyToken))) {
        console.log('Token is invalid or expired, redirecting to login page.')
        next({ name: 'login' })
        return
      }
      console.log('Token is valid, allowing access.')
      next()
    }
  } else {
    next()
  }
})

export default router
