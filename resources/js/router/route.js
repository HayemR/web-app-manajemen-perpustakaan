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
      title: 'Login Admin - DigiNesh',
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


export default router