<template>
  <main class="min-h-screen flex items-center justify-center">
    <section class="w-full max-w-md p-8 bg-zinc-800 rounded-lg shadow-md">
      <h1 class="text-4xl text-center mb-6 text-purple-500">
        Login to <span class="hover:text-purple-300 font-semibold transition-all">Harmony</span>
      </h1>
      <div class="space-y-4">
        <div>
          <label for="email" class="block text-sm text-gray-400 mb-1">Email</label>
          <input
            type="text"
            name="email"
            v-model="form.email"
            placeholder="Enter your Email"
            class="mt-1 w-full px-4 py-2 border border-zinc-700 bg-zinc-600 rounded-md text-white focus:outline-none focus:border-purple-500 transition-all duration-300"
          />
          <p class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
        </div>
        <div>
          <label for="password" class="block text-sm text-gray-400 mb-1">Password</label>
          <input
            type="password"
            name="password"
            v-model="form.password"
            placeholder="Enter your Password"
            class="mt-1 w-full px-4 py-2 border border-zinc-700 bg-zinc-600 rounded-md text-white focus:outline-none focus:border-purple-500 transition-all duration-300"
          />
          <p class="text-red-500 text-sm mt-1">{{ errors.password[0] }}</p>
        </div>

        <p v-if="errors.message" class="text-red-500 text-sm mt-1">{{ errors.message }}</p>
        <p class="text-gray-300 text-sm">
          Don't have an account yet?
          <RouterLink to="/register" class="text-purple-500 hover:underline">Register</RouterLink>
        </p>

        <button
          @click="submitForm"
          class="w-full bg-purple-500 text-white rounded-md py-2 transition-all hover:bg-purple-600"
        >
          Continue
        </button>
      </div>
    </section>
  </main>
</template>

<script setup>
import { reactive} from 'vue'
import { RouterLink } from 'vue-router'

const form = reactive({
  email: '',
  password: ''
})

const errors = reactive({
  email: [],
  password: [],
  message: ''
})

const submitForm = async () => {
  // Clear all errors before making the API call
  errors.email = []
  errors.password = []
  errors.message = ''

  try {
    const response = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(form)
    })

    // Handle HTTP errors
    if (!response.ok) {
      const errorData = await response.json()

      if (response.status === 401) {
        errors.message = errorData.message || []
      } else if (response.status === 422) {
        errors.email = errorData.errors.email || []
        errors.password = errorData.errors.password || []
      }
    }

    const data = await response.json()

    document.cookie = "harmony_token=" + data.harmony_token + "; expires=" + new Date(new Date().getTime() + 3600000).toUTCString() + "; Secure; SameSite=None";
    localStorage.setItem('user_id', data.user_id)
    
    await SpotifyAuthLogin()
  } catch (error) {
    // Handle other errors
    console.error('Error:', error)
  }
}

const SpotifyAuthLogin = async () => {

  const spotifyAuthUrl = generateSpotifyAuthUrl();
  window.location.href = spotifyAuthUrl;
}

function generateSpotifyAuthUrl() {
  const state = Math.random().toString(36).substring(2, 18);

  const scope = 'user-read-private user-read-email user-top-read user-read-recently-played ' +
    'playlist-read-private playlist-modify-private playlist-modify-public ' +
    'user-library-modify user-follow-modify playlist-modify-private user-follow-read ' +
    'user-library-read';

  // Build query parameters for Spotify authorization URL
  const queryParams = {
    'response_type': 'code',
    'client_id': "81f1bbc7cf0840afa5706ed3b67c19d1", 
    'redirect_uri': "http://localhost:5173/harmony/callback",
    'state': state,
    'scope': scope,
    'show_dialog': true,
  };

  // Construct the Spotify authorization URL
  const spotifyAuthUrl = 'https://accounts.spotify.com/authorize?' + new URLSearchParams(queryParams);

  return spotifyAuthUrl;
}

</script>