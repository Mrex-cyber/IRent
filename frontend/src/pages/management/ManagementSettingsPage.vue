<template>
  <v-container fluid class="pa-0">
    <div class="settings-page">
      <div class="page-container">
        <div class="header-section">
          <h1 class="page-title">Settings</h1>
          <p class="page-subtitle">Manage OSBB configuration and application preferences</p>
        </div>

        <div v-if="error" class="error-state">{{ error }}</div>
        <div v-else-if="isLoading" class="loading-state">Loading settings…</div>

        <template v-else>
          <div v-for="(items, group) in settings" :key="group" class="settings-group">
            <h2 class="group-title">{{ formatGroupLabel(String(group)) }}</h2>
            <div class="settings-cards">
              <div v-for="item in items" :key="item.key" class="setting-item">
                <div class="setting-label">{{ item.label }}</div>
                <div class="setting-control">
                  <label v-if="item.type === 'boolean'" class="toggle-wrapper">
                    <input
                      type="checkbox"
                      :checked="item.value === true"
                      class="toggle-input"
                      @change="
                        (e) => updateSetting(item.key, (e.target as HTMLInputElement).checked)
                      "
                    />
                    <span class="toggle-slider"></span>
                  </label>
                  <input
                    v-else
                    :type="item.type === 'number' ? 'number' : 'text'"
                    :value="item.value"
                    class="text-input"
                    @change="(e) => updateSetting(item.key, (e.target as HTMLInputElement).value)"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="save-section">
            <button class="save-button" :disabled="isSaving" @click="saveSettings">
              {{ isSaving ? 'Saving…' : 'Save Changes' }}
            </button>
            <span v-if="saveSuccess" class="save-success">Settings saved successfully</span>
          </div>
        </template>
      </div>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiClient from '@/services/api/client'

interface SettingItem {
  key: string
  value: string | number | boolean
  type: string
  label: string
}

type SettingsGroups = Record<string, SettingItem[]>

const settings = ref<SettingsGroups>({})
const pendingChanges = ref<Record<string, string | number | boolean>>({})
const isLoading = ref(false)
const isSaving = ref(false)
const error = ref<string | null>(null)
const saveSuccess = ref(false)

const groupLabels: Record<string, string> = {
  general: 'General',
  billing: 'Billing',
  notifications: 'Notifications',
  announcements: 'Announcements'
}

const formatGroupLabel = (group: string) => groupLabels[group] ?? group

const fetchSettings = async () => {
  isLoading.value = true
  error.value = null
  try {
    const { data } = await apiClient.get<SettingsGroups>('management/settings')
    settings.value = data
    pendingChanges.value = {}
  } catch {
    error.value = 'Failed to load settings'
  } finally {
    isLoading.value = false
  }
}

const updateSetting = (key: string, value: string | boolean) => {
  pendingChanges.value[key] = value
  for (const group of Object.values(settings.value)) {
    const item = group.find((i) => i.key === key)
    if (item) {
      item.value = value
      break
    }
  }
}

const saveSettings = async () => {
  if (Object.keys(pendingChanges.value).length === 0) return
  isSaving.value = true
  saveSuccess.value = false
  try {
    const { data } = await apiClient.put<SettingsGroups>('management/settings', {
      settings: pendingChanges.value
    })
    settings.value = data
    pendingChanges.value = {}
    saveSuccess.value = true
    setTimeout(() => {
      saveSuccess.value = false
    }, 3000)
  } catch {
    error.value = 'Failed to save settings'
  } finally {
    isSaving.value = false
  }
}

onMounted(() => {
  fetchSettings()
})
</script>

<style scoped>
.settings-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 2rem 1rem;
}

.page-container {
  max-width: 50rem;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2.5rem;
}

.page-title {
  font-size: 2rem;
  font-weight: 500;
  color: #212121;
  margin: 0 0 0.5rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.page-subtitle {
  font-size: 1rem;
  color: #757575;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.loading-state,
.error-state {
  padding: 3rem;
  text-align: center;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.error-state {
  color: #f44336;
}

.settings-group {
  margin-bottom: 2rem;
}

.group-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #212121;
  margin: 0 0 1rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.settings-cards {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 0.25rem 0;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.setting-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
  border-bottom: 0.0625rem solid #f0f0f0;
  gap: 1rem;
}

.setting-item:last-child {
  border-bottom: none;
}

.setting-label {
  font-size: 0.9375rem;
  color: #212121;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  flex: 1;
}

.setting-control {
  flex-shrink: 0;
}

.text-input {
  border: 0.0625rem solid #e0e0e0;
  border-radius: 0.5rem;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: #212121;
  min-width: 12rem;
  outline: none;
  transition: border-color 0.2s;
}

.text-input:focus {
  border-color: #204ef6;
}

.toggle-wrapper {
  position: relative;
  display: inline-flex;
  cursor: pointer;
}

.toggle-input {
  opacity: 0;
  width: 0;
  height: 0;
  position: absolute;
}

.toggle-slider {
  display: block;
  width: 3rem;
  height: 1.5rem;
  background-color: #e0e0e0;
  border-radius: 1.5rem;
  transition: background-color 0.2s;
  position: relative;
}

.toggle-slider::after {
  content: '';
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.125rem;
  height: 1.125rem;
  background-color: #ffffff;
  border-radius: 50%;
  transition: transform 0.2s;
}

.toggle-input:checked + .toggle-slider {
  background-color: #204ef6;
}

.toggle-input:checked + .toggle-slider::after {
  transform: translateX(1.5rem);
}

.save-section {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-top: 2rem;
}

.save-button {
  padding: 0.875rem 2rem;
  background-color: #204ef6;
  color: #ffffff;
  border: none;
  border-radius: 0.5rem;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.save-button:hover:not(:disabled) {
  background-color: #1a3dd4;
}

.save-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.save-success {
  font-size: 0.9375rem;
  color: #4caf50;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
</style>
