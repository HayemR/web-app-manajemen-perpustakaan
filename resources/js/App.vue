<script setup>
import { ref } from 'vue'
const count = ref(0)
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center gap-4 bg-slate-100">
    <h1 class="text-3xl font-bold text-slate-800">Halo Vue + Tailwind</h1>
    <button
      @click="count++"
      class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
    >
      Diklik {{ count }} kali
    </button>
  </div>
</template>