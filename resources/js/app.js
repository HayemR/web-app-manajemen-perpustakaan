import '../css/app.css'

import { createApp } from 'vue'
import App from './App.vue'

import router from './router/route'

createApp(App)
  .use(router)
  .mount('#app')