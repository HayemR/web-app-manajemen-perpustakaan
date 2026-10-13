import { createRouter, createWebHistory } from 'vue-router'

import Home from '../../pages/student/home.vue'
import MyLibrary from '../../pages/student/my-library.vue'
import BookDetail from '../../pages/student/book-detail.vue'
import Bookmarks from '../../pages/student/bookmarks.vue'
import Scan from '../../pages/student/scan.vue'
import Account from '../../pages/student/account.vue'


const routes = [
  {
    path: '/vue',
    name: 'home',
    component: Home,
  },
  {
    path: '/my-library',
    name: 'my-library',
    component: MyLibrary,
  },
  { path: '/book/:id', 
    name: 'book-detail', 
    component: BookDetail 
},
  {
    path: '/bookmarks',
    name: 'bookmarks',
    component: Bookmarks,
  },
  {
    path: '/scan',
    name: 'scan',
    component: Scan,
  },
  {
    path: '/account',
    name: 'account',
    component: Account,
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,

 scrollBehavior(to, from, savedPosition) {
  return {
    top: 0,
    left: 0,
  }
}
})

export default router