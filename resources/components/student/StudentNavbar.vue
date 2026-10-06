<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()

const navItems = [
  {
    name: 'Home',
    path: '/vue',
    icon: 'home',
  },
  {
    name: 'My Library',
    path: '/my-library',
    icon: 'local_library',
  },
  {
    name: 'Bookmarks',
    path: '#',
    icon: 'bookmark',
  },
  {
    name: 'Scan',
    path: '#',
    icon: 'qr_code_scanner',
  },
  {
    name: 'Account',
    path: '#',
    icon: 'person',
  },
]

function isActive(path) {
  return route.path === path
}
</script>

<template>
  <header
    class="fixed left-0 right-0 top-0 z-50 bg-white/90 shadow-[0_4px_18px_rgba(18,65,112,0.05)] backdrop-blur-md"
  >
    <div
      class="mx-auto flex h-20 max-w-[1400px] items-center justify-between gap-4 px-4 md:px-5"
    >
      <!-- Logo -->
      <div class="flex min-w-0 items-center gap-5">
        <RouterLink
          to="/vue"
          class="flex shrink-0 items-center gap-2"
        >
          <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#26667F] text-white shadow-sm"
          >
            <span class="material-symbols-outlined">
              local_library
            </span>
          </div>

          <div class="hidden flex-col sm:flex">
            <span
              class="text-[16px] font-semibold tracking-tight text-[#124170]"
            >
              Diginesh
            </span>

            <span
              class="text-[11px] font-semibold text-[#6F8B95]"
            >
              School Library
            </span>
          </div>
        </RouterLink>

        <!-- Navigation -->
        <nav class="hidden items-center gap-1 lg:flex">
          <RouterLink
            v-for="item in navItems"
            :key="item.name"
            :to="item.path"
            class="flex items-center gap-1.5 rounded-xl px-3 py-2 text-[14px] font-semibold transition-colors"
            :class="
              isActive(item.path)
                ? 'bg-[#E2F9EC] text-[#124170] shadow-sm'
                : 'text-[#6F8B95] hover:bg-[#E2F9EC] hover:text-[#124170]'
            "
          >
            <span class="material-symbols-outlined text-[18px]">
              {{ item.icon }}
            </span>

            {{ item.name }}
          </RouterLink>
        </nav>
      </div>

      <!-- Right Side -->
      <div class="flex shrink-0 items-center gap-2 md:gap-3">
        <!-- Search -->
        <div
          class="hidden h-10 w-56 items-center rounded-xl bg-white px-3 shadow-[0_2px_8px_rgba(18,65,112,0.04)] md:flex lg:w-64"
        >
          <span
            class="material-symbols-outlined mr-2 text-[#26667F]"
          >
            search
          </span>

          <input
            type="text"
            placeholder="Search titles, authors..."
            class="w-full bg-transparent text-sm outline-none placeholder:text-[#6F8B95]"
          />

          <kbd
            class="rounded bg-[#E2F9EC] px-1.5 py-0.5 text-[11px] font-semibold text-[#6F8B95]"
          >
            ⌘K
          </kbd>
        </div>

        <!-- Notification -->
        <button
          class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#26667F] shadow-[0_2px_8px_rgba(18,65,112,0.04)] transition-colors hover:bg-[#E2F9EC]"
        >
          <span class="material-symbols-outlined">
            notifications
          </span>

          <span
            class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#67C090]"
          />
        </button>

        <!-- Profile -->
        <div
          class="flex items-center gap-2 rounded-xl bg-[#F4FBF7] px-2.5 py-1.5"
        >
          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-[#26667F] text-xs font-bold text-white"
          >
            AD
          </div>

          <div class="hidden min-w-0 flex-col sm:flex">
            <span
              class="whitespace-nowrap text-[13px] font-semibold text-[#124170]"
            >
              Adel
            </span>

            <span
              class="mt-0.5 flex items-center gap-1 whitespace-nowrap text-[11px] font-semibold text-[#006C45]"
            >
              <span
                class="h-1.5 w-1.5 rounded-full bg-[#67C090]"
              />
              Student • Active
            </span>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>