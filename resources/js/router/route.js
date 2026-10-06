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
    path: '/admin',
    redirect: '/admin/dashboard',
  },
  {
    path: '/admin/login',
    name: 'admin-login',
    component: AdminLogin,
  },
  {
    path: '/admin/dashboard',
    name: 'admin-dashboard',
    component: AdminDashboard,
  },
  {
    path: '/admin/add-book',
    name: 'admin-add-book',
    component: AdminAddBook,
  },

  {
    path: '/admin/borrow-approval',
    name: 'admin-borrow-approval',
    component: AdminBorrowApproval,
  },
  {
    path: '/admin/return-book',
    name: 'admin-return-book',
    component: AdminReturnBook,
  },
  {
    path: '/admin/borrowers',
    name: 'admin-borrowers',
    component: AdminBorrowers,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router