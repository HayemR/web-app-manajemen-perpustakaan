import Home from '../../pages/student/home.vue'
import MyLibrary from '../../pages/student/my-library.vue'
import BookDetail from '../../pages/student/book-detail.vue'
import Bookmarks from '../../pages/student/bookmarks.vue'
import Scan from '../../pages/student/scan.vue'
import Account from '../../pages/student/account.vue'
import LoginPage from '../../pages/student/login-page.vue'

const StudentRoutes = [
  {
    path: '/home',
    name: 'student-home',
    component: Home,
    meta: {
      title: 'Student Home - DigiNesh',
      requiresStudentAuth: true,
    },
  },
  {
    path: '/vue',
    redirect: { name: 'student-home' },
  },

  {
    path: '/my-library',
    name: 'my-library',
    component: MyLibrary,
    meta: {
      title: 'Student Library - DigiNesh',
      requiresStudentAuth: true,
    },
  },

  { 
    path: '/book/:id', 
    name: 'book-detail', 
    component: BookDetail,
    meta: {
      title: 'Student Book Detail - DigiNesh',
      requiresStudentAuth: true,
    },
},

  {
    path: '/bookmarks',
    name: 'bookmarks',
    component: Bookmarks,
     meta: {
      title: 'Student Bookmarks - DigiNesh',
      requiresStudentAuth: true,
    },
  },

  {
    path: '/scan',
    name: 'scan',
    component: Scan,
     meta: {
      title: 'Student Scan - DigiNesh',
      requiresStudentAuth: true,
    },
  },

  {
    path: '/account',
    name: 'account',
    component: Account,
     meta: {
      title: 'Student Account - DigiNesh',
      requiresStudentAuth: true,
    },
  },
  {
    path: '/login',
    name: 'student-login',
    component: LoginPage,
    meta: {
      title: 'Masuk - DigiNesh',
    },
  },
]

export default StudentRoutes