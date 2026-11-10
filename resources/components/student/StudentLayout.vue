<script setup>
import StudentNavbar from './StudentNavbar.vue'
import StudentFooter from './StudentFooter.vue'
</script>

<template>
  <div class="student-theme min-h-screen bg-[#F4F6F8] text-[#20384D]">
    <StudentNavbar />
    <slot />
    <StudentFooter />
  </div>
</template>
