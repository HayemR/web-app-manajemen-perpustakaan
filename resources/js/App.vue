<template>
  <!-- Admin Login -->
  <AdminLogin v-if="path === '/admin/login'" />

  <!-- Admin Dashboard -->
  <AdminDashboard v-else-if="path === '/admin/dashboard'" />

  <!-- Student Home -->
  <StudentHome v-else-if="path === '/student/home'" />

  <!-- Default -->
  <StudentHome v-else />
</template>

<script setup>
import AdminLogin from '../pages/admin/login.vue'
import AdminDashboard from '../pages/admin/dashboard.vue'
import StudentHome from '../pages/student/home.vue'

const path = window.location.pathname
</script>