import { createRouter, createWebHistory } from 'vue-router'

import StudentRoutes from './index'

import AdminLogin from '../../pages/admin/login.vue'
import AdminDashboard from '../../pages/admin/dashboard.vue'
import AdminAddBook from '../../pages/admin/add-book.vue'
import AdminBorrowApproval from '../../pages/admin/borrow-approval.vue'
import AdminReturnBook from '../../pages/admin/return-book.vue'
import AdminBorrowers from '../../pages/admin/borrowers.vue'

const routes = [
  // STUDENT
  ...StudentRoutes,

  // ADMIN
  {
    path: '/admin/login',
    name: 'admin-login',
    component: AdminLogin,
    meta: {
      title: 'Admin Login - DigiNesh',
    },
  },
  {
    path: '/admin/dashboard',
    name: 'admin-dashboard',
    component: AdminDashboard,
    meta: {
      title: 'Dashboard Admin - DigiNesh',
    },
  },
  {
    path: '/admin/add-book',
    name: 'admin-add-book',
    component: AdminAddBook,
    meta: {
      title: 'Add-Book Admin - DigiNesh',
    },
  },

  {
    path: '/admin/borrow-approval',
    name: 'admin-borrow-approval',
    component: AdminBorrowApproval,
    meta: {
      title: 'Borrow-Approval Admin - DigiNesh',
    },
  },
  {
    path: '/admin/return-book',
    name: 'admin-return-book',
    component: AdminReturnBook,
    meta: {
      title: 'Return-Book Admin - DigiNesh',
    },
  },
  {
    path: '/admin/borrowers',
    name: 'admin-borrowers',
    component: AdminBorrowers,
    meta: {
      title: 'Borrowers Admin - DigiNesh',
    },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return {
      top: 0,
      left: 0,
    }
  },
})


router.afterEach((to) => {
  document.title = to.meta.title || 'DigiNesh'
})

router.beforeEach(async (to) => {
  const requiresStudentAuth = to.matched.some(
    (route) => route.meta.requiresStudentAuth,
  )
  const isStudentLogin = to.name === 'student-login'

  if (!requiresStudentAuth && !isStudentLogin) {
    return true
  }

  try {
    const response = await fetch('/student/session', {
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
      },
    })

    if (response.status === 401) {
      return requiresStudentAuth ? { name: 'student-login' } : true
    }

    if (!response.ok) {
      throw new Error(`Session check failed with status ${response.status}.`)
    }

    return isStudentLogin ? { name: 'student-home' } : true
  } catch (error) {
    console.error('Unable to verify the student session.', error)

    return false
  }
})

export default router