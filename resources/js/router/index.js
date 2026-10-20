import Home from '../../pages/student/home.vue'
import MyLibrary from '../../pages/student/my-library.vue'
import BookDetail from '../../pages/student/book-detail.vue'
import Bookmarks from '../../pages/student/bookmarks.vue'
import Scan from '../../pages/student/scan.vue'
import Account from '../../pages/student/account.vue'


const StudentRoutes = [
  {
    path: '/vue',
    name: 'student-home',
    component: Home,
    meta: {
      title: 'Student Home - DigiNesh',
    },
  },

  {
    path: '/my-library',
    name: 'my-library',
    component: MyLibrary,
    meta: {
      title: 'Student Library - DigiNesh',
    },
  },

  { 
    path: '/book/:id', 
    name: 'book-detail', 
    component: BookDetail,
    meta: {
      title: 'Student Book Detail - DigiNesh',
    },
},

  {
    path: '/bookmarks',
    name: 'bookmarks',
    component: Bookmarks,
     meta: {
      title: 'Student Bookmarks - DigiNesh',
    },
  },

  {
    path: '/scan',
    name: 'scan',
    component: Scan,
     meta: {
      title: 'Student Scan - DigiNesh',
    },
  },

  {
    path: '/account',
    name: 'account',
    component: Account,
     meta: {
      title: 'Student Account - DigiNesh',
    },
  }
]

export default StudentRoutes