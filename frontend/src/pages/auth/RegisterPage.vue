<template>
  <div>
    <v-card-title class="text-center">
      <h2>Sign Up</h2>
    </v-card-title>

    <v-form @submit.prevent="handleRegister">
      <v-text-field
        v-model="form.firstName"
        label="First Name"
        required
        :error-messages="errors.firstName"
      ></v-text-field>

      <v-text-field
        v-model="form.lastName"
        label="Last Name"
        required
        :error-messages="errors.lastName"
      ></v-text-field>

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

      <v-text-field
        v-model="form.passwordConfirmation"
        label="Confirm Password"
        type="password"
        required
        :error-messages="errors.passwordConfirmation"
      ></v-text-field>

      <v-select
        v-model="form.role"
        label="Role"
        :items="roleOptions"
        required
        :error-messages="errors.role"
      ></v-select>

      <v-btn type="submit" color="primary" block :loading="isLoading" class="mt-4"> Sign Up </v-btn>
    </v-form>

    <div class="text-center mt-4">
      <router-link to="/auth/login"> Already have an account? Sign in </router-link>
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
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  passwordConfirmation: '',
  role: 'Tenant'
})

const errors = reactive({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  passwordConfirmation: '',
  role: ''
})

const isLoading = ref(false)

const roleOptions = [
  { title: 'Tenant', value: 'Tenant' },
  { title: 'Apartment Owner', value: 'ApartmentOwner' },
  { title: 'OSBB Representative', value: 'OSBBRepresentative' },
  { title: 'Realtor', value: 'Realtor' }
]

const handleRegister = async () => {
  try {
    isLoading.value = true
    Object.keys(errors).forEach((key) => {
      errors[key as keyof typeof errors] = ''
    })

    await authStore.register(form)
    router.push('/dashboard')
  } catch (error: any) {
    console.error('Registration failed:', error)
    
    // Handle validation errors
    if (error.message) {
      try {
        const validationErrors = JSON.parse(error.message)
        Object.keys(validationErrors).forEach((key) => {
          const camelKey = key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase())
          if (errors.hasOwnProperty(camelKey)) {
            errors[camelKey as keyof typeof errors] = Array.isArray(validationErrors[key])
              ? validationErrors[key].join(', ')
              : validationErrors[key]
          }
        })
      } catch {
        // If not JSON, show general error
        errors.password = error.message || 'Registration failed. Please try again.'
      }
    }
  } finally {
    isLoading.value = false
  }
}
</script>
