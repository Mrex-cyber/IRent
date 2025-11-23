import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    redirect: '/menu'
  },
  {
    path: '/auth',
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
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('@/pages/dashboard/DashboardPage.vue')
  },
  {
    path: '/apartments',
    name: 'Apartments',
    component: () => import('@/pages/apartments/ApartmentsPage.vue')
  },
  {
    path: '/apartments/:id',
    name: 'ApartmentDetails',
    component: () => import('@/pages/apartments/ApartmentDetailsPage.vue')
  },
  {
    path: '/chat',
    name: 'Chat',
    component: () => import('@/pages/chat/ChatPage.vue')
  },
  {
    path: '/analytics',
    name: 'Analytics',
    component: () => import('@/pages/analytics/AnalyticsPage.vue')
  },
  {
    path: '/news',
    name: 'News',
    component: () => import('@/pages/news/NewsPage.vue')
  },
  {
    path: '/tasks',
    name: 'Tasks',
    component: () => import('@/pages/tasks/TasksPage.vue')
  },
  {
    path: '/payments',
    name: 'Payments',
    component: () => import('@/pages/payments/PaymentsPage.vue')
  },
  {
    path: '/menu',
    name: 'Menu',
    component: () => import('@/pages/menu/MenuPage.vue')
  },
  {
    path: '/members',
    name: 'Members',
    component: () => import('@/pages/members/MembersPage.vue')
  },
  {
    path: '/activities',
    name: 'Activities',
    component: () => import('@/pages/activities/ActivitiesPage.vue')
  },
  {
    path: '/settings',
    name: 'Settings',
    component: () => import('@/pages/settings/SettingsPage.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
