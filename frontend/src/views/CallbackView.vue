<template>
  <!-- Your template content goes here -->
</template>

<script setup>
import router from '@/router';
import { onMounted } from 'vue'

const makeRequest = async () => {
  // Obtain the authorization code and state from the callback request
  const urlParams = new URLSearchParams(window.location.search)
  const code = urlParams.get('code')
  const state = urlParams.get('state')

  // Check for a valid state to prevent CSRF attacks
  if (state === null) {
    // Redirect or handle the state mismatch error
    return
  }

  // Spotify token endpoint URL
  const spotifyTokenUrl = 'https://accounts.spotify.com/api/token'

  // Options for obtaining access token using authorization code
  const authOptions = {
    grant_type: 'authorization_code',
    code: code,
    redirect_uri: 'http://localhost:5173/harmony/callback', // Replace with your actual redirect URI
    client_id: '81f1bbc7cf0840afa5706ed3b67c19d1', // Replace with your actual client ID
    client_secret: 'd21f98778a4448499f6a53872651763d' // Replace with your actual client secret
  }

  try {
    // Send a POST request to Spotify token endpoint to obtain access token
    const response = await fetch(spotifyTokenUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        Authorization: 'Basic ' + btoa(authOptions.client_id + ':' + authOptions.client_secret)
      },
      body: new URLSearchParams(authOptions)
    })

    const responseData = await response.json()

    // Store tokens on your backend
    const tokensResponse = await fetch('http://127.0.0.1:8000/api/auth/store-tokens', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        tokens: {
          access_token: responseData.access_token,
          refresh_token: responseData.refresh_token
        },
        userId: localStorage.getItem('user_id')
      })
    })

    // Handle the response from storing tokens if needed
    const tokensData = await tokensResponse.json()
  } catch (error) {
    // Handle errors
    console.error('Error:', error)
  }
}

onMounted(() => {
  makeRequest()
  router.push('/harmony/home')
})
</script>
