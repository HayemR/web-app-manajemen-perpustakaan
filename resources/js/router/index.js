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

export default StudentRoutes