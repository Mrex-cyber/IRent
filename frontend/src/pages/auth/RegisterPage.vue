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
import { getApiErrorMessage } from '@/services/api/client'

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
  isLoading.value = true
  errors.firstName = ''
  errors.lastName = ''
  errors.email = ''
  errors.password = ''
  errors.passwordConfirmation = ''
  errors.role = ''

  try {
    await authStore.register(form)
    router.push('/management/news')
  } catch (error: unknown) {
    errors.password = getApiErrorMessage(error)
  } finally {
    isLoading.value = false
  }
}
</script>
