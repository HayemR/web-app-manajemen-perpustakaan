<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useRoute, RouterLink } from 'vue-router'

const route = useRoute()

const navItems = [
  { name: 'Home', path: '/vue', icon: 'home' },
  { name: 'My Library', path: '/my-library', icon: 'local_library' },
  { name: 'Bookmarks', path: '/bookmarks', icon: 'bookmark' },
  { name: 'Scan', path: '/scan', icon: 'qr_code_scanner' },
  { name: 'Account', path: '/account', icon: 'person' },
]

function isActive(path) {
  return route.path === path
}

// NOTIFICATION

const isNotificationOpen = ref(false)
const notificationContainer = ref(null)

const notifications = ref([
  {
    id: 1,
    title: 'Pengingat pengembalian buku',
    message:
      'Jangan lupa memeriksa batas waktu pengembalian buku pinjamanmu.',
    time: '10 menit yang lalu',
    icon: 'menu_book',
    read: false,
    path: '/my-library',
  },
  {
    id: 2,
    title: 'Peminjaman berhasil',
    message: 'Status peminjaman bukumu berhasil diperbarui.',
    time: '1 jam yang lalu',
    icon: 'check_circle',
    read: false,
    path: '/my-library',
  },
  {
    id: 3,
    title: 'Koleksi buku baru',
    message: 'Jelajahi koleksi buku terbaru di perpustakaan.',
    time: 'Kemarin',
    icon: 'auto_stories',
    read: true,
    path: '/my-library',
  },
])

const unreadCount = computed(() =>
  notifications.value.filter((item) => !item.read).length
)

function toggleNotifications() {
  isNotificationOpen.value = !isNotificationOpen.value
}

function markAsRead(notification) {
  notification.read = true
}

function markAllAsRead() {
  notifications.value.forEach((item) => {
    item.read = true
  })
}

function handleOutsideClick(event) {
  if (
    notificationContainer.value &&
    !notificationContainer.value.contains(event.target)
  ) {
    isNotificationOpen.value = false
  }
}

function handleEscape(event) {
  if (event.key === 'Escape') {
    isNotificationOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleOutsideClick)
  document.addEventListener('keydown', handleEscape)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleOutsideClick)
  document.removeEventListener('keydown', handleEscape)
})

const searchInput = ref(null)

function handleSearchShortcut(event) {
  if (
    (event.ctrlKey || event.metaKey) &&
    event.key.toLowerCase() === 'k'
  ) {
    event.preventDefault()
    searchInput.value?.focus()
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleSearchShortcut)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleSearchShortcut)
})

const isProfileOpen = ref(false)
const profileContainer = ref(null)

const profile = {
  name: 'Adel',
  role: 'Siswa',
  email: 'adel@example.com',
  initials: 'A',
}

function toggleProfile() {
  isProfileOpen.value = !isProfileOpen.value
}

function closeProfile() {
  isProfileOpen.value = false
}

function handleProfileOutsideClick(event) {
  if (
    profileContainer.value &&
    !profileContainer.value.contains(event.target)
  ) {
    closeProfile()
  }
}

function handleProfileEscape(event) {
  if (event.key === 'Escape') {
    closeProfile()
  }
}

onMounted(() => {
  document.addEventListener('click', handleProfileOutsideClick)
  document.addEventListener('keydown', handleProfileEscape)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleProfileOutsideClick)
  document.removeEventListener('keydown', handleProfileEscape)
})
</script>

