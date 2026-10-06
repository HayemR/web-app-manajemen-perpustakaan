<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'

const activeTab = ref('borrowed')
const toastMessage = ref('')
const showToastState = ref(false)

let toastTimer

const borrowedBooks = ref([
  {
    id: 1,
    code: 'SCI-520.1',
    loanId: 'L-8942',
    title: 'Cosmos: A Personal Voyage',
    author: 'Carl Sagan',
    category: 'Science',
    borrowedOn: '12 Okt 2026',
    dueDate: '26 Okt 2026',
    status: 'due-soon',
    statusText: 'Jatuh tempo dalam 5 hari',
    returnStation: 'Meja Sirkulasi Perpustakaan',
    course: 'Astronomi & Fisika',
    image:
      'https://lh3.googleusercontent.com/aida-public/AB6AXuAOgxKinXrk18jmiPCr--Mgv5nUdiW-6WAYOVJO_68pKGyK8LiFaBbUY5ufBVGMNl9xugzqbDeODy7PsQUTZIm8ocBaPDYFvt3gGplV2sOqrvZReDW1qLdVKzH9Vw8OaC5F3KFd4SATvUBxJAij5AgWYAElLp-QU8VA31Vw2755j859pz1txOi01bg5oSNsz3iBh1N1HtWASoDOmpBgFpITVU1lobjrvrDeT1cEY-3SAgk38fprTrX2QA',
  },
  {
    id: 2,
    code: 'AST-523.8',
    loanId: 'L-9017',
    title: 'A Brief History of Time: From the Big Bang to Black Holes',
    author: 'Stephen Hawking',
    category: 'Physics / Cosmology',
    borrowedOn: '30 Sep 2026',
    dueDate: '14 Okt 2026',
    status: 'overdue',
    statusText: 'Terlambat 2 hari',
    returnStation: 'Meja Sirkulasi Perpustakaan',
    course: 'Fisika & Kosmologi',
    image: null,
  },
])

const loanHistory = ref([
  {
    id: 1,
    title: 'To Kill a Mockingbird',
    author: 'Harper Lee',
    category: 'Fiction',
    code: 'LIT-101',
    returnedDate: '10 Okt 2026',
    duration: '14 Hari',
    status: 'Tepat Waktu',
    course: 'Bahasa Inggris',
    image:
      'https://lh3.googleusercontent.com/aida-public/AB6AXuAqJHywXo533vqEBIR-vDv6CTcs1ATd3vuIAkdpqvHhkF_CsTpnYClwlc2MYqISwTUq00d-PntcUVrSDPHOmfcc8hd-c8j7VVs2TddlLkVhYJSzb11ygixYyoS_xej6IlkXhcN0kz8rCHqFZfxU_7f5JnyGsov8BTwZroVUMIIJtVGU7CW9Jzsu2zfgDTN1EMONi_8MaEhu1vH3Q6uiI1a2SYt15xphqOz7kaHb4iTbJDSOKT4Fg-Hixg',
  },
  {
    id: 2,
    title: '1984',
    author: 'George Orwell',
    category: 'Dystopian Literature',
    code: 'SOC-101',
    returnedDate: '28 Sep 2026',
    duration: '21 Hari',
    status: 'Tepat Waktu',
    course: 'Literatur',
    image: null,
  },
  {
    id: 3,
    title: 'The Great Gatsby',
    author: 'F. Scott Fitzgerald',
    category: 'Modernist Fiction',
    code: 'NOV-042',
    returnedDate: '14 Sep 2026',
    duration: '12 Hari',
    status: 'Tepat Waktu',
    course: null,
    image: null,
  },
])

const activeBookCount = computed(() => borrowedBooks.value.length)

const fineAmount = computed(() => {
  const overdueBooks = borrowedBooks.value.filter(
    (book) => book.status === 'overdue',
  )

  return overdueBooks.length * 0.25
})

const quotaPercentage = computed(() =>
  Math.round((activeBookCount.value / 3) * 100),
)

function showToast(message) {
  toastMessage.value = message
  showToastState.value = true

  clearTimeout(toastTimer)

  toastTimer = setTimeout(() => {
    showToastState.value = false
  }, 3200)
}

