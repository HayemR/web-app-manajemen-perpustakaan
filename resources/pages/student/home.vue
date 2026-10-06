<script setup>
import { computed, ref } from 'vue'

const searchQuery = ref('')
const selectedCategory = ref('all')
const availableOnly = ref(false)
const sortBy = ref('popular')

const toast = ref({
  visible: false,
  title: '',
  message: '',
})

const selectedBook = ref(null)

const books = [
  {
    id: 1,
    title: 'Cosmos: A Personal Voyage',
    author: 'Carl Sagan',
    category: 'science',
    categoryLabel: 'Science',
    code: 'SCI-204',
    shelf: 'Shelf B-14',
    status: 'available',
    statusLabel: 'In Stack',
    image:
      'https://lh3.googleusercontent.com/aida-public/AB6AXuAkZxldLXYoeS1vHjSSESBAhdgBKOVAhYdQ8C_LCWFRDIr-zxX3AYII0KjpV41TzggU9MK_daauNEWjS6x0l1cqnhpqVrOAwslqRcxVwJs3csRccL2TnVE2fGM7v_G-XrgpNX4qlyhcI7PLUHdZsu13dZF-z_PU7VEkyRI6f7tp0LmVdMcgLsnz_533uJT5OzYX5Fp6LGy2dnrv9Xt7KkpbIARKa_yZPYDrqmiqRf14bZW0ugETB66LNg',
    bookmarked: false,
  },
  {
    id: 2,
    title: 'To Kill a Mockingbird',
    author: 'Harper Lee',
    category: 'fiction',
    categoryLabel: 'Fiction',
    code: 'FIC-108',
    shelf: 'Shelf A-05',
    status: 'available',
    statusLabel: 'In Stack',
    image:
      'https://lh3.googleusercontent.com/aida-public/AB6AXuAZb7pIlk76j-OoRZ0gz3X6ufptqUqC4U5-pNVE7yTcYdB2d2zND5IbYoG2WrfDrauZwaZ1pbvk9tHUvIdgcpoxIMiubrj-KzDf3ENFOEDqFFuTkOWeordbmx_J04NtoK4Tas1yupwtX5xiUu1OH7RNbcblqdKMGxI8mAdn2YyAeCWXioa1sxpvWnvhRVGZeXlrgPGHVpUlJnAC9COSIAqj89vvnl6571LF_TS7FK95gTkVr5vH3oEclQ',
    bookmarked: false,
  },
  {
    id: 3,
    title: 'A Brief History of Time',
    author: 'Stephen Hawking',
    category: 'science',
    categoryLabel: 'Science',
    code: 'SCI-330',
    shelf: 'Due Nov 12',
    status: 'unavailable',
    statusLabel: 'Checked Out',
    image:
      'https://lh3.googleusercontent.com/aida-public/AB6AXuCn_tzD-224i4sY7so6IoAno8LpJbSBrsn5XvtIsYJWfa6DopPmB5N-2PpSbnLhSkQwWXoYLj4nxkQ3RKBiBp5TAY_dm3VHiW3Nm0cWI_hWswPKVSxH7b28HzHNqLvYKLcox4fgmjBOqUmdQGWmCAbhHN7rwnSiTxQSZhiJwYqIUNwyDOLp3i3t2_CDsftL53N5GZSdK_5Hu9wRD6v6JTAiLuXVlfkJnNEUv7nQo1XNJQ0NY2G5g1oqYQ',
    bookmarked: false,
  },
  {
    id: 4,
    title: 'The Great Gatsby',
    author: 'F. Scott Fitzgerald',
    category: 'classics',
    categoryLabel: 'Classics',
    code: 'NOV-042',
    shelf: 'Shelf C-12',
    status: 'available',
    statusLabel: 'In Stack',
    image:
      'https://lh3.googleusercontent.com/aida-public/AB6AXuD1RKb0cQbD_NijHPZ2VHcRDN9WoF5mf2YOQneN3hoSvgpjJWMJZEwDtjVzjQ2dDKRQY-Axj5_wYJJhF5wMCvj9LU7QyEI_Fy4xojzWG8eFHX_uepPjCamEyfEaCGxzshX2kYbhHxBZGoLHiE6HZK4HZAk5ew5I5R9VO4LJtf9yJy7Orf2b94lombVjoAVZDp7E-liIG-PcnnrgFCpRoTBow9xA0sPJ0ANlo1K7RoTQYVUBWd76zbLaUQ',
    bookmarked: false,
  },
  {
    id: 5,
    title: 'Dune',
    author: 'Frank Herbert',
    category: 'fiction',
    categoryLabel: 'Fiction',
    code: 'FIC-994',
    shelf: 'Due Next Mon',
    status: 'unavailable',
    statusLabel: 'Reserved (1)',
    image:
      'https://lh3.googleusercontent.com/aida-public/AB6AXuCdBnzNhv6eEX8R_GcwY8CShzZ_2Ixuo5gfr2SMmutg4cfWh7tNeKLVfuUU5WmFocNGWmk_vP7rkYy-n7t6K4t2IHb-M7Nbr4HrxJWOLhW6w0l78gmOR9aiEax43PlhXNysPIqkfMWk6V-XYifzdVDCY6pPWq-AVAb2dzOX7K0Vs9ft_BeS-jrllpNHWu3asJIdJ-AIK0UvIATM8JGIoQcRC3Nrx3d8_wrq_KrjZrOguI1gOv2wv3KQyw',
    bookmarked: false,
  },
  {
    id: 6,
    title: '1984',
    author: 'George Orwell',
    category: 'dystopian',
    categoryLabel: 'Dystopian',
    code: 'SOC-101',
    shelf: 'Shelf C-08',
    status: 'available',
    statusLabel: 'In Stack',
    image:
      'https://lh3.googleusercontent.com/aida-public/AB6AXuBxEKmCFT4AZ9xwQqUqIpT3xNUNPoweX5K_tyF84GNJ9-eiphw4Vv-tceIqLPn4zg9ApTqAXn5ahUuSt1XJ1XB_jSEIE7zw4PNiQi19kIOsvZxU-Ek-LRXNs_ZeoG0kcbMkaAm3xKmtH8gVTDKTKoAYOMFsqIFeJtDWnTvPlKg0b3NOk39kF-DaI9ljw_6thHhfRRr0AHhGBzH0SBnXYgZFuNFB4z4irvuOygJ83og0aMrmUvAAbX9w9A',
    bookmarked: false,
  },
]

