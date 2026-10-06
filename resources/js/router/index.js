import Home from '../../pages/student/home.vue'
import MyLibrary from '../../pages/student/my-library.vue'

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
]

export default StudentRoutes