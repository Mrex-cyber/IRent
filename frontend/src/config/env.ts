const apiBase = import.meta.env.VITE_API_BASE_URL ?? ''

export const apiBaseUrl = apiBase ? `${apiBase.replace(/\/$/, '')}/api` : '/api'