const categories = [
  { value: 'all', label: 'All Books' },
  { value: 'fiction', label: 'Fiction' },
  { value: 'science', label: 'Science & Space' },
  { value: 'classics', label: 'Classics & Literature' },
  { value: 'dystopian', label: 'Dystopian' },
  { value: 'history', label: 'History' },
  { value: 'graphic', label: 'Graphic Novels' },
]

const filteredBooks = computed(() => {
  const query = searchQuery.value.toLowerCase().trim()

  let result = books.filter((book) => {
    const matchesSearch =
      !query ||
      book.title.toLowerCase().includes(query) ||
      book.author.toLowerCase().includes(query) ||
      book.categoryLabel.toLowerCase().includes(query) ||
      book.code.toLowerCase().includes(query)

    const matchesCategory =
      selectedCategory.value === 'all' ||
      book.category === selectedCategory.value

    const matchesAvailability =
      !availableOnly.value || book.status === 'available'

    return matchesSearch && matchesCategory && matchesAvailability
  })

  if (sortBy.value === 'title') {
    result = [...result].sort((a, b) =>
      a.title.localeCompare(b.title),
    )
  }

  if (sortBy.value === 'author') {
    result = [...result].sort((a, b) =>
      a.author.localeCompare(b.author),
    )
  }

  return result
})

let toastTimer

function showToast(title, message) {
  clearTimeout(toastTimer)

  toast.value = {
    visible: true,
    title,
    message,
  }

  toastTimer = setTimeout(() => {
    toast.value.visible = false
  }, 3200)
}

function goToScan(book = null) {
  selectedBook.value = book

  document.querySelector('#scan-section')?.scrollIntoView({
    behavior: 'smooth',
    block: 'center',
  })
}

function reserveBook(book) {
  goToScan(book)
}

function toggleBookmark(book) {
  book.bookmarked = !book.bookmarked

  if (book.bookmarked) {
    showToast(
      'Book Saved! 🔖',
      `"${book.title}" has been saved to your bookmarks.`,
    )
  } else {
    showToast(
      'Bookmark Removed',
      `"${book.title}" was removed from your bookmarks.`,
    )
  }
}

function applySearchTag(query) {
  searchQuery.value = query
}

function openBarcode() {
  showToast(
    'Library Barcode Ready',
    'Displaying student identity LIB-8849-G11.',
  )
}

function focusSearch() {
  document.querySelector('#bookSearchInput')?.focus()
}

function handleKeyboardShortcut(event) {
  if (
    (event.metaKey || event.ctrlKey) &&
    event.key.toLowerCase() === 'k'
  ) {
    event.preventDefault()
    focusSearch()
  }
}

window.addEventListener('keydown', handleKeyboardShortcut)
</script>

