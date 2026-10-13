<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import StudentNavbar from '../../components/student/StudentNavbar.vue'
import StudentFooter from '../../components/student/StudentFooter.vue'

const books = ref([
  {
    id: 1,
    title: 'Cosmos: A Personal Voyage',
    author: 'Carl Sagan',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAkZxldLXYoeS1vHjSSESBAhdgBKOVAhYdQ8C_LCWFRDIr-zxX3AYII0KjpV41TzggU9MK_daauNEWjS6x0l1cqnhpqVrOAwslqRcxVwJs3csRccL2TnVE2fGM7v_G-XrgpNX4qlyhcI7PLUHdZsu13dZF-z_PU7VEkyRI6f7tp0LmVdMcgLsnz_533uJT5OzYX5Fp6LGy2dnrv9Xt7KkpbIARKa_yZPYDrqmiqRf14bZW0ugETB66LNg',
    code: '520.1 SAG',
    category: 'science',
    categoryLabel: 'Sains',
    subject: 'Astronomi',
    badge: 'STEM',
    shelf: 'B-14',
    available: true,
    dueDate: '',
    added: 5,
  },
  {
    id: 2,
    title: 'To Kill a Mockingbird',
    author: 'Harper Lee',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZb7pIlk76j-OoRZ0gz3X6ufptqUqC4U5-pNVE7yTcYdB2d2zND5IbYoG2WrfDrauZwaZ1pbvk9tHUvIdgcpoxIMiubrj-KzDf3ENFOEDqFFuTkOWeordbmx_J04NtoK4Tas1yupwtX5xiUu1OH7RNbcblqdKMGxI8mAdn2YyAeCWXioa1sxpvWnvhRVGZeXlrgPGHVpUlJnAC9COSIAqj89vvnl6571LF_TS7FK95gTkVr5vH3oEclQ',
    code: '813.5 LEE',
    category: 'classics',
    categoryLabel: 'Klasik',
    subject: 'Sastra Kelas 10',
    badge: 'LIT',
    shelf: '',
    available: false,
    dueDate: '12 November',
    added: 4,
  },
  {
    id: 3,
    title: '1984',
    author: 'George Orwell',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuANTW3GQZqWQnPlG_-BmLUxas3eiOehdxE_2ibJz8rsS4FlzO9mQAOuKR4tpoaU8C6LdIqA8TmGbSm7HIVoRE1YJxGRgah8Rbb5PTE9NjY4oh4Rx0AlkROvUAoTXUoWsZYWOiA0DggDQEWK1m__U9BqW3BVErMOIGvHWqB3Rqd6GAHckfuv_T20G2di5M7RrE54l7QELaDmgp_3ZUylc2qHXtSwt0H52wdUD_WkBJe2NVPjg9ttX1nWag',
    code: '823.9 ORW',
    category: 'classics',
    categoryLabel: 'Distopia',
    subject: 'Ilmu Sosial',
    badge: 'SOC',
    shelf: 'C-08',
    available: true,
    dueDate: '',
    added: 3,
  },
  {
    id: 4,
    title: 'A Brief History of Time',
    author: 'Stephen Hawking',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQkvFsyo3sYnv27CUJDl9Q5pzSN9UO6Kvy7ewbhTsuK49GmgwjaUqZdB096_crpywpfDS053_YEc0U7fvmxvfkUEA44HaqYl-VbZVePYSFbqxzBMmvpEqV5mO32s-QWSmuRv_2VZB_9mQqj196n9QImR2s6rqKbpX96N79TdDs4CCVEhZAHNh8QxcZqzqze8HgpVPe56HNtBXrGRTZDq0rM_plesGY3oeu-p56FHtkRJpSkuCy2eYMNw',
    code: '530.1 HAW',
    category: 'science',
    categoryLabel: 'Fisika',
    subject: 'Rak STEM',
    badge: 'STEM',
    shelf: 'B-02',
    available: true,
    dueDate: '',
    added: 2,
  },
  {
    id: 5,
    title: 'The Great Gatsby',
    author: 'F. Scott Fitzgerald',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDAgNZZCrOoqMuLaxnRG7P4PcqT8NPdOQ6f2I2Qmx7ZfsBTdZ8h97Vpjd_uNZcOJDVHpMjjNigtwskd4IpQF2oiHqxsKxhtZUmGy-vlL1nk9UpiZyFtZwL7T5LYnAqC9nV_yUIn73sPGCxGxXxzdsYvfpzyexjq9zWmXvZ3JtrdEPXG-thFokJtuP7KF1fQpNk-YwZKt9ozhgtm1PiBnjX0f09VAVBSJKXXGSz03HtsOczsaDXSp-XOHg',
    code: '813.5 FIT',
    category: 'classics',
    categoryLabel: 'Sastra Amerika',
    subject: 'Sastra SMA',
    badge: 'LIT',
    shelf: '',
    available: false,
    dueDate: '18 November',
    added: 1,
  },
])

