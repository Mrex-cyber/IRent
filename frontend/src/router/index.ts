import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    redirect: '/management/news'
  },
  {
    path: '/auth',
    meta: { requiresGuest: true },
    component: () => import('@/layouts/AuthLayout.vue'),
    children: [
      {
        path: 'login',
        name: 'Login',
        component: () => import('@/pages/auth/LoginPage.vue')
      },
      {
        path: 'register',
        name: 'Register',
        component: () => import('@/pages/auth/RegisterPage.vue')
      }
    ]
  },
  {
    path: '/management',
    meta: { requiresAuth: true },
    component: () => import('@/layouts/ManagementLayout.vue'),
    redirect: '/management/news',
    children: [
      {
        path: 'news',
        name: 'ManagementNews',
        component: () => import('@/pages/management/NewsAnnouncementsPage.vue')
      },
      {
        path: 'requests',
        name: 'ManagementRequests',
        component: () => import('@/pages/management/MessagesPage.vue')
      },
      {
        path: 'tasks',
        name: 'ManagementTasks',
        component: () => import('@/pages/management/TaskLoadOverviewPage.vue')
      },
      {
        path: 'buildings',
        name: 'ManagementBuildings',
        component: () => import('@/pages/management/BuildingsApartmentsPage.vue')
      },
      {
        path: 'profile',
        name: 'UserProfile',
        component: () => import('@/pages/management/UserProfilePage.vue')
      },
      {
        path: 'analytics',
        name: 'ManagementAnalytics',
        component: () => import('@/pages/analytics/AnalyticsPage.vue')
      },
      {
        path: 'members',
        name: 'ManagementMembers',
        component: () => import('@/pages/members/MembersPage.vue')
      },
      {
        path: 'settings',
        name: 'ManagementSettings',
        component: () => import('@/pages/settings/SettingsPage.vue')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (authStore.token && !authStore.user) {
    await authStore.fetchUser()
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next({ name: 'Login', query: { redirect: to.fullPath } })
  }

  if (to.meta.requiresGuest && authStore.isAuthenticated) {
    return next({ path: '/management/news' })
  }

  next()
})

export default router