<template>
  <div
    class="min-h-screen bg-[#DDF4E7] font-['Plus_Jakarta_Sans',sans-serif] text-[#0c1f17] antialiased"
  >
    <!-- HEADER -->
    <header
      class="fixed left-0 right-0 top-0 z-50 bg-white/90 shadow-[0_4px_18px_rgba(18,65,112,0.05)] backdrop-blur-md"
    >
      <div
        class="mx-auto flex h-20 w-full max-w-[1200px] items-center justify-between gap-4 px-4 md:px-5"
      >
        <!-- Logo + Navigation -->
        <div class="flex min-w-0 flex-1 items-center gap-5">
          <div class="flex shrink-0 items-center gap-2">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#26667f] text-white shadow-sm"
            >
              <span class="material-symbols-outlined">
                local_library
              </span>
            </div>

            <div class="hidden flex-col sm:flex">
              <span
                class="text-base font-semibold tracking-tight text-[#124170]"
              >
                Diginesh
              </span>

              <span
                class="text-[11px] font-semibold tracking-[0.3px] text-[#6F8B95]"
              >
                Digital Library System
              </span>
            </div>
          </div>

          <!-- Navigation -->
          <nav class="hidden items-center gap-1 lg:flex">
                <RouterLink
                    to="/vue"
                    class="rounded-xl px-3 py-2 text-[14px] font-semibold text-[#6F8B95] transition-colors hover:bg-[#E2F9EC] hover:text-[#124170]"
                >
                    Home
                </RouterLink>

                <RouterLink
                    to="/my-library"
                    class="rounded-xl px-3 py-2 text-[14px] font-semibold text-[#6F8B95] transition-colors hover:bg-[#E2F9EC] hover:text-[#124170]"
                >
                    My Library
                </RouterLink>

                <RouterLink
                    to="#"
                    class="rounded-xl px-3 py-2 text-[14px] font-semibold text-[#6F8B95]"
                >
                    Bookmarks
                </RouterLink>

                <RouterLink
                    to="#"
                    class="rounded-xl px-3 py-2 text-[14px] font-semibold text-[#6F8B95]"
                >
                    Scan
                </RouterLink>

                <RouterLink
                    to="#"
                    class="rounded-xl px-3 py-2 text-[14px] font-semibold text-[#6F8B95]"
                >
                    Account
                </RouterLink>
            </nav>
        </div>

        <!-- Right Header -->
        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
          <!-- Small Search -->
          <div
            class="hidden h-10 w-64 items-center rounded-xl bg-white px-3 shadow-[0_2px_8px_rgba(18,65,112,0.04)] md:flex lg:w-72"
          >
            <span class="material-symbols-outlined mr-2 text-[#26667F]">
              search
            </span>

            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search titles, authors..."
              class="w-full bg-transparent text-xs outline-none placeholder:text-[#6F8B95]"
            />

            <kbd
              class="rounded bg-[#E2F9EC] px-1.5 py-0.5 text-[11px] text-[#6F8B95]"
            >
              ⌘K
            </kbd>
          </div>

          <!-- Notification -->
          <button
            class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#26667F] shadow-[0_2px_8px_rgba(18,65,112,0.04)] transition hover:bg-[#E2F9EC]"
          >
            <span class="material-symbols-outlined">
              notifications
            </span>

            <span
              class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#67C090]"
            ></span>
          </button>

          <!-- Profile -->
          <div
            class="flex min-w-[150px] shrink-0 items-center gap-2 rounded-xl bg-[#F4FBF7] px-2.5 py-1.5"
          >
            <div
              class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#26667f] text-xs font-bold text-white"
            >
              AD
            </div>

            <div class="hidden min-w-0 flex-col sm:flex">
              <span
                class="whitespace-nowrap text-sm font-semibold leading-tight text-[#124170]"
              >
                Adel
              </span>

              <span
                class="mt-0.5 flex items-center gap-1 whitespace-nowrap text-[10px] font-semibold text-[#006c45]"
              >
                <span
                  class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#67C090]"
                ></span>

                Student • Active
              </span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- MAIN -->
    <main class="min-h-screen w-full bg-[#DDF4E7] pt-20">
      <!-- TOAST -->
      <Transition name="toast">
        <div
          v-if="toast.visible"
          class="fixed bottom-6 right-6 z-[100] flex max-w-sm items-center gap-3 rounded-xl bg-white px-5 py-4 shadow-xl"
        >
          <div
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#E8F8F0] text-[#006c45]"
          >
            <span class="material-symbols-outlined">
              check_circle
            </span>
          </div>

          <div class="flex flex-col">
            <span class="text-sm font-semibold text-[#124170]">
              {{ toast.title }}
            </span>

            <span class="text-xs text-[#6F8B95]">
              {{ toast.message }}
            </span>
          </div>
        </div>
      </Transition>

      <!-- HERO -->
      <section
        class="relative w-full overflow-hidden bg-white pb-8"
      >
        <div class="absolute inset-0">
          <div
            class="h-full w-full bg-cover bg-center opacity-20 blur-[0.5px]"
            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAasCunP0oda7v31O3b5pgT-bJ6rOpZfXHN45ccMacBmUKbqLoSIdWXgsgYoWLON_4bnYEyHIDgPrngiawxl2C_i57jULtcBt6u8QNjtPxM-FnNwSs3HPlycNzZCZy0KX7HG8aVtalnhfOVMaEmiSjBv1Tkj79OGC6xBD9m-zUS_QhEq-hTRdDeoki6gk0-fvJfsLoVGbEjAEfwo3iLoQg3WbCM52b9TPgmO963fGrJ42qrJURnQAt59Q')"
          ></div>

          <div
            class="absolute inset-0 bg-gradient-to-r from-white via-white/95 to-[#DDF4E7]/80"
          ></div>

          <div
            class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#DDF4E7] via-[#DDF4E7]/50 to-transparent"
          ></div>
        </div>

        <div
          class="relative z-10 mx-auto max-w-[1200px] px-4 pt-6 md:px-5"
        >
          <div
            class="mb-6 flex flex-col justify-between gap-6 lg:flex-row lg:items-end"
          >
            <!-- Welcome -->
            <div class="flex flex-col">
              <div class="flex items-baseline gap-3">
                <h1
                  class="text-[32px] font-bold tracking-tight text-[#124170]"
                >
                  Halo, Adel!

                  <span
                    class="inline-block transition-transform hover:rotate-12"
                  >
                    👋
                  </span>
                </h1>

                <p
                  class="text-xl font-normal text-[#26667F]"
                >
                  Mau baca buku apa hari ini?
                </p>
              </div>
            </div>

            <!-- Reading Status -->
            <div
              class="flex shrink-0 items-center gap-6 rounded-xl bg-white/90 px-5 py-4 shadow-sm backdrop-blur-md"
            >
              <div class="flex items-center gap-2">
                <div
                  class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E2F9EC] text-[#004e65]"
                >
                  <span class="material-symbols-outlined">
                    auto_stories
                  </span>
                </div>

                <div class="flex flex-col">
                  <span
                    class="text-[11px] font-semibold uppercase tracking-wider text-[#6F8B95]"
                  >
                    Active Loans
                  </span>

                  <span
                    class="text-sm font-semibold text-[#124170]"
                  >
                    2 Buku Dipinjam
                  </span>
                </div>
              </div>

              <div class="h-8 w-px bg-[#C2E2D4]"></div>

              <div class="flex items-center gap-2">
                <div
                  class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F8F0] text-[#006c45]"
                >
                  <span class="material-symbols-outlined">
                    event_upcoming
                  </span>
                </div>

                <div class="flex flex-col">
                  <span
                    class="text-[11px] font-semibold uppercase tracking-wider text-[#6F8B95]"
                  >
                    Next Due Date
                  </span>

                  <span
                    class="text-sm font-bold text-[#006c45]"
                  >
                    12 Nov (4 hari lagi)
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- BIG SEARCH -->
          <div
            class="flex w-full flex-col items-center gap-1 rounded-xl bg-white p-1 shadow-md md:flex-row"
          >
            <div
              class="relative flex h-12 w-full flex-1 items-center px-4"
            >
              <span
                class="material-symbols-outlined mr-3 text-xl text-[#26667F]"
              >
                search
              </span>

              <input
                id="bookSearchInput"
                v-model="searchQuery"
                type="text"
                placeholder="Cari berdasarkan judul, penulis, genre, atau kode buku..."
                class="w-full bg-transparent text-sm text-[#124170] outline-none placeholder:text-[#6F8B95]"
              />

              <button
                @click="focusSearch"
                class="hidden items-center gap-1 rounded bg-[#E2F9EC] px-2 py-1 text-[11px] text-[#6F8B95] md:flex"
              >
                <span>Ctrl</span>
                <span>K</span>
              </button>
            </div>

            <div
              class="flex w-full items-center justify-end gap-2 p-1 md:w-auto"
            >
              <button
                class="flex h-10 items-center gap-1 rounded-lg bg-[#E2F9EC] px-4 text-[13px] font-semibold text-[#26667F] transition hover:bg-[#D1E8DB]"
              >
                <span class="material-symbols-outlined text-lg">
                  filter_list
                </span>
                Filters
              </button>

              <button
                @click="focusSearch"
                class="flex h-10 items-center gap-1 rounded-lg bg-[#004e65] px-5 text-[13px] font-semibold text-white shadow-sm transition hover:bg-[#26667f] active:scale-95"
              >
                Find Books

                <span class="material-symbols-outlined text-lg">
                  arrow_forward
                </span>
              </button>
            </div>
          </div>

          <!-- POPULAR SEARCH -->
          <div
            class="mt-4 flex items-center gap-2 overflow-x-auto pb-1 text-[11px]"
          >
            <span
              class="whitespace-nowrap font-semibold text-[#124170]"
            >
              Pencarian Populer:
            </span>

            <button
              @click="applySearchTag('Carl Sagan')"
              class="whitespace-nowrap rounded-full bg-white/70 px-3 py-1 text-[#26667F] transition hover:bg-white"
            >
              Astrophysics
            </button>

            <button
              @click="applySearchTag('Dune')"
              class="whitespace-nowrap rounded-full bg-white/70 px-3 py-1 text-[#26667F] transition hover:bg-white"
            >
              Science Fiction
            </button>

            <button
              @click="applySearchTag('Mockingbird')"
              class="whitespace-nowrap rounded-full bg-white/70 px-3 py-1 text-[#26667F] transition hover:bg-white"
            >
              Pulitzer Winners
            </button>

            <button
              @click="applySearchTag('History')"
              class="whitespace-nowrap rounded-full bg-white/70 px-3 py-1 text-[#26667F] transition hover:bg-white"
            >
              Modern World History
            </button>
          </div>
        </div>
      </section>

      <!-- CONTENT -->
      <div
        class="mx-auto w-full max-w-[1200px] px-4 py-6 md:px-5"
      >
        <!-- FILTER BAR -->
        <div
          class="mb-6 flex flex-col justify-between gap-4 rounded-xl bg-white p-4 shadow-sm lg:flex-row lg:items-center"
        >
          <div
            class="flex items-center gap-1 overflow-x-auto py-1"
          >
            <button
              v-for="category in categories"
              :key="category.value"
              @click="selectedCategory = category.value"
              class="whitespace-nowrap rounded-full px-4 py-1.5 text-[13px] font-semibold transition-all"
              :class="
                selectedCategory === category.value
                  ? 'bg-[#26667f] text-white shadow-sm'
                  : 'bg-[#E2F9EC] text-[#26667F] hover:bg-[#D1E8DB]'
              "
            >
              {{ category.label }}
            </button>
          </div>

          <div class="flex shrink-0 items-center gap-3">
            <label
              class="flex cursor-pointer select-none items-center gap-2 rounded-full bg-[#E2F9EC] px-3 py-1.5"
            >
              <input
                v-model="availableOnly"
                type="checkbox"
                class="h-4 w-4 accent-[#006c45]"
              />

              <span
                class="text-[11px] font-semibold text-[#124170]"
              >
                Available Now Only
              </span>
            </label>

            <select
              v-model="sortBy"
              class="rounded-full bg-[#E2F9EC] py-1.5 pl-3 pr-8 text-[13px] font-semibold text-[#124170] outline-none"
            >
              <option value="popular">
                Most Popular
              </option>

              <option value="recent">
                Recently Added
              </option>

              <option value="author">
                Author (A-Z)
              </option>

              <option value="title">
                Title (A-Z)
              </option>
            </select>
          </div>
        </div>

        <!-- GRID + SIDEBAR -->
        <div
          class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12"
        >
          <!-- BOOK CATALOG -->
          <div
            class="flex flex-col gap-4 lg:col-span-9"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <h2
                  class="text-xl font-semibold text-[#124170]"
                >
                  Recommended for You
                </h2>

                <span
                  class="rounded-full bg-[#D7EEE1] px-2 py-0.5 text-[11px] font-semibold text-[#26667F]"
                >
                  {{ filteredBooks.length }} Titles
                </span>
              </div>

              <a
                href="#"
                class="hidden items-center gap-1 text-[13px] font-semibold text-[#26667F] hover:text-[#124170] sm:flex"
              >
                Explore all 240+ books

                <span
                  class="material-symbols-outlined text-sm"
                >
                  chevron_right
                </span>
              </a>
            </div>

            <!-- BOOK CARDS -->
            <div
              class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
              <div
                v-for="book in filteredBooks"
                :key="book.id"
                class="group relative flex flex-col justify-between overflow-hidden rounded-xl bg-white p-4 shadow-sm transition-all hover:shadow-md"
              >
                <div>
                  <!-- COVER -->
                  <div
                    class="relative mb-4 aspect-[3/4] w-full overflow-hidden rounded-lg bg-[#E2F9EC]"
                  >
                    <img
                      :src="book.image"
                      :alt="`Cover ${book.title}`"
                      class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />

                    <span
                      class="absolute left-2.5 top-2.5 rounded bg-white/90 px-2 py-0.5 text-[11px] font-semibold text-[#124170] shadow-sm backdrop-blur-sm"
                    >
                      {{ book.code }}
                    </span>

                    <!-- BOOKMARK -->
                    <button
                      @click="toggleBookmark(book)"
                      :title="
                        book.bookmarked
                          ? 'Remove bookmark'
                          : 'Save to bookmarks'
                      "
                      class="absolute right-2.5 top-2.5 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-[#26667F] shadow-sm backdrop-blur-sm transition hover:bg-white"
                    >
                      <span
                        class="material-symbols-outlined text-lg"
                        :class="
                          book.bookmarked
                            ? 'text-[#004e65]'
                            : ''
                        "
                        :style="{
                          fontVariationSettings:
                            book.bookmarked
                              ? '\'FILL\' 1'
                              : '\'FILL\' 0',
                        }"
                      >
                        bookmark
                      </span>
                    </button>
                  </div>

                  <!-- META -->
                  <div
                    class="mb-1 flex items-center justify-between gap-1"
                  >
                    <span
                      class="rounded-full px-2 py-0.5 text-[11px] font-bold"
                      :class="
                        book.category === 'classics'
                          ? 'bg-[#D3E3FF] text-[#1d4978]'
                          : 'bg-[#E8F8F0] text-[#006c45]'
                      "
                    >
                      {{ book.categoryLabel }}
                    </span>

                    <span
                      class="flex items-center gap-1 text-[11px]"
                      :class="
                        book.status === 'available'
                          ? 'text-[#6F8B95]'
                          : 'text-[#E55353]'
                      "
                    >
                      <span
                        class="h-1.5 w-1.5 rounded-full"
                        :class="
                          book.status === 'available'
                            ? 'bg-[#67C090]'
                            : 'bg-[#E55353]'
                        "
                      ></span>

                      {{ book.shelf }}
                    </span>
                  </div>

                  <h3
                    class="line-clamp-1 text-base font-semibold text-[#124170] transition-colors group-hover:text-[#26667F]"
                  >
                    {{ book.title }}
                  </h3>

                  <p class="mb-4 text-xs text-[#6F8B95]">
                    {{ book.author }}
                  </p>
                </div>

                <!-- CARD BOTTOM -->
                <div
                  class="mt-auto flex items-center justify-between gap-2 border-t border-[#E2F9EC] pt-3"
                >
                  <span
                    class="flex items-center gap-1 text-[11px] font-semibold"
                    :class="
                      book.status === 'available'
                        ? 'text-[#006c45]'
                        : 'text-[#E55353]'
                    "
                  >
                    <span
                      class="material-symbols-outlined text-sm"
                    >
                      {{
                        book.status === 'available'
                          ? 'check_circle'
                          : 'schedule'
                      }}
                    </span>

                    {{ book.statusLabel }}
                  </span>

                  <button
                    v-if="book.status === 'available'"
                    @click="reserveBook(book)"
                    class="h-9 rounded-lg bg-[#26667F] px-4 text-[13px] font-semibold text-white transition hover:bg-[#004e65] active:scale-95"
                  >
                    Reserve
                  </button>

                  <button
                    v-else
                    disabled
                    class="h-9 cursor-not-allowed rounded-lg bg-[#E2F9EC] px-3 text-[13px] font-semibold text-[#6F8B95]"
                  >
                    Tidak Tersedia
                  </button>
                </div>
              </div>
            </div>

            <!-- EMPTY -->
            <div
              v-if="filteredBooks.length === 0"
              class="rounded-xl bg-white p-10 text-center shadow-sm"
            >
              <span
                class="material-symbols-outlined text-4xl text-[#6F8B95]"
              >
                search_off
              </span>

              <h3
                class="mt-3 text-lg font-semibold text-[#124170]"
              >
                Buku tidak ditemukan
              </h3>

              <p class="mt-1 text-sm text-[#6F8B95]">
                Coba gunakan kata kunci atau kategori yang berbeda.
              </p>
            </div>

            <!-- CURATED LIST -->
            <div
              class="mt-4 flex flex-col items-center justify-between gap-4 rounded-xl bg-gradient-to-r from-white to-[#E2F9EC] p-6 shadow-sm md:flex-row"
            >
              <div class="flex items-center gap-4">
                <div
                  class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#004e65] text-white shadow-sm"
                >
                  <span
                    class="material-symbols-outlined text-2xl"
                  >
                    psychology
                  </span>
                </div>

                <div>
                  <span
                    class="text-[11px] font-semibold uppercase tracking-wider text-[#26667F]"
                  >
                    Teacher Curated Track
                  </span>

                  <h4
                    class="text-lg font-semibold text-[#124170]"
                  >
                    Advanced Literature & Scientific Research
                  </h4>

                  <p class="text-xs text-[#6F8B95]">
                    32 reference works selected for independent essays.
                  </p>
                </div>
              </div>

              <button
                class="h-11 whitespace-nowrap rounded-lg bg-[#004e65] px-5 text-[13px] font-semibold text-white shadow-sm transition hover:bg-[#26667f] active:scale-95"
              >
                View Reading List
              </button>
            </div>
          </div>

          <!-- SIDEBAR -->
          <aside
            class="flex w-full flex-col gap-4 lg:col-span-3"
          >
            <!-- SCANNER -->
            <div
              id="scan-section"
              class="flex scroll-mt-24 flex-col gap-4 rounded-xl bg-white p-5 shadow-sm"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-1">
                  <span
                    class="material-symbols-outlined text-[#26667F]"
                  >
                    qr_code_scanner
                  </span>

                  <span
                    class="text-sm font-semibold text-[#124170]"
                  >
                    Instant Self-Checkout
                  </span>
                </div>

                <span
                  class="rounded-full bg-[#E8F8F0] px-2 py-0.5 text-[11px] font-semibold text-[#006c45]"
                >
                  Fast Track
                </span>
              </div>

              <!-- BARCODE -->
              <div
                class="flex flex-col items-center justify-center rounded-lg bg-[#E2F9EC] p-4 text-center"
              >
                <div
                  class="flex h-12 w-full items-center justify-center overflow-hidden rounded bg-white px-4"
                >
                  <svg
                    class="h-8 w-full text-[#124170]"
                    viewBox="0 0 200 40"
                    fill="currentColor"
                  >
                    <rect height="40" width="4" x="0" />
                    <rect height="40" width="2" x="6" />
                    <rect height="40" width="6" x="12" />
                    <rect height="40" width="2" x="22" />
                    <rect height="40" width="4" x="28" />
                    <rect height="40" width="2" x="36" />
                    <rect height="40" width="6" x="42" />
                    <rect height="40" width="4" x="52" />
                    <rect height="40" width="2" x="60" />
                    <rect height="40" width="8" x="66" />
                    <rect height="40" width="2" x="78" />
                    <rect height="40" width="4" x="84" />
                    <rect height="40" width="6" x="92" />
                    <rect height="40" width="2" x="102" />
                    <rect height="40" width="4" x="108" />
                    <rect height="40" width="8" x="116" />
                    <rect height="40" width="2" x="128" />
                    <rect height="40" width="4" x="134" />
                    <rect height="40" width="6" x="142" />
                    <rect height="40" width="2" x="152" />
                    <rect height="40" width="4" x="158" />
                    <rect height="40" width="2" x="166" />
                    <rect height="40" width="8" x="172" />
                    <rect height="40" width="4" x="184" />
                    <rect height="40" width="4" x="192" />
                  </svg>
                </div>

                <span
                  class="mt-2 font-mono text-[11px] tracking-widest text-[#6F8B95]"
                >
                  LIB-8849-G11
                </span>
              </div>

              <div
                v-if="selectedBook"
                class="rounded-lg bg-[#E8F8F0] p-3"
              >
                <p
                  class="text-[10px] font-semibold uppercase tracking-wider text-[#006c45]"
                >
                  Buku yang dipilih
                </p>

                <p
                  class="mt-1 text-xs font-semibold text-[#124170]"
                >
                  {{ selectedBook.title }}
                </p>
              </div>

              <p
                class="text-xs leading-relaxed text-[#6F8B95]"
              >
                Gunakan kartu digitalmu untuk melakukan peminjaman dengan cepat melalui kiosk perpustakaan.
              </p>

              <button
                @click="openBarcode"
                class="flex h-10 w-full items-center justify-center gap-1 rounded-lg bg-[#E2F9EC] text-[13px] font-semibold text-[#124170] transition hover:bg-[#D1E8DB]"
              >
                <span
                  class="material-symbols-outlined text-lg"
                >
                  document_scanner
                </span>

                Open Mobile Barcode
              </button>
            </div>

            <!-- HOURS -->
            <div
              class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm"
            >
              <div class="flex items-center gap-1">
                <span
                  class="material-symbols-outlined text-[#26667F]"
                >
                  schedule
                </span>

                <span
                  class="text-sm font-semibold text-[#124170]"
                >
                  Hours & Study Hubs
                </span>
              </div>

              <div
                class="flex items-center justify-between rounded-lg bg-[#E8F8F0] p-2 text-[#006c45]"
              >
                <div class="flex items-center gap-2">
                  <span
                    class="h-2 w-2 animate-pulse rounded-full bg-[#67C090]"
                  ></span>

                  <span class="text-[13px] font-semibold">
                    Open Right Now
                  </span>
                </div>

                <span class="text-[11px] font-semibold">
                  Closes at 5:30 PM
                </span>
              </div>

              <div class="flex flex-col gap-1 text-xs">
                <div class="flex justify-between py-1">
                  <span class="text-[#6F8B95]">
                    Senin – Kamis
                  </span>

                  <span class="font-semibold">
                    07:30 – 17:30
                  </span>
                </div>

                <div class="flex justify-between py-1">
                  <span class="text-[#6F8B95]">
                    Jumat
                  </span>

                  <span class="font-semibold">
                    07:30 – 16:00
                  </span>
                </div>

                <div class="flex justify-between py-1">
                  <span class="text-[#6F8B95]">
                    Weekend
                  </span>

                  <span class="font-semibold">
                    Closed
                  </span>
                </div>
              </div>

              <div class="flex flex-col gap-1 pt-2">
                <div
                  class="flex items-center justify-between text-[11px] font-semibold"
                >
                  <span class="text-[#6F8B95]">
                    Study Pods Occupancy
                  </span>

                  <span class="text-[#124170]">
                    4 of 6 Free
                  </span>
                </div>

                <div
                  class="h-2 w-full overflow-hidden rounded-full bg-[#E2F9EC]"
                >
                  <div
                    class="h-full w-1/3 rounded-full bg-[#006c45]"
                  ></div>
                </div>
              </div>
            </div>

            <!-- RULES -->
            <div
              class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow-sm"
            >
              <div
                class="flex items-start justify-between"
              >
                <div class="flex items-center gap-2">
                  <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#E2F9EC] text-[#26667F]"
                  >
                    <span
                      class="material-symbols-outlined"
                    >
                      gavel
                    </span>
                  </div>

                  <div>
                    <h4
                      class="text-sm font-semibold text-[#124170]"
                    >
                      Borrowing Rules
                    </h4>

                    <p
                      class="text-[11px] text-[#6F8B95]"
                    >
                      School borrowing policy
                    </p>
                  </div>
                </div>

                <span
                  class="rounded-full bg-[#E2F9EC] px-2 py-0.5 text-[11px] font-semibold text-[#26667F]"
                >
                  Student
                </span>
              </div>

              <div
                class="flex flex-col gap-3 text-xs"
              >
                <div class="flex items-start gap-2">
                  <span
                    class="material-symbols-outlined text-lg text-[#26667F]"
                  >
                    menu_book
                  </span>

                  <div>
                    <span
                      class="font-semibold text-[#124170]"
                    >
                      Loan Limit
                    </span>

                    <p class="text-[#6F8B95]">
                      Maksimal 3 buku dalam satu waktu.
                    </p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <span
                    class="material-symbols-outlined text-lg text-[#26667F]"
                  >
                    calendar_today
                  </span>

                  <div>
                    <span
                      class="font-semibold text-[#124170]"
                    >
                      Loan Period
                    </span>

                    <p class="text-[#6F8B95]">
                      Masa peminjaman standar 14 hari.
                    </p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <span
                    class="material-symbols-outlined text-lg text-[#26667F]"
                  >
                    autorenew
                  </span>

                  <div>
                    <span
                      class="font-semibold text-[#124170]"
                    >
                      Renewals
                    </span>

                    <p class="text-[#6F8B95]">
                      1 kali perpanjangan jika tidak ada antrean.
                    </p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <span
                    class="material-symbols-outlined text-lg text-[#26667F]"
                  >
                    assignment_return
                  </span>

                  <div>
                    <span
                      class="font-semibold text-[#124170]"
                    >
                      Returns
                    </span>

                    <p class="text-[#6F8B95]">
                      Kembalikan ke drop box atau meja perpustakaan.
                    </p>
                  </div>
                </div>

                <div class="flex items-start gap-2">
                  <span
                    class="material-symbols-outlined text-lg text-[#26667F]"
                  >
                    verified_user
                  </span>

                  <div>
                    <span
                      class="font-semibold text-[#124170]"
                    >
                      Lost Items
                    </span>

                    <p class="text-[#6F8B95]">
                      Laporkan buku yang hilang atau rusak.
                    </p>
                  </div>
                </div>
              </div>

              <a
                href="#"
                class="flex items-center justify-between border-t border-[#C2E2D4] pt-3 text-[11px] font-semibold text-[#26667F] hover:text-[#124170]"
              >
                Full borrowing policy

                <span
                  class="material-symbols-outlined text-sm"
                >
                  arrow_forward
                </span>
              </a>
            </div>
          </aside>
        </div>
      </div>
    </main>

    <!-- FOOTER -->
    <footer
      class="w-full bg-white shadow-[0_-4px_18px_rgba(18,65,112,0.03)]"
    >
      <div
        class="mx-auto grid max-w-[1200px] grid-cols-1 gap-8 px-4 py-10 md:grid-cols-4 md:px-5"
      >
        <div class="flex flex-col gap-2">
          <div class="flex items-center gap-2">
            <div
              class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#26667F] text-white"
            >
              <span
                class="material-symbols-outlined text-lg"
              >
                local_library
              </span>
            </div>

            <span
              class="text-base font-semibold tracking-tight text-[#124170]"
            >
              Diginesh
            </span>
          </div>

          <p
            class="text-xs leading-relaxed text-[#6F8B95]"
          >
            Sistem perpustakaan digital untuk memudahkan siswa mencari, meminjam, dan mengelola buku.
          </p>
        </div>

        <div class="flex flex-col gap-2">
          <span
            class="text-sm font-semibold text-[#124170]"
          >
            Quick Navigation
          </span>

          <a
            href="#"
            class="text-xs text-[#6F8B95] hover:text-[#124170]"
          >
            My Library
          </a>

          <a
            href="#scan-section"
            class="text-xs text-[#6F8B95] hover:text-[#124170]"
          >
            Scan
          </a>

          <a
            href="#"
            class="text-xs text-[#6F8B95] hover:text-[#124170]"
          >
            Loan Policy
          </a>
        </div>

        <div class="flex flex-col gap-2">
          <span
            class="text-sm font-semibold text-[#124170]"
          >
            Opening Hours
          </span>

          <span class="text-xs text-[#0c1f17]">
            Senin – Kamis: 07:30 – 17:30
          </span>

          <span class="text-xs text-[#0c1f17]">
            Jumat: 07:30 – 16:00
          </span>

          <span class="text-xs text-[#6F8B95]">
            Sabtu & Minggu: Tutup
          </span>
        </div>

        <div class="flex flex-col gap-2">
          <span
            class="text-sm font-semibold text-[#124170]"
          >
            Student Help Desk
          </span>

          <span class="text-xs text-[#0c1f17]">
            Lokasi: Ruang Perpustakaan
          </span>

          <span class="text-xs text-[#0c1f17]">
            Email: library@diginesh.id
          </span>

          <span class="text-xs text-[#6F8B95]">
            Extension: x4280
          </span>
        </div>
      </div>

      <div class="w-full bg-[#E2F9EC] py-3">
        <div
          class="mx-auto flex max-w-[1200px] flex-col items-center justify-between gap-2 px-4 sm:flex-row md:px-5"
        >
          <span class="text-[11px] text-[#6F8B95]">
            © 2026 Diginesh Library System. All rights reserved.
          </span>

          <div class="flex gap-4">
            <a
              href="#"
              class="text-[11px] text-[#6F8B95] hover:text-[#124170]"
            >
              Privacy Policy
            </a>

            <a
              href="#"
              class="text-[11px] text-[#6F8B95] hover:text-[#124170]"
            >
              Accessibility
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition:
    opacity 0.3s ease,
    transform 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(24px);
}
</style>