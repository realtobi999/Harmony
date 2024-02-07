<template>
  <main class="min-h-screen flex items-center justify-center">
    <section class="w-full max-w-md p-8 bg-zinc-800 rounded-lg shadow-md">
      <h1 class="text-4xl text-center mb-6 text-purple-500">
        Register to <span class="hover:text-purple-300 font-semibold transition-all">Harmony</span>
      </h1>
      <div>
        <div class="space-y-4">
          <div>
            <label for="username" class="block text-sm text-gray-400 mb-1">Username</label>
            <input
              type="text"
              name="username"
              v-model="form.username"
              placeholder="Enter your username"
              class="mt-1 w-full px-4 py-2 border border-zinc-700 bg-zinc-600 rounded-md text-white focus:outline-none focus:border-purple-500 transition-all duration-300"
            />
            <p class="text-red-500 text-sm mt-1">{{ errors.username[0] }}</p>
          </div>
          <div>
            <label for="email" class="block text-sm text-gray-400 mb-1">Email</label>
            <input
              type="text"
              name="email"
              v-model="form.email"
              placeholder="Enter your email"
              class="mt-1 w-full px-4 py-2 border border-zinc-700 bg-zinc-600 rounded-md text-white focus:outline-none focus:border-purple-500 transition-all duration-300"
            />
            <p class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
          </div>
          <div>
            <label for="password" class="block text-sm text-gray-400 mb-1">Password</label>
            <input
              type="text"
              name="password"
              v-model="form.password"
              placeholder="Enter your Password"
              class="mt-1 w-full px-4 py-2 border border-zinc-700 bg-zinc-600 rounded-md text-white focus:outline-none focus:border-purple-500 transition-all duration-300"
            />
            <p class="text-red-500 text-sm mt-1">{{ errors.password[0] }}</p>
          </div>
          <div>
            <label for="password_confirmation" class="block text-sm text-gray-400 mb-1"
              >Repeat Password</label
            >
            <input
              type="text"
              name="password_confirmation"
              v-model="form.password_confirmation"
              placeholder="Enter your Password again"
              class="mt-1 w-full px-4 py-2 border border-zinc-700 bg-zinc-600 rounded-md text-white focus:outline-none focus:border-purple-500 transition-all duration-300"
            />
            <p class="text-red-500 text-sm mt-1">{{ errors.password_confirmation[0] }}</p>
          </div>
        </div>
        <p class="text-gray-300 text-sm mt-3">
          Already have an account?
          <RouterLink to="/" class="text-purple-500 hover:underline">Login</RouterLink>
        </p>

        <div class="mt-5">
          <button
            @click="submitForm"
            class="w-full bg-purple-500 text-white rounded-md py-2 transition-all hover:bg-purple-600"
          >
            Register
          </button>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import InputField from '@/components/InputField.vue'
import { reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'

const form = reactive({
  username: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const errors = reactive({
  username: [],
  email: [],
  password: [],
  password_confirmation: []
})
const submitForm = async () => {
  // Clear all errors before making the API call
  errors.username = [];
  errors.email = [];
  errors.password = [];
  errors.password_confirmation = [];

  try {
    const response = await fetch('http://127.0.0.1:8000/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(form)
    });

    // Handle HTTP errors
    if (!response.ok) {
      const errorData = await response.json();

      // Set specific errors based on the errorData received
      if (errorData.errors) {
        errors.username = errorData.errors.username || [];
        errors.email = errorData.errors.email || [];
        errors.password = errorData.errors.password || [];
        errors.password_confirmation = errorData.errors.password_confirmation || [];
      }
    }
  } catch (error) {
    // Handle errors
    console.error('Error:', error);
  }
};

</script>

