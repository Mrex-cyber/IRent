<template>
  <div>
    <v-card-title class="text-center">
      <h2>Sign In</h2>
    </v-card-title>

    <v-form @submit.prevent="handleLogin">
      <v-text-field
        v-model="form.email"
        label="Email"
        type="email"
        required
        :error-messages="errors.email"
      ></v-text-field>

      <v-text-field
        v-model="form.password"
        label="Password"
        type="password"
        required
        :error-messages="errors.password"
      ></v-text-field>

      <v-btn type="submit" color="primary" block :loading="isLoading" class="mt-4"> Sign In </v-btn>
    </v-form>

    <div class="text-center mt-4">
      <router-link to="/auth/register"> Don't have an account? Sign up </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: ''
})

const errors = reactive({
  email: '',
  password: ''
})

const isLoading = ref(false)

const handleLogin = async () => {
  try {
    isLoading.value = true
    errors.email = ''
    errors.password = ''

    await authStore.login(form)
    router.push('/dashboard')
  } catch (error) {
    console.error('Login failed:', error)
  } finally {
    isLoading.value = false
  }
}
</script>
