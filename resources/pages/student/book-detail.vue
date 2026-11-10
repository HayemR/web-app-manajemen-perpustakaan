<script setup>
import { onBeforeUnmount, ref } from 'vue'
import { useRouter } from 'vue-router'
import StudentLayout from '../../components/student/StudentLayout.vue'

const router = useRouter()

const bookmarked = ref(false)
const showMap = ref(false)
const toastMessage = ref('')
const toastTitle = ref('')
const showToast = ref(false)
let toastTimer

function triggerToast(title, message) {
  toastTitle.value = title
  toastMessage.value = message
  showToast.value = true

  clearTimeout(toastTimer)
  toastTimer = setTimeout(() => {
    showToast.value = false
  }, 4000)
}

function borrowBook() {
  triggerToast(
    'Peminjaman di Perpustakaan',
    'Silakan datang ke perpustakaan dan gunakan kartu siswa untuk melakukan peminjaman.'
  )
}

function toggleBookmark() {
  bookmarked.value = !bookmarked.value

  if (bookmarked.value) {
    triggerToast(
      'Buku Disimpan!',
      'Cosmos: A Personal Voyage telah ditambahkan ke bookmarkmu.'
    )
  } else {
    triggerToast(
      'Bookmark Dihapus',
      'Cosmos: A Personal Voyage telah dihapus dari bookmarkmu.'
    )
  }
}

async function shareBook() {
  if (navigator.clipboard) {
    try {
      await navigator.clipboard.writeText(window.location.href)
      triggerToast(
        'Tautan Disalin!',
        'Tautan detail buku telah disalin ke clipboard.'
      )
    } catch {
      triggerToast(
        'Referensi Buku',
        'Referensi katalog SCI-204-SAG siap dibagikan.'
      )
    }
  } else {
    triggerToast(
      'Referensi Buku',
      'Referensi katalog SCI-204-SAG siap dibagikan.'
    )
  }
}

function goBack() {
  if (window.history.length > 1) {
    router.back()
  } else {
    router.push('/home')
  }
}

onBeforeUnmount(() => {
  clearTimeout(toastTimer)
})
</script>

