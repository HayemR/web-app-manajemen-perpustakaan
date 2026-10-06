import { createRouter, createWebHistory } from 'vue-router'

import Home from '../../pages/student/home.vue'
import MyLibrary from '../../pages/student/my-library.vue'

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
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router