const searchQuery = ref('')
const activeFilter = ref('all')
const sortBy = ref('recent')
const viewMode = ref('grid')
const showBanner = ref(true)
const showClearDialog = ref(false)

const toast = ref({
  show: false,
  title: '',
  message: '',
})

let toastTimer

const totalBooks = computed(() => books.value.length)
const availableCount = computed(
  () => books.value.filter((book) => book.available).length,
)
const loanCount = computed(
  () => books.value.filter((book) => !book.available).length,
)

const filteredBooks = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()

  let result = books.value.filter((book) => {
    const matchesSearch = [
      book.title,
      book.author,
      book.code,
      book.categoryLabel,
      book.subject,
      book.shelf,
    ]
      .filter(Boolean)
      .some((value) => value.toLowerCase().includes(query))

    const matchesFilter =
      activeFilter.value === 'all' ||
      (activeFilter.value === 'available' && book.available) ||
      (activeFilter.value === 'loan' && !book.available) ||
      book.category === activeFilter.value

    return matchesSearch && matchesFilter
  })

  result = [...result]

  if (sortBy.value === 'title') {
    result.sort((a, b) => a.title.localeCompare(b.title))
  } else if (sortBy.value === 'available') {
    result.sort((a, b) => Number(b.available) - Number(a.available))
  } else if (sortBy.value === 'shelf') {
    result.sort((a, b) => {
      return (a.shelf || 'ZZZ').localeCompare(b.shelf || 'ZZZ')
    })
  } else {
    result.sort((a, b) => b.added - a.added)
  }

  return result
})

function triggerToast(title, message) {
  toast.value = { show: true, title, message }

  clearTimeout(toastTimer)
  toastTimer = setTimeout(() => {
    toast.value.show = false
  }, 3500)
}

function removeBookmark(book) {
  books.value = books.value.filter((item) => item.id !== book.id)

  triggerToast(
    'Bookmark dihapus',
    `${book.title} telah dihapus dari daftar bookmark.`,
  )
}

function restoreBook(book) {
  if (!books.value.some((item) => item.id === book.id)) {
    books.value.push(book)
  }
}

function clearAllBookmarks() {
  books.value = []
  showClearDialog.value = false

  triggerToast(
    'Bookmark dibersihkan',
    'Semua buku telah dihapus dari daftar bookmark.',
  )
}

function reserveBook(book) {
  if (!book.available) {
    triggerToast(
      'Buku sedang dipinjam',
      `Buku ${book.title} belum tersedia. Kamu bisa mengajukan antrean peminjaman di perpustakaan.`,
    )
    return
  }

  triggerToast(
    'Informasi peminjaman',
    `Silakan kunjungi rak ${book.shelf} dan bawa kartu siswa untuk meminjam ${book.title}.`,
  )
}

function setFilter(filter) {
  activeFilter.value = filter
}

function handleKeyboard(event) {
  if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
    event.preventDefault()
    document.getElementById('bookmark-search')?.focus()
  }

  if (event.key === 'Escape') {
    showClearDialog.value = false
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyboard)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyboard)
  clearTimeout(toastTimer)
})
</script>