<template>
  <header
    class="fixed left-0 right-0 top-0 z-50 bg-white/90 shadow-[0_4px_18px_rgba(18,65,112,0.05)] backdrop-blur-md"
  >
    <div
      class="mx-auto flex h-20 max-w-[1400px] items-center justify-between gap-4 px-4 md:px-5"
    >
      <!-- LOGO -->
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

            <span class="text-[11px] font-semibold text-[#6F8B95]">
              School Library
            </span>
          </div>
        </RouterLink>

        <!-- MENU -->
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

      <!-- RIGHT SIDE -->
      <div class="flex shrink-0 items-center gap-2 md:gap-3">
        <!-- SEARCH -->
        <div
          class="hidden h-10 w-64 items-center gap-2 rounded-xl border border-[#CFE5D8] bg-white px-3 lg:flex"
        >
          <span
            class="material-symbols-outlined shrink-0 text-[20px] text-[#6F8B95]"
          >
            search
          </span>

          <input
            ref="searchInput"
            type="text"
            placeholder="Search books..."
            class="min-w-0 flex-1 bg-transparent text-sm text-[#124170] outline-none placeholder:text-[#9AAEB5]"
          />

          <kbd
            class="shrink-0 whitespace-nowrap rounded-md border border-[#D7E8DE] bg-[#F4F8F6] px-2 py-1 text-[10px] font-semibold leading-none text-[#6F8B95]"
          >
            Ctrl K
          </kbd>
        </div>

        <!-- NOTIFICATION -->
        <div
          ref="notificationContainer"
          class="relative"
        >
          <!-- Tombol lonceng -->
          <button
            type="button"
            aria-label="Buka notifikasi"
            aria-controls="notification-panel"
            :aria-expanded="isNotificationOpen"
            @click="toggleNotifications"
            class="relative flex h-10 w-10 items-center justify-center rounded-xl text-[#6F8B95] transition hover:bg-[#E2F9EC] hover:text-[#124170]"
          >
            <span class="material-symbols-outlined text-[21px]">
              notifications
            </span>

            <!-- Badge jumlah belum dibaca -->
            <span
              v-if="unreadCount > 0"
              class="absolute right-1 top-1 flex h-[17px] min-w-[17px] items-center justify-center rounded-full border-2 border-white bg-[#67C090] px-1 text-[9px] font-bold text-[#124170]"
            >
              {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
          </button>

          <!-- DROPDOWN PANEL -->
          <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-2 scale-[0.98] opacity-0"
            enter-to-class="translate-y-0 scale-100 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 scale-100 opacity-100"
            leave-to-class="translate-y-2 scale-[0.98] opacity-0"
          >
            <section
              v-if="isNotificationOpen"
              id="notification-panel"
              aria-label="Daftar notifikasi"
              class="absolute right-0 top-full z-[60] mt-3 w-[min(380px,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-[#D7E8DE] bg-white shadow-[0_12px_40px_rgba(18,65,112,0.16)]"
            >
              <!-- HEADER -->
              <div
                class="flex items-center justify-between gap-3 border-b border-[#E4EEE8] px-4 py-4"
              >
                <div>
                  <h3 class="text-base font-bold text-[#124170]">
                    Notifikasi
                  </h3>

                  <p class="mt-1 text-xs text-[#6F8B95]">
                    {{ unreadCount }} belum dibaca
                  </p>
                </div>

                <button
                  v-if="unreadCount > 0"
                  type="button"
                  class="relative z-10 cursor-pointer rounded-md px-2 py-1 text-right text-xs font-semibold text-[#26667F] transition hover:bg-[#E2F9EC] hover:text-[#124170]"
                  @click.stop="markAllAsRead"
                >
                  Tandai semua dibaca
                </button>
              </div>

              <!-- DAFTAR NOTIFIKASI -->
              <div class="max-h-[min(420px,60vh)] overflow-y-auto">
                <!-- Tampilan jika kosong -->
                <div
                  v-if="notifications.length === 0"
                  class="px-5 py-10 text-center"
                >
                  <span
                    class="material-symbols-outlined text-4xl text-[#9AAEB5]"
                  >
                    notifications_off
                  </span>

                  <p class="mt-2 text-sm font-semibold text-[#124170]">
                    Belum ada notifikasi
                  </p>

                  <p class="mt-1 text-xs text-[#6F8B95]">
                    Notifikasi terbaru akan muncul di sini.
                  </p>
                </div>

                <!-- Item notifikasi -->
                <RouterLink
                  v-for="item in notifications"
                  :key="item.id"
                  :to="item.path"
                  @click="markAsRead(item); isNotificationOpen = false"
                  class="flex items-start gap-3 border-b border-[#EEF4F0] px-4 py-4 transition hover:bg-[#F4F8F6]"
                  :class="
                    !item.read ? 'bg-[#F4F8F6]' : 'bg-white'
                  "
                >
                  <!-- Ikon -->
                  <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E2F9EC]"
                  >
                    <span
                      class="material-symbols-outlined text-[21px] text-[#26667F]"
                    >
                      {{ item.icon }}
                    </span>
                  </div>

                  <!-- Isi -->
                  <div class="min-w-0 flex-1">
                    <div class="flex items-start gap-2">
                      <p
                        class="flex-1 text-sm font-semibold text-[#124170]"
                      >
                        {{ item.title }}
                      </p>

                      <span
                        v-if="!item.read"
                        class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#67C090]"
                      ></span>
                    </div>

                    <p
                      class="mt-1 text-xs leading-relaxed text-[#6F8B95]"
                    >
                      {{ item.message }}
                    </p>

                    <p
                      class="mt-2 text-[10px] font-medium text-[#8AA0A8]"
                    >
                      {{ item.time }}
                    </p>
                  </div>
                </RouterLink>
              </div>

              <!-- FOOTER -->
              <div
                class="border-t border-[#E4EEE8] bg-white px-4 py-3"
              >
                <p class="text-center text-xs text-[#6F8B95]">
                  Menampilkan {{ notifications.length }} notifikasi
                </p>
              </div>
            </section>
          </Transition>
        </div>

        <div ref="profileContainer" class="relative">
          <!-- TOMBOL PROFIL -->
          <button
            type="button"
            @click.stop="toggleProfile"
            :aria-expanded="isProfileOpen"
            aria-haspopup="true"
            class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-[#E2F9EC]"
          >
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-[#DDF4E7] font-bold text-[#124170]"
            >
              {{ profile.initials }}
            </div>

            <div class="hidden text-left sm:block">
              <p class="text-sm font-semibold text-[#124170]">
                {{ profile.name }}
              </p>
              <p class="text-xs text-[#6F8B95]">
                {{ profile.role }}
              </p>
            </div>

            <span
              class="material-symbols-outlined text-[#6F8B95] transition"
              :class="{ 'rotate-180': isProfileOpen }"
            >
              expand_more
            </span>
          </button>

          <!-- DROPDOWN PROFIL -->
          <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-2 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-2 opacity-0"
          >
            <div
              v-if="isProfileOpen"
              class="absolute right-0 top-full z-50 mt-3 w-72 overflow-hidden rounded-2xl border border-[#DDF4E7] bg-white shadow-xl"
            >
              <!-- HEADER PROFIL -->
              <div class="bg-[#F4F8F6] p-5">
                <div class="flex items-center gap-3">
                  <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#DDF4E7] text-lg font-bold text-[#124170]"
                  >
                    {{ profile.initials }}
                  </div>

                  <div class="min-w-0">
                    <p class="truncate font-bold text-[#124170]">
                      {{ profile.name }}
                    </p>
                    <p class="text-sm text-[#6F8B95]">
                      {{ profile.role }}
                    </p>
                    <p class="truncate text-xs text-[#6F8B95]">
                      {{ profile.email }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- AKSI PROFIL -->
              <div class="p-3">
                <RouterLink
                  to="/account"
                  @click="closeProfile"
                  class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold text-[#124170] transition hover:bg-[#E2F9EC]"
                >
                  <span class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">
                      manage_accounts
                    </span>
                    Lihat profil lengkap
                  </span>

                  <span class="material-symbols-outlined text-[18px]">
                    arrow_forward
                  </span>
                </RouterLink>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </header>
</template>