function switchTab(tab) {
  activeTab.value = tab

  if (tab === 'history') {
    showToast('Menampilkan riwayat peminjaman buku')
  }
}

function viewBookDetails(book) {
  showToast(`Membuka detail "${book.title}"`)
}

function returnInstructions(book) {
  showToast(`Informasi pengembalian untuk "${book.title}"`)
}

function reportIssue(book) {
  showToast(`Laporan masalah untuk "${book.title}" dibuka`)
}

function borrowAgain(book) {
  showToast(`"${book.title}" ditambahkan ke daftar buku yang ingin dipinjam`)
}

function handleKeyboardShortcut(event) {
  if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
    event.preventDefault()
    showToast('Fitur pencarian siap digunakan')
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyboardShortcut)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyboardShortcut)
  clearTimeout(toastTimer)
})
</script>

<template>
  <div class="min-h-screen bg-[#DDF4E7] text-[#0c1f17]">
    <!-- Toast -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="translate-y-[-120%] opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-300 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="translate-y-[-120%] opacity-0"
    >
      <div
        v-if="showToastState"
        class="fixed right-6 top-24 z-[60] flex items-center gap-2 rounded-xl bg-[#124170] px-4 py-3 text-white shadow-xl"
      >
        <span class="material-symbols-outlined text-[#67C090]">
          check_circle
        </span>

        <span class="text-sm font-semibold">
          {{ toastMessage }}
        </span>
      </div>
    </Transition>

    <!-- Header -->
    <header
      class="fixed left-0 right-0 top-0 z-50 bg-white/90 shadow-[0_4px_18px_rgba(18,65,112,0.05)] backdrop-blur-md"
    >
      <div
        class="mx-auto flex h-20 max-w-[1200px] items-center justify-between gap-4 px-4 md:px-5"
      >
        <!-- Logo -->
        <div class="flex min-w-0 items-center gap-5">
          <div class="flex shrink-0 items-center gap-2">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#26667F] text-white shadow-sm"
            >
              <span class="material-symbols-outlined">local_library</span>
            </div>

            <div class="hidden flex-col sm:flex">
              <span
                class="text-[16px] font-semibold tracking-tight text-[#124170]"
              >
                Diginesh
              </span>

              <span class="text-[11px] font-semibold text-[#6F8B95]">
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

        <!-- Header Right -->
        <div class="flex shrink-0 items-center gap-2 md:gap-3">
          <!-- Search -->
          <div
            class="hidden h-10 w-56 items-center rounded-xl bg-white px-3 shadow-[0_2px_8px_rgba(18,65,112,0.04)] md:flex lg:w-64"
          >
            <span class="material-symbols-outlined mr-2 text-[#26667F]">
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
            @click="showToast('Tidak ada notifikasi baru')"
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
                <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]" />
                Student • Active
              </span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main -->
    <main class="min-h-screen w-full bg-[#DDF4E7] pt-20">
      <div
        class="mx-auto flex w-full max-w-[1200px] flex-col gap-6 px-4 py-6 md:px-5"
      >
        <!-- Page Heading -->
        <section
          class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between"
        >
          <div class="flex flex-col gap-1">
            <span
              class="text-[11px] font-semibold uppercase tracking-wider text-[#006C45]"
            >
              Student Dashboard
            </span>

            <h1
              class="text-[28px] font-bold tracking-tight text-[#124170] md:text-[32px]"
            >
              My Library & Borrowed Items
            </h1>

            <p class="max-w-2xl text-sm leading-5 text-[#6F8B95]">
              Kelola buku yang sedang kamu pinjam dan lihat riwayat
              peminjamanmu.
            </p>
          </div>

          <!-- Tabs -->
          <div
            class="inline-flex self-start rounded-xl bg-white p-1 shadow-sm md:self-auto"
          >
            <button
              class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold transition-all"
              :class="
                activeTab === 'borrowed'
                  ? 'bg-[#26667F] text-white shadow-sm'
                  : 'text-[#6F8B95] hover:text-[#124170]'
              "
              @click="switchTab('borrowed')"
            >
              <span>Currently Borrowed</span>

              <span
                class="rounded-full px-1.5 py-0.5 text-[11px]"
                :class="
                  activeTab === 'borrowed'
                    ? 'bg-white/20 text-white'
                    : 'bg-[#E2F9EC] text-[#6F8B95]'
                "
              >
                {{ activeBookCount }}
              </span>
            </button>

            <button
              class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold transition-all"
              :class="
                activeTab === 'history'
                  ? 'bg-[#26667F] text-white shadow-sm'
                  : 'text-[#6F8B95] hover:text-[#124170]'
              "
              @click="switchTab('history')"
            >
              <span>Loan History</span>

              <span
                class="rounded-full px-1.5 py-0.5 text-[11px]"
                :class="
                  activeTab === 'history'
                    ? 'bg-white/20 text-white'
                    : 'bg-[#E2F9EC] text-[#6F8B95]'
                "
              >
                14
              </span>
            </button>
          </div>
        </section>

        <!-- Borrowed Tab -->
        <template v-if="activeTab === 'borrowed'">
          <!-- Warning Banner -->
          <section
            class="relative flex flex-col gap-4 overflow-hidden rounded-2xl bg-white p-5 shadow-sm lg:flex-row lg:items-center lg:justify-between"
          >
            <div
              class="absolute bottom-0 left-0 top-0 w-1.5 bg-[#E55353]"
            />

            <div class="flex items-start gap-3 pl-1 sm:items-center">
              <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#FDE8E8] text-[#E55353]"
              >
                <span class="material-symbols-outlined">
                  warning
                </span>
              </div>

              <div>
                <div class="flex flex-wrap items-center gap-2">
                  <span
                    class="text-sm font-semibold text-[#124170] md:text-base"
                  >
                    1 buku jatuh tempo dalam 5 hari • 1 buku terlambat 2 hari
                  </span>

                  <span
                    class="rounded-full bg-[#E55353] px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-white"
                  >
                    Action Needed
                  </span>
                </div>

                <p class="mt-0.5 text-xs leading-5 text-[#6F8B95]">
                  Segera kembalikan buku yang terlambat ke meja sirkulasi
                  perpustakaan.
                </p>
              </div>
            </div>

            <button
              class="shrink-0 self-start rounded-xl bg-[#E2F9EC] px-4 py-2 text-sm font-semibold text-[#124170] transition-colors hover:bg-[#D1E8DB] lg:self-auto"
              @click="returnInstructions()"
            >
              Lihat Informasi Pengembalian
            </button>
          </section>

          <!-- Metrics -->
          <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Quota -->
            <div
              class="flex flex-col justify-between rounded-2xl bg-white p-5 shadow-sm"
            >
              <div class="mb-2 flex items-center justify-between">
                <span
                  class="text-[11px] font-semibold uppercase tracking-wider text-[#6F8B95]"
                >
                  Borrowing Quota
                </span>

                <div
                  class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#E2F9EC] text-[#26667F]"
                >
                  <span class="material-symbols-outlined text-lg">
                    menu_book
                  </span>
                </div>
              </div>

              <div>
                <div class="flex items-baseline justify-between">
                  <span class="text-xl font-semibold text-[#124170]">
                    {{ activeBookCount }} of 3 Books
                  </span>

                  <span class="text-[11px] font-semibold text-[#26667F]">
                    {{ quotaPercentage }}% Active
                  </span>
                </div>

                <div
                  class="mt-2 h-2 w-full overflow-hidden rounded-full bg-[#E2F9EC]"
                >
                  <div
                    class="h-full rounded-full bg-[#26667F] transition-all"
                    :style="{ width: `${quotaPercentage}%` }"
                  />
                </div>
              </div>

              <span class="mt-2 text-xs text-[#6F8B95]">
                {{ 3 - activeBookCount }} slot peminjaman masih tersedia
              </span>
            </div>

            <!-- Balance -->
            <div
              class="flex flex-col justify-between rounded-2xl bg-white p-5 shadow-sm"
            >
              <div class="mb-2 flex items-center justify-between">
                <span
                  class="text-[11px] font-semibold uppercase tracking-wider text-[#6F8B95]"
                >
                  Account Balance
                </span>

                <div
                  class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#FDE8E8] text-[#E55353]"
                >
                  <span class="material-symbols-outlined text-lg">
                    payments
                  </span>
                </div>
              </div>

              <div>
                <span
                  class="text-xl font-semibold"
                  :class="
                    fineAmount > 0
                      ? 'text-[#E55353]'
                      : 'text-[#006C45]'
                  "
                >
                  Rp{{ (fineAmount * 16000).toLocaleString('id-ID') }}
                  Pending Fine
                </span>

                <span class="mt-1 block text-xs text-[#6F8B95]">
                  Denda dihitung berdasarkan keterlambatan pengembalian.
                </span>
              </div>

              <span class="mt-2 text-xs font-semibold text-[#6F8B95]">
                Hubungi petugas perpustakaan untuk informasi pembayaran.
              </span>
            </div>
          </section>

          <!-- Main Two Column -->
          <section
            class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12"
          >
            <!-- Loans -->
            <div class="flex flex-col gap-5 lg:col-span-8">
              <div
                class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center"
              >
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[#26667F]">
                    bookmark
                  </span>

                  <h2
                    class="text-xl font-semibold text-[#124170]"
                  >
                    Active Physical Book Loans
                  </h2>

                  <span
                    class="rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-[#26667F] shadow-sm"
                  >
                    {{ activeBookCount }} items
                  </span>
                </div>

                <div
                  class="rounded-xl bg-white px-3 py-2 text-xs font-semibold text-[#6F8B95] shadow-sm"
                >
                  Sort by: Due Date
                </div>
              </div>

              <!-- Loan Cards -->
              <article
                v-for="book in borrowedBooks"
                :key="book.id"
                class="relative flex flex-col gap-5 overflow-hidden rounded-2xl bg-white p-5 shadow-sm transition-shadow hover:shadow-md sm:flex-row"
                :class="
                  book.status === 'overdue'
                    ? 'border-l-4 border-[#E55353]'
                    : ''
                "
              >
                <!-- Cover -->
                <div
                  class="relative h-52 w-full shrink-0 overflow-hidden rounded-xl bg-[#E2F9EC] shadow-sm sm:w-36"
                >
                  <img
                    v-if="book.image"
                    :src="book.image"
                    :alt="book.title"
                    class="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                  />

                  <div
                    v-else
                    class="flex h-full w-full flex-col justify-end bg-gradient-to-tr from-[#124170] to-[#26667F] p-3 text-white"
                  >
                    <span
                      class="material-symbols-outlined mb-auto text-4xl opacity-40"
                    >
                      public
                    </span>

                    <span class="text-[11px] font-semibold uppercase opacity-75">
                      {{ book.category }}
                    </span>

                    <span class="text-sm font-bold leading-tight">
                      A Brief History of Time
                    </span>

                    <span class="text-xs opacity-80">
                      Stephen Hawking
                    </span>
                  </div>

                  <div
                    class="absolute left-2 top-2 rounded bg-white/90 px-1.5 py-0.5 font-mono text-[11px] font-semibold text-[#124170] shadow-sm backdrop-blur-sm"
                  >
                    {{ book.code }}
                  </div>
                </div>

                <!-- Information -->
                <div class="flex min-w-0 flex-1 flex-col justify-between">
                  <div>
                    <div
                      class="mb-1 flex flex-wrap items-center justify-between gap-2"
                    >
                      <span
                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold"
                        :class="
                          book.status === 'overdue'
                            ? 'bg-[#FDE8E8] text-[#E55353]'
                            : 'bg-[#E8F8F0] text-[#006C45]'
                        "
                      >
                        <span
                          class="h-1.5 w-1.5 rounded-full"
                          :class="
                            book.status === 'overdue'
                              ? 'bg-[#E55353]'
                              : 'bg-[#67C090]'
                          "
                        />

                        {{ book.statusText }} • Jatuh tempo
                        {{ book.dueDate }}
                      </span>

                      <span
                        v-if="book.status === 'overdue'"
                        class="inline-flex items-center gap-1 rounded bg-[#FDE8E8] px-2 py-0.5 text-[11px] font-semibold text-[#E55353]"
                      >
                        <span class="material-symbols-outlined text-sm">
                          attach_money
                        </span>

                        Denda Rp4.000
                      </span>

                      <span
                        v-else
                        class="font-mono text-[11px] text-[#6F8B95]"
                      >
                        Loan ID: #{{ book.loanId }}
                      </span>
                    </div>

                    <h3
                      class="truncate text-base font-semibold text-[#124170] transition-colors hover:text-[#26667F]"
                    >
                      {{ book.title }}
                    </h3>

                    <span class="mt-0.5 block text-xs text-[#6F8B95]">
                      By {{ book.author }} • {{ book.category }}
                    </span>

                    <!-- Metadata -->
                    <div
                      class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 rounded-xl p-3 text-[#6F8B95]"
                      :class="
                        book.status === 'overdue'
                          ? 'bg-[#FDE8E8]/40'
                          : 'bg-[#E2F9EC]'
                      "
                    >
                      <div class="flex flex-col">
                        <span class="text-[11px] font-semibold">
                          Borrowed On
                        </span>

                        <span class="text-xs text-[#0c1f17]">
                          {{ book.borrowedOn }}
                        </span>
                      </div>

                      <div class="flex flex-col">
                        <span class="text-[11px] font-semibold">
                          Return Station
                        </span>

                        <span class="text-xs text-[#0c1f17]">
                          {{ book.returnStation }}
                        </span>
                      </div>

                      <div class="flex flex-col">
                        <span class="text-[11px] font-semibold">
                          Status
                        </span>

                        <span
                          class="text-xs font-semibold"
                          :class="
                            book.status === 'overdue'
                              ? 'text-[#E55353]'
                              : 'text-[#006C45]'
                          "
                        >
                          {{
                            book.status === 'overdue'
                              ? 'Terlambat'
                              : 'Sedang Dipinjam'
                          }}
                        </span>
                      </div>

                      <div class="flex flex-col">
                        <span class="text-[11px] font-semibold">
                          Course Tie-in
                        </span>

                        <span class="text-xs text-[#0c1f17]">
                          {{ book.course }}
                        </span>
                      </div>
                    </div>

                    <div
                      class="mt-2 flex items-start gap-1.5"
                      :class="
                        book.status === 'overdue'
                          ? 'text-[#E55353]'
                          : 'text-[#6F8B95]'
                      "
                    >
                      <span class="material-symbols-outlined mt-0.5 text-base">
                        {{
                          book.status === 'overdue'
                            ? 'error'
                            : 'info'
                        }}
                      </span>

                      <span class="text-xs leading-5">
                        {{
                          book.status === 'overdue'
                            ? 'Buku sudah melewati tanggal pengembalian. Silakan segera kembalikan ke petugas perpustakaan.'
                            : 'Pastikan buku dikembalikan sebelum tanggal jatuh tempo.'
                        }}
                      </span>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div
                    class="mt-4 flex flex-wrap items-center gap-2 border-t border-[#E2F9EC] pt-3"
                  >
                    <button
                      class="rounded-xl bg-[#E2F9EC] px-3 py-2 text-sm font-semibold text-[#124170] transition-colors hover:bg-[#D1E8DB]"
                      @click="viewBookDetails(book)"
                    >
                      Book Details
                    </button>

                    <button
                      v-if="book.status === 'overdue'"
                      class="rounded-xl bg-[#E55353] px-3 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#BA1A1A]"
                      @click="returnInstructions(book)"
                    >
                      Return Instructions
                    </button>

                    <button
                      v-if="book.status === 'overdue'"
                      class="ml-auto text-xs font-semibold text-[#6F8B95] underline transition-colors hover:text-[#124170]"
                      @click="reportIssue(book)"
                    >
                      Report Issue / Lost Book
                    </button>
                  </div>
                </div>
              </article>
            </div>

            <!-- Sidebar -->
            <aside class="flex flex-col gap-5 lg:col-span-4">
              <!-- Student Card -->
              <div
                class="flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#26667F]">
                      badge
                    </span>

                    <span
                      class="text-[11px] font-semibold uppercase tracking-wider text-[#6F8B95]"
                    >
                      Student Card
                    </span>
                  </div>

                  <span
                    class="inline-flex items-center gap-1 rounded-full bg-[#E8F8F0] px-2 py-0.5 text-[11px] font-semibold text-[#006C45]"
                  >
                    <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]" />
                    Active
                  </span>
                </div>

                <div class="flex items-center gap-3">
                  <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#26667F] text-lg font-bold text-white shadow-sm"
                  >
                    AD
                  </div>

                  <div class="flex min-w-0 flex-col">
                    <span
                      class="text-xl font-semibold leading-tight text-[#124170]"
                    >
                      Adel
                    </span>

                    <span class="text-xs text-[#6F8B95]">
                      Student
                    </span>

                    <span
                      class="mt-0.5 font-mono text-xs text-[#26667F]"
                    >
                      LIB-8849
                    </span>
                  </div>
                </div>

                <div
                  class="rounded-xl bg-[#E2F9EC] p-3 text-xs leading-5 text-[#6F8B95]"
                >
                  <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-base text-[#006C45]">
                      info
                    </span>

                    <span>
                      Kartu siswa digunakan untuk proses peminjaman di
                      perpustakaan. Peminjaman hanya dapat dilakukan setelah
                      siswa berada di area perpustakaan dan melakukan scan
                      kartu.
                    </span>
                  </div>
                </div>
              </div>

              <!-- Borrowing Policies -->
              <div
                id="rules-section"
                class="flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm"
              >
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[#124170]">
                    gavel
                  </span>

                  <h3 class="text-base font-semibold text-[#124170]">
                    Borrowing Policies
                  </h3>
                </div>

                <ul class="flex flex-col gap-3">
                  <li class="flex items-start gap-2">
                    <div
                      class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#E2F9EC] text-[#26667F]"
                    >
                      <span class="material-symbols-outlined text-sm">
                        calendar_today
                      </span>
                    </div>

                    <div>
                      <span class="block text-sm font-semibold text-[#124170]">
                        14-Day Lending Period
                      </span>

                      <span class="text-xs leading-5 text-[#6F8B95]">
                        Buku dapat dipinjam selama maksimal 14 hari.
                      </span>
                    </div>
                  </li>

                  <li class="flex items-start gap-2">
                    <div
                      class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#E2F9EC] text-[#26667F]"
                    >
                      <span class="material-symbols-outlined text-sm">
                        format_list_numbered
                      </span>
                    </div>

                    <div>
                      <span class="block text-sm font-semibold text-[#124170]">
                        3 Physical Books Max
                      </span>

                      <span class="text-xs leading-5 text-[#6F8B95]">
                        Maksimal 3 buku dapat dipinjam secara bersamaan.
                      </span>
                    </div>
                  </li>

                  <li class="flex items-start gap-2">
                    <div
                      class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#E2F9EC] text-[#26667F]"
                    >
                      <span class="material-symbols-outlined text-sm">
                        location_on
                      </span>
                    </div>

                    <div>
                      <span class="block text-sm font-semibold text-[#124170]">
                        Peminjaman di Perpustakaan
                      </span>

                      <span class="text-xs leading-5 text-[#6F8B95]">
                        Peminjaman harus dilakukan langsung di perpustakaan
                        menggunakan scan kartu siswa.
                      </span>
                    </div>
                  </li>

                  <li class="flex items-start gap-2">
                    <div
                      class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#FDE8E8] text-[#E55353]"
                    >
                      <span class="material-symbols-outlined text-sm">
                        monetization_on
                      </span>
                    </div>

                    <div>
                      <span class="block text-sm font-semibold text-[#E55353]">
                        Overdue Fees
                      </span>

                      <span class="text-xs leading-5 text-[#6F8B95]">
                        Keterlambatan dapat dikenakan denda sesuai kebijakan
                        perpustakaan.
                      </span>
                    </div>
                  </li>
                </ul>

                <div
                  class="flex items-center justify-between rounded-xl bg-[#E2F9EC] p-3"
                >
                  <span class="text-xs font-semibold text-[#124170]">
                    Panduan Peminjaman
                  </span>

                  <button
                    class="text-[#26667F] transition-colors hover:text-[#124170]"
                    @click="showToast('Panduan peminjaman akan tersedia')"
                  >
                    <span class="material-symbols-outlined text-lg">
                      open_in_new
                    </span>
                  </button>
                </div>
              </div>
            </aside>
          </section>

          <!-- Recently Returned -->
          <section
            class="flex flex-col gap-5 rounded-2xl bg-white p-5 shadow-sm md:p-6"
          >
            <div
              class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center"
            >
              <div>
                <h3 class="text-xl font-semibold text-[#124170]">
                  Recently Returned Books
                </h3>

                <p class="text-xs text-[#6F8B95]">
                  3 buku terakhir yang sudah kamu kembalikan.
                </p>
              </div>

              <button
                class="inline-flex items-center gap-1 text-sm font-semibold text-[#26667F] transition-colors hover:text-[#124170]"
                @click="switchTab('history')"
              >
                View Full Loan History

                <span class="material-symbols-outlined text-base">
                  arrow_forward
                </span>
              </button>
            </div>

            <div class="flex flex-col gap-2">
              <div
                v-for="book in loanHistory.slice(0, 3)"
                :key="book.id"
                class="flex flex-col justify-between gap-3 rounded-xl bg-[#E2F9EC] p-3 transition-colors hover:bg-[#D1E8DB] sm:flex-row sm:items-center"
              >
                <div class="flex items-center gap-3">
                  <div
                    class="flex h-16 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white shadow-sm"
                  >
                    <img
                      v-if="book.image"
                      :src="book.image"
                      :alt="book.title"
                      class="h-full w-full object-cover"
                    />

                    <span
                      v-else-if="book.title === '1984'"
                      class="font-mono text-sm font-bold text-[#124170]"
                    >
                      1984
                    </span>

                    <span
                      v-else
                      class="material-symbols-outlined text-2xl text-[#26667F]"
                    >
                      auto_stories
                    </span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm font-semibold text-[#124170]">
                      {{ book.title }}
                    </span>

                    <span class="text-xs text-[#6F8B95]">
                      {{ book.author }} • {{ book.category }}
                    </span>

                    <span
                      class="mt-0.5 flex items-center gap-1 text-[11px] font-semibold text-[#006C45]"
                    >
                      <span class="material-symbols-outlined text-sm">
                        check_circle
                      </span>

                      Returned {{ book.returnedDate }} • {{ book.status }}
                    </span>
                  </div>
                </div>

                <div class="flex items-center gap-2 self-end sm:self-auto">
                  <span
                    class="rounded-lg bg-white px-2.5 py-1 font-mono text-[11px] font-semibold text-[#124170] shadow-sm"
                  >
                    {{ book.duration }}
                  </span>

                  <button
                    class="rounded-lg p-1.5 text-[#6F8B95] transition-colors hover:bg-white hover:text-[#124170]"
                    title="Borrow again"
                    @click="borrowAgain(book)"
                  >
                    <span class="material-symbols-outlined text-lg">
                      replay
                    </span>
                  </button>
                </div>
              </div>
            </div>
          </section>
        </template>

        <!-- History Tab -->
        <template v-else>
          <section
            class="flex flex-col gap-5 rounded-2xl bg-white p-5 shadow-sm md:p-6"
          >
            <div>
              <span
                class="text-[11px] font-semibold uppercase tracking-wider text-[#006C45]"
              >
                Loan History
              </span>

              <h2
                class="mt-1 text-2xl font-semibold tracking-tight text-[#124170]"
              >
                Your Reading History
              </h2>

              <p class="mt-1 text-sm text-[#6F8B95]">
                Daftar buku yang pernah kamu pinjam dan kembalikan.
              </p>
            </div>

            <div class="flex flex-col gap-2">
              <div
                v-for="book in loanHistory"
                :key="book.id"
                class="flex flex-col justify-between gap-3 rounded-xl bg-[#E2F9EC] p-4 transition-colors hover:bg-[#D1E8DB] sm:flex-row sm:items-center"
              >
                <div class="flex items-center gap-3">
                  <div
                    class="flex h-20 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white shadow-sm"
                  >
                    <img
                      v-if="book.image"
                      :src="book.image"
                      :alt="book.title"
                      class="h-full w-full object-cover"
                    />

                    <span
                      v-else-if="book.title === '1984'"
                      class="font-mono text-sm font-bold text-[#124170]"
                    >
                      1984
                    </span>

                    <span
                      v-else
                      class="material-symbols-outlined text-2xl text-[#26667F]"
                    >
                      auto_stories
                    </span>
                  </div>

                  <div>
                    <h3 class="text-base font-semibold text-[#124170]">
                      {{ book.title }}
                    </h3>

                    <p class="text-xs text-[#6F8B95]">
                      {{ book.author }} • {{ book.category }}
                    </p>

                    <p class="mt-1 text-xs font-semibold text-[#006C45]">
                      Returned {{ book.returnedDate }} • {{ book.status }}
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-3 self-end sm:self-auto">
                  <span
                    class="rounded-lg bg-white px-3 py-1.5 font-mono text-xs font-semibold text-[#124170] shadow-sm"
                  >
                    {{ book.duration }}
                  </span>

                  <button
                    class="rounded-xl bg-white px-3 py-2 text-xs font-semibold text-[#26667F] shadow-sm transition-colors hover:bg-[#F4FBF7]"
                    @click="borrowAgain(book)"
                  >
                    Borrow Again
                  </button>
                </div>
              </div>
            </div>
          </section>
        </template>
      </div>
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white shadow-[0_-4px_18px_rgba(18,65,112,0.03)]">
      <div
        class="mx-auto grid max-w-[1200px] grid-cols-1 gap-6 px-4 py-8 md:grid-cols-4 md:px-5"
      >
        <!-- Brand -->
        <div class="flex flex-col gap-2">
          <div class="flex items-center gap-2">
            <div
              class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#26667F] text-white"
            >
              <span class="material-symbols-outlined text-lg">
                local_library
              </span>
            </div>

            <span class="text-base font-semibold tracking-tight text-[#124170]">
              Diginesh
            </span>
          </div>

          <p class="text-xs leading-5 text-[#6F8B95]">
            Platform perpustakaan digital untuk membantu siswa menemukan,
            meminjam, dan mengelola buku dengan lebih mudah.
          </p>
        </div>

        <!-- Quick Navigation -->
        <div class="flex flex-col gap-2">
          <span class="text-sm font-semibold text-[#124170]">
            Quick Navigation
          </span>

          <a
            href="/vue"
            class="text-xs text-[#6F8B95] transition-colors hover:text-[#124170]"
          >
            Home
          </a>

          <a
            href="#"
            class="text-xs text-[#6F8B95] transition-colors hover:text-[#124170]"
          >
            My Library
          </a>

          <a
            href="#"
            class="text-xs text-[#6F8B95] transition-colors hover:text-[#124170]"
          >
            Bookmarks
          </a>

          <a
            href="#"
            class="text-xs text-[#6F8B95] transition-colors hover:text-[#124170]"
          >
            Scan
          </a>
        </div>

        <!-- Opening Hours -->
        <div class="flex flex-col gap-2">
          <span class="text-sm font-semibold text-[#124170]">
            Opening Hours
          </span>

          <div class="flex flex-col gap-0.5">
            <span class="text-xs text-[#0c1f17]">
              Senin – Kamis: 07.30 – 15.30
            </span>

            <span class="text-xs text-[#0c1f17]">
              Jumat: 07.30 – 14.30
            </span>

            <span class="text-xs text-[#6F8B95]">
              Sabtu & Minggu: Tutup
            </span>
          </div>
        </div>

        <!-- Help -->
        <div class="flex flex-col gap-2">
          <span class="text-sm font-semibold text-[#124170]">
            Student Help Desk
          </span>

          <div class="flex flex-col gap-0.5">
            <span class="text-xs text-[#0c1f17]">
              Lokasi: Perpustakaan Sekolah
            </span>

            <span class="text-xs text-[#0c1f17]">
              Hubungi petugas perpustakaan
            </span>

            <span class="text-xs text-[#6F8B95]">
              Bantuan peminjaman & pengembalian
            </span>
          </div>
        </div>
      </div>

      <div class="w-full bg-[#E2F9EC] py-3">
        <div
          class="mx-auto flex max-w-[1200px] flex-col items-center justify-between gap-2 px-4 sm:flex-row md:px-5"
        >
          <span class="text-[11px] text-[#6F8B95]">
            © 2026 Diginesh School Library. All rights reserved.
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
              Student Code of Conduct
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