<template>
  <StudentLayout>

    <!-- Toast -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="translate-y-24 opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-300 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="translate-y-24 opacity-0"
    >
      <div
        v-if="showToast"
        class="fixed bottom-6 right-6 z-[60] flex max-w-md items-center gap-3 rounded-xl border border-[#D4DEE5] bg-white p-3 shadow-xl"
      >
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#EDF3F5] text-[#006C45]">
          <span class="material-symbols-outlined text-xl">check_circle</span>
        </div>
        <div class="flex flex-col">
          <span class="text-sm font-semibold text-[#20384D]">{{ toastTitle }}</span>
          <span class="text-xs leading-5 text-[#637587]">{{ toastMessage }}</span>
        </div>
      </div>
    </Transition>

    <!-- Main -->
    <main class="min-h-screen w-full bg-[#F4F6F8] pt-20">
      <div class="mx-auto flex w-full max-w-[1200px] flex-col gap-6 px-4 py-6 md:px-5">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
          <button
            type="button"
            class="inline-flex self-start items-center gap-1 rounded-xl bg-white/80 px-3 py-2 text-xs font-semibold text-[#315D78] shadow-sm transition-all hover:bg-white hover:text-[#20384D] sm:self-auto"
            @click="goBack"
          >
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Kembali
          </button>
        </nav>

        <!-- Main Two Column -->
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
          <!-- Left Column -->
            <aside class="flex w-full flex-col gap-5 lg:col-span-4">
            <!-- Book Cover -->
                <div class="group relative flex flex-col items-center overflow-hidden rounded-xl bg-white p-5 shadow-sm">
                <div class="relative aspect-[3/4.4] w-full max-w-[280px] overflow-hidden rounded-lg shadow-2xl transition-transform duration-300 group-hover:scale-[1.02]">
                    <img
                    alt="Sampul buku Cosmos: A Personal Voyage karya Carl Sagan"
                    class="h-full w-full object-cover object-center"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAkZxldLXYoeS1vHjSSESBAhdgBKOVAhYdQ8C_LCWFRDIr-zxX3AYII0KjpV41TzggU9MK_daauNEWjS6x0l1cqnhpqVrOAwslqRcxVwJs3csRccL2TnVE2fGM7v_G-XrgpNX4qlyhcI7PLUHdZsu13dZF-z_PU7VEkyRI6f7tp0LmVdMcgLsnz_533uJT5OzYX5Fp6LGy2dnrv9Xt7KkpbIARKa_yZPYDrqmiqRf14bZW0ugETB66LNg"
                    />
                </div>

                <!-- Location -->
                <div class="mt-5 flex w-full items-center justify-between gap-2 rounded-xl bg-[#EAF0F4] p-3">
                    <div class="flex items-center gap-2 text-[#20384D]">
                    <span class="material-symbols-outlined text-lg text-[#006C45]">location_on</span>
                    <div class="flex flex-col">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-[#637587]">
                        Lokasi Fisik
                        </span>
                        <span class="text-xs font-semibold text-[#20384D]">
                        Sayap B • Bagian 4 • Rak 12
                        </span>
                    </div>
                    </div>

                    <button
                    type="button"
                    title="Lihat lokasi rak"
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-[#315D78] shadow-sm transition-colors hover:bg-[#DFE8ED] hover:text-[#20384D]"
                    @click="showMap = !showMap"
                    >
                    <span class="material-symbols-outlined text-lg">map</span>
                    </button>
                </div>

                <!-- Copies & Format -->
                <div class="mt-2 grid w-full grid-cols-2 gap-2 text-center">
                    <div class="flex flex-col items-center justify-center rounded-lg bg-white p-2 shadow-sm">
                    <span class="text-xl font-semibold text-[#20384D]">3 dari 4</span>
                    <span class="text-[11px] font-semibold text-[#006C45]">Eksemplar Tersedia</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-lg bg-white p-2 shadow-sm">
                    <span class="text-sm font-semibold text-[#20384D]">Hardcover</span>
                    <span class="text-[11px] text-[#637587]">384 Halaman</span>
                    </div>
                </div>
                </div>

            <!-- Quick Actions -->
            <div class="flex flex-col gap-3 rounded-xl bg-white p-5 shadow-sm">
              <button
                type="button"
                class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#315D78] text-sm font-semibold text-white shadow-md transition-all hover:bg-[#20384D] active:scale-[0.98]"
                @click="borrowBook"
              >
                <span class="material-symbols-outlined text-lg">menu_book</span>
                Pinjam Buku
                <span class="material-symbols-outlined text-base">arrow_forward</span>
              </button>
                <button
                  type="button"
                  class="flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-white text-xs font-semibold text-[#315D78] shadow-sm transition-colors hover:bg-[#EAF0F4] active:scale-[0.98]"
                  :class="bookmarked ? 'bg-[#EAF0F4] text-[#006C45]' : ''"
                  @click="toggleBookmark"
                >
                  <span class="material-symbols-outlined text-base">
                    {{ bookmarked ? 'bookmark' : 'bookmark_add' }}
                  </span>
                  {{ bookmarked ? 'Tersimpan' : 'Bookmark' }}
                </button>
            </div>

              <!-- Borrowing Notice -->
              <div class="flex items-start gap-2 rounded-xl bg-[#EDF3F5] p-3">
                <span class="material-symbols-outlined mt-0.5 shrink-0 text-lg text-[#006C45]">
                  check_circle
                </span>
                <div class="flex flex-col">
                  <span class="text-xs font-semibold leading-tight text-[#006C45]">
                    Peminjaman langsung di perpustakaan
                  </span>
                  <span class="mt-0.5 text-[11px] leading-5 text-[#20384D]">
                    Bawa kartu siswa dan lakukan scan kartu di perpustakaan untuk meminjam buku.
                  </span>
                </div>
              </div>

              <!-- Map Drawer -->
              <Transition
                enter-active-class="transition-all duration-200"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
              >
                <div
                  v-if="showMap"
                  class="flex flex-col gap-2 rounded-xl bg-[#F8FFFB] p-3"
                >
                  <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1 text-sm font-semibold text-[#20384D]">
                      <span class="material-symbols-outlined text-base text-[#315D78]">near_me</span>
                      Panduan Lokasi Perpustakaan
                    </span>
                    <button
                      type="button"
                      class="text-[#637587] hover:text-[#20384D]"
                      @click="showMap = false"
                    >
                      <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                  </div>

                  <div class="relative flex h-32 w-full flex-col items-center justify-center overflow-hidden rounded-lg bg-[#DFE8ED] p-2 text-center">
                    <div class="absolute inset-0 opacity-15">
                      <svg height="100%" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                          <pattern id="gridPattern" height="20" patternUnits="userSpaceOnUse" width="20">
                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="#315D78" stroke-width="1" />
                          </pattern>
                        </defs>
                        <rect fill="url(#gridPattern)" height="100%" width="100%" />
                      </svg>
                    </div>
                    <span class="material-symbols-outlined z-10 mb-1 animate-bounce text-3xl text-[#006C45]">
                      pin_drop
                    </span>
                    <span class="z-10 text-xs font-semibold text-[#20384D]">
                      Sayap B • Lantai 2
                    </span>
                    <span class="z-10 text-[11px] text-[#637587]">
                      Bagian 4 • Rak 12
                    </span>
                  </div>
                </div>
              </Transition>

              <!-- Catalog Metadata -->
              <div class="flex flex-col gap-2 border-t border-[#D4DEE5] pt-3">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-[#637587]">
                  Metadata Katalog
                </span>

                <div class="grid grid-cols-2 gap-x-4 gap-y-2 py-1">
                  <div class="flex flex-col">
                    <span class="text-[11px] text-[#637587]">Nomor Panggil</span>
                    <span class="font-mono text-xs font-medium text-[#20384D]">520.1 SAG</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-[11px] text-[#637587]">ID Katalog</span>
                    <span class="font-mono text-xs font-medium text-[#20384D]">SCI-204-SAG</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-[11px] text-[#637587]">ISBN-13</span>
                    <span class="font-mono text-xs text-[#20384D]">978-0345331359</span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-[11px] text-[#637587]">Bahasa</span>
                    <span class="text-xs text-[#20384D]">Bahasa Inggris</span>
                  </div>

                  <div class="col-span-2 flex flex-col">
                    <span class="text-[11px] text-[#637587]">Penerbit</span>
                    <span class="text-xs leading-5 text-[#20384D]">
                      Ballantine Books (Edisi 2013 / Edisi asli 1980)
                    </span>
                  </div>
                </div>
              </div>
          </aside>

          <!-- Right Column -->
          <div class="flex flex-col gap-6 lg:col-span-8">
            <!-- Book Information -->
            <section class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm md:p-6">
              <!-- Category & Status -->
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-1.5">
                  <span class="rounded-full bg-[#315D78] px-3 py-1 text-[11px] font-semibold text-white">
                    Sains & Antariksa
                  </span>
                  <span class="rounded-full bg-[#EAF0F4] px-3 py-1 text-[11px] font-semibold text-[#315D78]">
                    Astrofisika
                  </span>
                  <span class="rounded-full bg-[#EAF0F4] px-3 py-1 text-[11px] font-semibold text-[#315D78]">
                    Non-Fiksi
                  </span>
                  <span class="rounded-full bg-[#DFE8ED] px-3 py-1 text-[11px] font-semibold text-[#396192]">
                    Bacaan SMA
                  </span>
                </div>

                <div class="inline-flex items-center gap-1.5 rounded-full bg-[#EDF3F5] px-3 py-1.5 text-xs font-semibold text-[#006C45]">
                  <span class="h-2.5 w-2.5 animate-pulse rounded-full bg-[#67C090]"></span>
                  Tersedia (3 eksemplar)
                </div>
              </div>

              <!-- Title -->
              <div class="flex flex-col gap-1">
                <h1 class="text-[28px] font-bold leading-tight tracking-tight text-[#20384D] md:text-[32px]">
                  Cosmos: A Personal Voyage
                </h1>

                <div class="flex flex-wrap items-center gap-2">
                  <span class="text-base text-[#637587]">
                    Ditulis oleh
                    <a href="#" class="font-semibold text-[#315D78] hover:underline">
                      Carl Sagan
                    </a>
                  </span>
                  <span class="text-[#637587]">•</span>
                  <span class="text-xs text-[#637587]">
                    Dengan pengantar baru oleh Ann Druyan
                  </span>
                </div>
              </div>

              <!-- Synopsis -->
              <div class="flex flex-col gap-2 pt-1">
                <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                  <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-lg text-[#315D78]">menu_book</span>
                    <h2 class="text-base font-semibold text-[#20384D]">
                      Sinopsis & Cakupan Akademik
                    </h2>
                  </div>

                  <span class="self-start rounded bg-[#EAF0F4] px-2.5 py-0.5 text-[11px] font-semibold uppercase text-[#315D78]">
                    Rekomendasi Kelas 10–12
                  </span>
                </div>

                <p class="text-sm leading-7 text-[#293A49]">
                  Karya klasik Carl Sagan, <strong class="font-semibold text-[#20384D]">Cosmos</strong>,
                  merupakan perjalanan menakjubkan yang membahas evolusi alam semesta selama miliaran
                  tahun serta rasa ingin tahu manusia terhadap ilmu pengetahuan. Dengan memadukan
                  astrofisika, filsafat, biologi evolusi, dan sejarah budaya, Sagan membantu pembaca
                  memahami posisi manusia di tengah luasnya alam semesta.
                </p>

                <p class="text-sm leading-6 text-[#637587]">
                  Selain membahas ilmu astronomi, <em>Cosmos</em> juga mengajak pembaca untuk berpikir
                  kritis mengenai bumi sebagai tempat tinggal kita serta pentingnya menjaga planet ini.
                </p>
              </div>

              <!-- Chapters -->
              <div class="flex flex-col gap-2 pt-1">
                <span class="flex items-center gap-1 text-[11px] font-semibold uppercase tracking-wider text-[#637587]">
                  <span class="material-symbols-outlined text-base">auto_stories</span>
                  Bab Penting & Materi Pembelajaran
                </span>

                <div class="flex flex-wrap gap-1.5">
                  <span class="flex items-center gap-1.5 rounded-lg bg-[#EAF0F4] px-3 py-1.5 text-xs font-semibold text-[#20384D] transition-colors hover:bg-[#DFE8ED]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#006C45]"></span>
                    The Shores of the Cosmic Ocean
                  </span>
                  <span class="flex items-center gap-1.5 rounded-lg bg-[#EAF0F4] px-3 py-1.5 text-xs font-semibold text-[#20384D] transition-colors hover:bg-[#DFE8ED]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#006C45]"></span>
                    One Voice in the Cosmic Fugue
                  </span>
                  <span class="flex items-center gap-1.5 rounded-lg bg-[#EAF0F4] px-3 py-1.5 text-xs font-semibold text-[#20384D] transition-colors hover:bg-[#DFE8ED]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#006C45]"></span>
                    Harmony of the Worlds
                  </span>
                  <span class="flex items-center gap-1.5 rounded-lg bg-[#EAF0F4] px-3 py-1.5 text-xs font-semibold text-[#20384D] transition-colors hover:bg-[#DFE8ED]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#006C45]"></span>
                    The Backbone of Night
                  </span>
                  <span class="flex items-center gap-1.5 rounded-lg bg-[#EAF0F4] px-3 py-1.5 text-xs font-semibold text-[#20384D] transition-colors hover:bg-[#DFE8ED]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#006C45]"></span>
                    Travelers' Tales
                  </span>
                </div>
              </div>
            </section>

            <!-- Borrowing Rules -->
            <section class="flex flex-col gap-5 rounded-xl bg-white p-5 shadow-sm md:p-6">
              <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                <div class="flex items-center gap-2">
                  <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#315D78] text-white">
                    <span class="material-symbols-outlined text-lg">rule</span>
                  </div>
                  <h2 class="text-xl font-semibold text-[#20384D]">
                    Aturan Peminjaman Perpustakaan
                  </h2>
                </div>

                <span class="self-start rounded-full bg-[#EDF3F5] px-3 py-1 text-[11px] font-semibold text-[#006C45] sm:self-auto">
                  Berlaku untuk Siswa
                </span>
              </div>

              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Policy 1 -->
                <div class="flex items-start gap-3 rounded-xl bg-[#EAF0F4] p-4">
                  <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#315D78] shadow-sm">
                    <span class="material-symbols-outlined">event_repeat</span>
                  </div>
                  <div class="flex flex-col">
                    <span class="text-sm font-semibold text-[#20384D]">
                      Durasi Peminjaman 14 Hari
                    </span>
                    <p class="mt-1 text-xs leading-5 text-[#637587]">
                      Buku dapat dipinjam selama maksimal 14 hari sesuai kebijakan perpustakaan.
                    </p>
                  </div>
                </div>

                <!-- Policy 2 -->
                <div class="flex items-start gap-3 rounded-xl bg-[#EAF0F4] p-4">
                  <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#315D78] shadow-sm">
                    <span class="material-symbols-outlined">library_books</span>
                  </div>
                  <div class="flex flex-col">
                    <span class="text-sm font-semibold text-[#20384D]">
                      Maksimal 3 Buku
                    </span>
                    <p class="mt-1 text-xs leading-5 text-[#637587]">
                      Setiap siswa dapat meminjam maksimal 3 buku fisik secara bersamaan.
                    </p>
                  </div>
                </div>

                <!-- Policy 3 -->
                <div class="flex items-start gap-3 rounded-xl bg-[#EAF0F4] p-4">
                  <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#315D78] shadow-sm">
                    <span class="material-symbols-outlined">assignment_return</span>
                  </div>
                  <div class="flex flex-col">
                    <span class="text-sm font-semibold text-[#20384D]">
                      Pengembalian Buku
                    </span>
                    <p class="mt-1 text-xs leading-5 text-[#637587]">
                      Buku harus dikembalikan langsung ke meja sirkulasi perpustakaan selama jam operasional.
                    </p>
                  </div>
                </div>

                <!-- Policy 4 -->
                <div class="flex items-start gap-3 rounded-xl bg-[#EAF0F4] p-4">
                  <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#315D78] shadow-sm">
                    <span class="material-symbols-outlined">verified_user</span>
                  </div>
                  <div class="flex flex-col">
                    <span class="text-sm font-semibold text-[#20384D]">
                      Menjaga Kondisi Buku
                    </span>
                    <p class="mt-1 text-xs leading-5 text-[#637587]">
                      Jaga buku tetap bersih dan utuh. Laporkan kerusakan kepada petugas perpustakaan.
                    </p>
                  </div>
                </div>
              </div>
            </section>

            <!-- Related Books -->
            <section class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm md:p-6">
              <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                <div class="flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-lg text-[#315D78]">shelves</span>
                  <h2 class="text-base font-semibold text-[#20384D]">
                    Buku Terkait di Rak Sains & Astrofisika
                  </h2>
                </div>

                <button
                  type="button"
                  class="flex items-center gap-0.5 text-xs font-semibold text-[#315D78] hover:text-[#20384D]"
                  @click="goBack"
                >
                  Jelajahi Koleksi
                  <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
              </div>

              <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Related Book 1 -->
                <div class="group flex flex-col justify-between gap-3 rounded-xl bg-[#EAF0F4]/70 p-3 transition-all hover:bg-[#EAF0F4]">
                  <div class="flex gap-3">
                    <div class="flex h-22 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white shadow-sm">
                      <img
                        class="h-full w-full object-cover"
                        alt="Sampul A Brief History of Time"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZy1F9vc1KIWypRtxK78tVnQjhF0_PIsco_uB7jXEVJ72WX0-vZl2cjM7-cvH25Lo6rtLI7If46hXKev0QDsa5v31tJzcbzQRmhlqYiapRLxYGrsbp6hyhMP_AQ7LiKMwI380o4wmrbCBJwAFaiwr0FSGRzSmojSXrrcd5iXGx9CZ8ZiNAyAzEp18D2pE1X1585hTG6aFpk8O80Uh9Qs1A0z7SSG62jomWdG2dC45yVqJhrg9UZRkEdw"
                      />
                    </div>

                    <div class="flex min-w-0 flex-col">
                      <span class="truncate text-sm font-semibold text-[#20384D] transition-colors group-hover:text-[#315D78]">
                        A Brief History of Time
                      </span>
                      <span class="truncate text-xs text-[#637587]">
                        Stephen Hawking
                      </span>
                      <div class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-[#006C45]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]"></span>
                        Tersedia
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center justify-between border-t border-[#D4DEE5] pt-2">
                    <span class="font-mono text-[11px] text-[#637587]">Rak B-02</span>
                    <button
                      type="button"
                      class="flex items-center gap-0.5 text-[11px] font-semibold text-[#315D78] hover:text-[#20384D]"
                      @click="triggerToast('Detail Buku', 'Membuka detail A Brief History of Time.')"
                    >
                      Lihat
                      <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                  </div>
                </div>

                <!-- Related Book 2 -->
                <div class="group flex flex-col justify-between gap-3 rounded-xl bg-[#EAF0F4]/70 p-3 transition-all hover:bg-[#EAF0F4]">
                  <div class="flex gap-3">
                    <div class="flex h-22 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white shadow-sm">
                      <img
                        class="h-full w-full object-cover"
                        alt="Sampul Astrophysics for People in a Hurry"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBt31VWUYFIgmUs-8sR3NKs1ktUTJo349pbudNqjLbFOtbqBX-6YB4ARGJ0vhw3s1DYR5cPVakRlZUTBSA-imJBC5SOvbWStcKpiY2En1DHDxTyixGk9UcyFPw8EXBVzXEUbTcrnNdaQGH3WeONLbdjl90PuKIoEA_41WUuiglAMiti5PjIDdnG8pZ5L7ldLj7EcCXCr5KfMrHum5JAyiokoo8Hk7LaVjYIHYhCQ6fqxhr2dKzlILJzDg"
                      />
                    </div>

                    <div class="flex min-w-0 flex-col">
                      <span class="truncate text-sm font-semibold text-[#20384D] transition-colors group-hover:text-[#315D78]">
                        Astrophysics in a Hurry
                      </span>
                      <span class="truncate text-xs text-[#637587]">
                        Neil deGrasse Tyson
                      </span>
                      <div class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-[#006C45]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]"></span>
                        Tersedia
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center justify-between border-t border-[#D4DEE5] pt-2">
                    <span class="font-mono text-[11px] text-[#637587]">Rak B-10</span>
                    <button
                      type="button"
                      class="flex items-center gap-0.5 text-[11px] font-semibold text-[#315D78] hover:text-[#20384D]"
                      @click="triggerToast('Detail Buku', 'Membuka detail Astrophysics in a Hurry.')"
                    >
                      Lihat
                      <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                  </div>
                </div>

                <!-- Related Book 3 -->
                <div class="group flex flex-col justify-between gap-3 rounded-xl bg-[#EAF0F4]/70 p-3 transition-all hover:bg-[#EAF0F4]">
                  <div class="flex gap-3">
                    <div class="flex h-22 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white shadow-sm">
                      <img
                        class="h-full w-full object-cover"
                        alt="Sampul The Demon-Haunted World"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo_DDM8kTk8vZRF7v6wf9gOr19uiQpmr5vbJAY_pIBRfoA1-dKD27mMUVMDCPO-yK-8mGVxW9qZ39D7Q5yBxvs_lFL75vkGKcH9i7N4FKh-dctBC7byBqd8KEkpZBdpvmiC22qJA02SZXXzpdaOLecNPtK0iuPbicvrKNjgqBdtVbd95wb_CQ7TTigjA1zS-heobTibDLSsYLJ1y7EG_QxGGfvik2fRO2t90yTR8PCgJ_dlJPPlk7tsA"
                      />
                    </div>

                    <div class="flex min-w-0 flex-col">
                      <span class="truncate text-sm font-semibold text-[#20384D] transition-colors group-hover:text-[#315D78]">
                        The Demon-Haunted World
                      </span>
                      <span class="truncate text-xs text-[#637587]">
                        Carl Sagan
                      </span>
                      <div class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-[#20384D]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]"></span>
                        1 eksemplar tersedia
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center justify-between border-t border-[#D4DEE5] pt-2">
                    <span class="font-mono text-[11px] text-[#637587]">Rak B-15</span>
                    <button
                      type="button"
                      class="flex items-center gap-0.5 text-[11px] font-semibold text-[#315D78] hover:text-[#20384D]"
                      @click="triggerToast('Detail Buku', 'Membuka detail The Demon-Haunted World.')"
                    >
                      Lihat
                      <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
  </StudentLayout>
</template>