<template>
  <div class="min-h-screen bg-[#DDF4E7]">
    <StudentNavbar />

    <!-- Notifikasi -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="translate-y-5 opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="translate-y-5 opacity-0"
    >
      <div
        v-if="toast.show"
        class="fixed bottom-5 right-4 z-[70] flex w-[calc(100%-2rem)] max-w-md items-start gap-3 rounded-xl border border-[#C2E2D4] bg-white p-4 shadow-xl sm:right-6"
        role="status"
        aria-live="polite"
      >
        <div
          class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E2F9EC] text-[#006C45]"
        >
          <span class="material-symbols-outlined">check_circle</span>
        </div>
        <div class="min-w-0 flex-1">
          <p class="text-sm font-semibold text-[#124170]">
            {{ toast.title }}
          </p>
          <p class="mt-1 text-xs leading-5 text-[#6F8B95]">
            {{ toast.message }}
          </p>
        </div>
        <button
          type="button"
          aria-label="Tutup notifikasi"
          class="text-[#6F8B95] hover:text-[#124170]"
          @click="toast.show = false"
        >
          <span class="material-symbols-outlined text-lg">close</span>
        </button>
      </div>
    </Transition>

    <!-- Konten utama -->
    <main class="min-h-screen w-full bg-[#DDF4E7] pt-20">
      <div
        class="mx-auto flex w-full max-w-[1200px] flex-col gap-6 px-4 py-6 md:px-6 lg:py-8"
      >

        <!-- Judul halaman -->
        <section
          class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end"
        >
          <div class="flex flex-col gap-2">
            <div class="flex flex-wrap items-center gap-3">
              <h1
                class="text-2xl font-bold tracking-tight text-[#124170] sm:text-3xl"
              >
                Buku Favorit Saya
              </h1>
              <span
                class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-[#26667F] shadow-sm"
              >
                {{ totalBooks }} buku tersimpan
              </span>
            </div>

            <p class="max-w-2xl text-sm leading-6 text-[#6F8B95]">
              Kumpulan buku pilihan untuk bahan belajar, penelitian, dan bacaan
              berikutnya.
            </p>
          </div>

          <button
            v-if="totalBooks > 0"
            type="button"
            class="inline-flex items-center justify-center gap-2 self-start rounded-xl bg-white px-4 py-2.5 text-xs font-semibold text-[#6F8B95] shadow-sm transition-colors hover:bg-[#FDE8E8] hover:text-[#BA1A1A] sm:self-auto"
            @click="showClearDialog = true"
          >
            <span class="material-symbols-outlined text-lg">delete_sweep</span>
            Hapus Semua
          </button>
        </section>

        <!-- Banner informasi -->
        <Transition
          enter-active-class="transition duration-200"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="transition duration-150"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <section
            v-if="showBanner"
            class="relative flex items-start gap-3 overflow-hidden rounded-xl bg-white p-4 shadow-sm sm:items-center sm:p-5"
          >
            <div
              class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#E2F9EC] text-[#26667F]"
            >
              <span class="material-symbols-outlined text-2xl">
                auto_stories
              </span>
            </div>

            <p class="relative z-10 flex-1 pr-1 text-sm leading-6 text-[#0c1f17]">
              Simpan buku yang ingin kamu baca atau gunakan untuk tugas sekolah.
              Temukan kembali koleksimu dengan mudah melalui pencarian dan
              filter kategori.
            </p>

            <button
              type="button"
              aria-label="Tutup informasi"
              class="relative z-10 rounded-lg p-1 text-[#6F8B95] transition-colors hover:text-[#124170]"
              @click="showBanner = false"
            >
              <span class="material-symbols-outlined">close</span>
            </button>

            <div
              class="pointer-events-none absolute -bottom-8 -right-8 h-28 w-28 rounded-full bg-[#DDF4E7] opacity-60"
            ></div>
          </section>
        </Transition>

        <!-- Layout daftar buku dan sidebar -->
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
          <!-- Daftar buku -->
          <section class="flex min-w-0 flex-col gap-4 lg:col-span-8">
            <!-- Pencarian, urutan, dan tampilan -->
            <div class="flex flex-col gap-4 rounded-xl bg-white p-4 shadow-sm">
              <div
                class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
              >
                <div class="relative min-w-0 flex-1">
                  <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-xl text-[#26667F]"
                  >
                    search
                  </span>

                  <input
                    id="bookmark-search"
                    v-model="searchQuery"
                    type="search"
                    placeholder="Cari judul, penulis, nomor panggil..."
                    class="h-11 w-full rounded-xl bg-[#E2F9EC] py-2 pl-10 pr-3 text-sm text-[#124170] outline-none transition-all placeholder:text-[#6F8B95] focus:bg-white focus:ring-2 focus:ring-[#26667F]/20"
                  />
                </div>

                <div class="flex items-center gap-2">
                  <div class="relative min-w-0 flex-1 sm:flex-none">
                    <select
                      v-model="sortBy"
                      aria-label="Urutkan buku"
                      class="h-11 w-full appearance-none rounded-xl bg-[#E2F9EC] py-2 pl-3 pr-9 text-xs font-semibold text-[#124170] outline-none focus:ring-2 focus:ring-[#26667F]/20 sm:w-auto"
                    >
                      <option value="recent">Terbaru disimpan</option>
                      <option value="title">Judul A–Z</option>
                      <option value="available">Ketersediaan</option>
                      <option value="shelf">Lokasi rak</option>
                    </select>
                    <span
                      class="material-symbols-outlined pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-lg text-[#6F8B95]"
                    >
                      expand_more
                    </span>
                  </div>

                  <div
                    class="flex shrink-0 items-center rounded-xl bg-[#E2F9EC] p-1"
                  >
                    <button
                      type="button"
                      aria-label="Tampilan kisi"
                      :aria-pressed="viewMode === 'grid'"
                      class="flex h-9 w-9 items-center justify-center rounded-lg transition-all"
                      :class="
                        viewMode === 'grid'
                          ? 'bg-white text-[#124170] shadow-sm'
                          : 'text-[#6F8B95] hover:text-[#124170]'
                      "
                      @click="viewMode = 'grid'"
                    >
                      <span class="material-symbols-outlined">grid_view</span>
                    </button>
                    <button
                      type="button"
                      aria-label="Tampilan daftar"
                      :aria-pressed="viewMode === 'list'"
                      class="flex h-9 w-9 items-center justify-center rounded-lg transition-all"
                      :class="
                        viewMode === 'list'
                          ? 'bg-white text-[#124170] shadow-sm'
                          : 'text-[#6F8B95] hover:text-[#124170]'
                      "
                      @click="viewMode = 'list'"
                    >
                      <span class="material-symbols-outlined">view_agenda</span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Filter -->
              <div
                class="flex items-center gap-2 overflow-x-auto pb-1"
              >
                <button
                  v-for="filter in [
                    { value: 'all', label: `Semua (${totalBooks})` },
                    {
                      value: 'available',
                      label: `Tersedia (${availableCount})`,
                    },
                    { value: 'loan', label: `Dipinjam (${loanCount})` },
                    { value: 'science', label: 'Sains & STEM' },
                    { value: 'classics', label: 'Klasik & Sastra' },
                  ]"
                  :key="filter.value"
                  type="button"
                  class="shrink-0 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all"
                  :class="
                    activeFilter === filter.value
                      ? 'bg-[#26667F] text-white shadow-sm'
                      : 'bg-[#E2F9EC] text-[#26667F] hover:bg-[#D7EEE1]'
                  "
                  @click="setFilter(filter.value)"
                >
                  {{ filter.label }}
                </button>
              </div>
            </div>

            <!-- Kartu buku -->
            <div
              v-if="filteredBooks.length > 0"
              class="grid items-stretch gap-4"
              :class="
                viewMode === 'grid'
                  ? 'grid-cols-1 md:grid-cols-2'
                  : 'grid-cols-1'
              "
            >
              <article
                v-for="book in filteredBooks"
                :key="book.id"
                class="group flex min-w-0 flex-col justify-between gap-4 rounded-xl bg-white p-4 shadow-sm transition-all duration-200 hover:shadow-md sm:p-5"
              >
                <div
                  class="flex min-w-0 gap-3 sm:gap-4"
                  :class="viewMode === 'list' ? 'sm:gap-5' : ''"
                >
                  <!-- Sampul -->
                  <div
                    class="group relative h-36 w-24 shrink-0 overflow-hidden rounded-xl bg-[#E2F9EC] shadow-sm ring-1 ring-[#124170]/10"
                  >

                    <RouterLink
                      :to="{ name: 'book-detail', params: { id: book.id } }"
                      class="block h-full w-full overflow-hidden"
                      :aria-label="`Lihat detail buku ${book.title}`"
                    >
                      <img
                        :src="book.image"
                        :alt="`Sampul buku ${book.title}`"
                        class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
                        loading="lazy"
                      />
                    </RouterLink>

                    <span
                      class="pointer-events-none absolute left-1.5 top-1.5 max-w-[calc(100%-12px)] truncate rounded-md bg-white/95 px-1.5 py-1 text-[9px] font-bold text-[#124170] shadow-sm backdrop-blur-sm"
                    >
                      {{ book.badge }}
                    </span>
                  </div>


                  <!-- Informasi buku -->
                  <div class="flex min-w-0 flex-1 flex-col gap-1">
                    <div class="flex items-start gap-1">
                      <h2
                        class="min-w-0 flex-1 text-sm font-semibold leading-5 text-[#124170] sm:text-base"
                      >
                        {{ book.title }}
                      </h2>

                      <button
                        type="button"
                        :aria-label="`Hapus bookmark ${book.title}`"
                        title="Hapus dari bookmark"
                        class="shrink-0 rounded-lg p-1 text-[#26667F] transition-colors hover:bg-[#FDE8E8] hover:text-[#BA1A1A]"
                        @click="removeBookmark(book)"
                      >
                        <span
                          class="material-symbols-outlined text-xl"
                          style="font-variation-settings: 'FILL' 1"
                        >
                          bookmark
                        </span>
                      </button>
                    </div>

                    <p class="text-xs text-[#6F8B95]">
                      {{ book.author }}
                    </p>

                    <div class="mt-2 flex flex-wrap gap-1.5">
                      <span
                        class="rounded-md bg-[#E2F9EC] px-2 py-1 text-[10px] font-semibold text-[#26667F]"
                      >
                        {{ book.categoryLabel }}
                      </span>
                      <span
                        class="rounded-md bg-[#E2F9EC] px-2 py-1 text-[10px] text-[#26667F]"
                      >
                        {{ book.subject }}
                      </span>
                    </div>

                    <p class="mt-1 font-mono text-[11px] text-[#6F8B95]">
                      No. panggil {{ book.code }}
                    </p>
                  </div>
                </div>
              </article>
            </div>

            <!-- Hasil kosong -->
            <div
              v-else
              class="flex flex-col items-center justify-center rounded-xl bg-white px-6 py-12 text-center shadow-sm sm:py-16"
            >
              <div
                class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#E2F9EC] text-[#26667F]"
              >
                <span class="material-symbols-outlined text-3xl">
                  {{ totalBooks === 0 ? 'bookmark_remove' : 'search_off' }}
                </span>
              </div>

              <h2 class="mt-4 text-lg font-semibold text-[#124170]">
                {{
                  totalBooks === 0
                    ? 'Belum ada buku tersimpan'
                    : 'Buku tidak ditemukan'
                }}
              </h2>

              <p class="mt-2 max-w-sm text-sm leading-6 text-[#6F8B95]">
                {{
                  totalBooks === 0
                    ? 'Tambahkan buku ke bookmark dari katalog agar mudah ditemukan kembali.'
                    : 'Coba kata kunci lain atau ubah filter untuk menemukan buku yang kamu cari.'
                }}
              </p>

              <RouterLink
                v-if="totalBooks === 0"
                to="/vue"
                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#26667F] px-4 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-[#124170]"
              >
                <span class="material-symbols-outlined text-base">
                  auto_stories
                </span>
                Jelajahi Katalog
              </RouterLink>

              <button
                v-else
                class="mt-5 rounded-xl bg-[#26667F] px-4 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-[#124170]"
                @click="
                  searchQuery = '';
                  activeFilter = 'all';
                "
              >
                Atur Ulang Pencarian
              </button>
            </div>

            <!-- Ringkasan daftar -->
            <div
              class="flex flex-col gap-2 rounded-xl bg-white p-4 text-xs text-[#6F8B95] shadow-sm sm:flex-row sm:items-center sm:justify-between"
            >
              <span>
                Menampilkan
                <strong class="text-[#124170]">
                  {{ filteredBooks.length }} dari {{ totalBooks }}
                </strong>
                buku tersimpan
              </span>

              <span
                class="inline-flex items-center gap-1.5 font-semibold text-[#006C45]"
              >
                <span class="material-symbols-outlined text-base">
                  check_circle
                </span>
                Daftar diperbarui secara langsung
              </span>
            </div>
          </section>

          <!-- Sidebar -->
          <aside class="flex min-w-0 flex-col gap-5 lg:col-span-4">
            <!-- Ringkasan koleksi -->
            <section
              class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm"
            >
              <div class="flex items-center justify-between gap-2">
                <h2 class="text-lg font-semibold text-[#124170]">
                  Ringkasan Koleksi
                </h2>
                <span class="material-symbols-outlined text-[#26667F]">
                  pie_chart
                </span>
              </div>

              <!-- Kuota peminjaman -->
              <div class="flex flex-col gap-3 rounded-xl bg-[#E2F9EC] p-4">
                <div class="flex items-center justify-between gap-2">
                  <span class="text-xs font-semibold text-[#124170]">
                    Kuota Peminjaman
                  </span>
                  <span class="text-lg font-bold text-[#26667F]">
                    {{ loanCount }} / 3
                  </span>
                </div>

                <p class="text-xs leading-5 text-[#6F8B95]">
                  Jumlah buku pada daftar ini yang sedang dipinjam. Batas
                  peminjaman perlu mengikuti kebijakan perpustakaan.
                </p>

                <div class="h-2.5 overflow-hidden rounded-full bg-white">
                  <div
                    class="h-full rounded-full bg-[#26667F] transition-all duration-300"
                    :style="{
                      width: `${Math.min((loanCount / 3) * 100, 100)}%`,
                    }"
                  ></div>
                </div>
              </div>

              <!-- Statistik -->
              <div class="flex flex-col gap-1">
                <div
                  class="flex items-center justify-between rounded-lg px-2 py-3 transition-colors hover:bg-[#E2F9EC]"
                >
                  <div class="flex items-center gap-2 text-sm text-[#0c1f17]">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#26667F]"></span>
                    Total buku tersimpan
                  </div>
                  <span class="text-sm font-semibold text-[#124170]">
                    {{ totalBooks }} buku
                  </span>
                </div>

                <div
                  class="flex items-center justify-between rounded-lg px-2 py-3 transition-colors hover:bg-[#E2F9EC]"
                >
                  <div class="flex items-center gap-2 text-sm text-[#0c1f17]">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#67C090]"></span>
                    Buku tersedia
                  </div>
                  <span class="text-sm font-semibold text-[#006C45]">
                    {{ availableCount }} buku
                  </span>
                </div>

                <div
                  class="flex items-center justify-between rounded-lg px-2 py-3 transition-colors hover:bg-[#E2F9EC]"
                >
                  <div class="flex items-center gap-2 text-sm text-[#0c1f17]">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#E55353]"></span>
                    Sedang dipinjam
                  </div>
                  <span class="text-sm font-semibold text-[#BA1A1A]">
                    {{ loanCount }} buku
                  </span>
                </div>
              </div>
            </section>

            <!-- Panduan lokasi rak -->
            <section
              class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm"
            >
              <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[#26667F]">
                    map
                  </span>
                  <h2 class="text-base font-semibold text-[#124170]">
                    Panduan Lokasi Rak
                  </h2>
                </div>

                <span
                  class="rounded-full bg-[#E8F8F0] px-2 py-1 text-[10px] font-semibold text-[#006C45]"
                >
                  Sayap Utama
                </span>
              </div>

              <p class="text-xs leading-5 text-[#6F8B95]">
                Temukan buku yang tersedia berdasarkan lokasi rak di
                perpustakaan sekolah.
              </p>

              <div class="flex flex-col gap-2">
                <div
                  class="flex items-center gap-3 rounded-xl bg-[#E2F9EC] p-3"
                >
                  <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-bold text-[#26667F] shadow-sm"
                  >
                    B
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-[#124170]">
                      Sains & Fisika
                    </p>
                    <p class="mt-1 text-[11px] leading-4 text-[#6F8B95]">
                      Rak B-02, B-14 · Lantai 2
                    </p>
                  </div>
                </div>

                <div
                  class="flex items-center gap-3 rounded-xl bg-[#E2F9EC] p-3"
                >
                  <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-bold text-[#26667F] shadow-sm"
                  >
                    C
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-[#124170]">
                      Sastra & Klasik
                    </p>
                    <p class="mt-1 text-[11px] leading-4 text-[#6F8B95]">
                      Rak C-08 · Lantai 1
                    </p>
                  </div>
                </div>
              </div>
            </section>

            <!-- Informasi siswa -->
            <section
              class="flex flex-col gap-2 rounded-xl bg-[#D7EEE1] p-5"
            >
              <div class="flex items-center gap-2 text-[#124170]">
                <span class="material-symbols-outlined">school</span>
                <h2 class="text-sm font-semibold">Informasi Siswa</h2>
              </div>

              <p class="text-xs leading-5 text-[#6F8B95]">
                Pengajuan peminjaman dan antrean buku mengikuti prosedur
                perpustakaan. Konfirmasikan ketersediaan kepada petugas
                sebelum mengambil buku.
              </p>

              <RouterLink
                to="/my-library"
                class="mt-1 inline-flex items-center gap-1 self-start text-xs font-semibold text-[#26667F] transition-colors hover:text-[#124170]"
              >
                Lihat Perpustakaanku
                <span class="material-symbols-outlined text-base">
                  arrow_forward
                </span>
              </RouterLink>
            </section>
          </aside>
        </div>
      </div>
    </main>

    <!-- Konfirmasi hapus semua -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="showClearDialog"
        class="fixed inset-0 z-[80] flex items-center justify-center bg-[#0c1f17]/40 p-4 backdrop-blur-sm"
        @click.self="showClearDialog = false"
        @keydown.esc="showClearDialog = false"
      >
        <section
          class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
          role="dialog"
          aria-modal="true"
          aria-labelledby="clear-dialog-title"
        >
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#FDE8E8] text-[#BA1A1A]"
          >
            <span class="material-symbols-outlined text-2xl">
              delete_sweep
            </span>
          </div>

          <h2
            id="clear-dialog-title"
            class="mt-4 text-lg font-bold text-[#124170]"
          >
            Hapus semua bookmark?
          </h2>

          <p class="mt-2 text-sm leading-6 text-[#6F8B95]">
            Semua {{ totalBooks }} buku akan dihapus dari daftar bookmark pada
            halaman ini. Tindakan ini tidak dapat dibatalkan.
          </p>

          <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button
              type="button"
              class="rounded-xl bg-[#E2F9EC] px-4 py-2.5 text-xs font-semibold text-[#26667F] transition-colors hover:bg-[#D7EEE1]"
              @click="showClearDialog = false"
            >
              Batal
            </button>

            <button
              type="button"
              class="rounded-xl bg-[#BA1A1A] px-4 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-[#93000A]"
              @click="clearAllBookmarks"
            >
              Ya, Hapus Semua
            </button>
          </div>
        </section>
      </div>
    </Transition>

    <StudentFooter />
  </div>
</template>

<style scoped>
.material-symbols-outlined {
  font-family: 'Material Symbols Outlined';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}
</style>
