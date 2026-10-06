<script setup>
import { computed, ref } from 'vue'
import AdminSidebar from '../../components/adminsidebar.vue'

// =====================================================
// STATE
// =====================================================

const searchQuery = ref('')
const selectedClass = ref('')
const selectedStatus = ref('')
const activeTab = ref('all')

const currentPage = ref(1)
const rowsPerPage = ref(8)

const selectedBorrower = ref(null)
const showDetailModal = ref(false)

const showIssueModal = ref(false)
const issueMessage = ref('')

const showToast = ref(false)
const toastMessage = ref('')

// =====================================================
// DATA BORROWERS
// =====================================================

const borrowers = ref([
  {
    id: 1,
    initials: 'AM',
    name: 'Alex Morgan',
    email: 'alex.m@school.edu',
    nis: 'NIS-2023-04921',
    class: '10-A',
    book: 'Cosmos: A Personal Voyage',
    catalogCode: 'LIB-AST-520.1',
    borrowDate: 'Nov 10, 2024',
    dueDate: 'Nov 24, 2024',
    status: 'overdue',
    overdueDays: 4,
    avatarClass: 'bg-[#ffdad6] text-[#93000a]',
  },

  {
    id: 2,
    initials: 'SC',
    name: 'Sophia Chen',
    email: 'sophia.c@school.edu',
    nis: 'NIS-2023-01824',
    class: '11-Sci 2',
    book: 'Principles of Physics (Vol. 1)',
    catalogCode: 'LIB-SCI-530.1',
    borrowDate: 'Nov 18, 2024',
    dueDate: 'Dec 02, 2024',
    status: 'borrowed',
    overdueDays: 0,
    avatarClass: 'bg-[#a8e2ff] text-[#26667f]',
  },

  {
    id: 3,
    initials: 'MV',
    name: 'Marcus Vance',
    email: 'marcus.v@school.edu',
    nis: 'NIS-2022-09312',
    class: '12-Lit',
    book: 'The Great Gatsby (F. Scott Fitzgerald)',
    catalogCode: 'LIB-LIT-813.2',
    borrowDate: 'Nov 08, 2024',
    dueDate: 'Nov 22, 2024',
    status: 'overdue',
    overdueDays: 6,
    avatarClass: 'bg-[#ffdad6] text-[#93000a]',
  },

  {
    id: 4,
    initials: 'EW',
    name: 'Emma Watson',
    email: 'emma.w@school.edu',
    nis: 'NIS-2024-03115',
    class: '10-B',
    book: 'Molecular Biology of the Cell',
    catalogCode: 'LIB-BIO-571.6',
    borrowDate: 'Nov 20, 2024',
    dueDate: 'Dec 04, 2024',
    status: 'borrowed',
    overdueDays: 0,
    avatarClass: 'bg-[#d7eee1] text-[#124170]',
  },

  {
    id: 5,
    initials: 'LH',
    name: 'Liam Henderson',
    email: 'liam.h@school.edu',
    nis: 'NIS-2023-07842',
    class: '11-A',
    book: 'To Kill a Mockingbird',
    catalogCode: 'LIB-LIT-813.1',
    borrowDate: 'Nov 05, 2024',
    dueDate: 'Nov 19, 2024',
    status: 'returned',
    overdueDays: 0,
    avatarClass: 'bg-[#d1e8db] text-[#42474f]',
  },

  {
    id: 6,
    initials: 'OT',
    name: 'Olivia Taylor',
    email: 'olivia.t@school.edu',
    nis: 'NIS-2024-05190',
    class: '10-A',
    book: '1984 (Centennial Edition)',
    catalogCode: 'LIB-FIC-823.9',
    borrowDate: 'Nov 22, 2024',
    dueDate: 'Dec 06, 2024',
    status: 'borrowed',
    overdueDays: 0,
    avatarClass: 'bg-[#9bf5c1] text-[#00311d]',
  },

  {
    id: 7,
    initials: 'DK',
    name: 'Daniel Kim',
    email: 'daniel.k@school.edu',
    nis: 'NIS-2022-04419',
    class: '12-Sci 1',
    book: 'Campbell Biology (11th Ed.)',
    catalogCode: 'LIB-BIO-570.0',
    borrowDate: 'Oct 25, 2024',
    dueDate: 'Nov 08, 2024',
    status: 'returned',
    overdueDays: 0,
    avatarClass: 'bg-[#d1e8db] text-[#42474f]',
  },

  {
    id: 8,
    initials: 'MP',
    name: 'Maya Patel',
    email: 'maya.p@school.edu',
    nis: 'NIS-2023-06231',
    class: '11-Arts',
    book: 'Astrophysics for People in a Hurry',
    catalogCode: 'LIB-AST-520.0',
    borrowDate: 'Nov 25, 2024',
    dueDate: 'Dec 09, 2024',
    status: 'borrowed',
    overdueDays: 0,
    avatarClass: 'bg-[#a8e2ff] text-[#26667f]',
  },
])

// =====================================================
// COMPUTED DATA
// =====================================================

const filteredBorrowers = computed(() => {
  let result = borrowers.value

  // Tab
  if (activeTab.value !== 'all') {
    result = result.filter(
      (borrower) => borrower.status === activeTab.value
    )
  }

  // Search
  const query = searchQuery.value.trim().toLowerCase()

  if (query) {
    result = result.filter((borrower) => {
      return (
        borrower.name.toLowerCase().includes(query) ||
        borrower.email.toLowerCase().includes(query) ||
        borrower.nis.toLowerCase().includes(query) ||
        borrower.book.toLowerCase().includes(query) ||
        borrower.catalogCode.toLowerCase().includes(query)
      )
    })
  }

  // Class
  if (selectedClass.value) {
    result = result.filter(
      (borrower) =>
        borrower.class === selectedClass.value
    )
  }

  // Status
  if (selectedStatus.value) {
    result = result.filter(
      (borrower) =>
        borrower.status === selectedStatus.value
    )
  }

  return result
})

const totalPages = computed(() => {
  return Math.max(
    1,
    Math.ceil(
      filteredBorrowers.value.length /
        rowsPerPage.value
    )
  )
})

const paginatedBorrowers = computed(() => {
  const start =
    (currentPage.value - 1) *
    rowsPerPage.value

  const end =
    start + rowsPerPage.value

  return filteredBorrowers.value.slice(
    start,
    end
  )
})

const borrowedCount = computed(() => {
  return borrowers.value.filter(
    (item) => item.status === 'borrowed'
  ).length
})

const overdueCount = computed(() => {
  return borrowers.value.filter(
    (item) => item.status === 'overdue'
  ).length
})

const returnedCount = computed(() => {
  return borrowers.value.filter(
    (item) => item.status === 'returned'
  ).length
})

// =====================================================
// PAGINATION
// =====================================================

function changePage(page) {
  if (
    page < 1 ||
    page > totalPages.value
  ) {
    return
  }

  currentPage.value = page
}

function nextPage() {
  if (
    currentPage.value <
    totalPages.value
  ) {
    currentPage.value++
  }
}

function previousPage() {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

function changeRowsPerPage() {
  currentPage.value = 1
}

// =====================================================
// FILTER
// =====================================================

function setTab(tab) {
  activeTab.value = tab
  currentPage.value = 1
}

function applyFilter() {
  currentPage.value = 1
}

// =====================================================
// BORROWER DETAIL
// =====================================================

function openBorrowerDetail(borrower) {
  selectedBorrower.value = borrower
  showDetailModal.value = true
}

function closeBorrowerDetail() {
  showDetailModal.value = false
  selectedBorrower.value = null
}

// =====================================================
// ISSUE BOOK
// =====================================================

function openIssueBook() {
  issueMessage.value = ''
  showIssueModal.value = true
}

function closeIssueBook() {
  showIssueModal.value = false
}

function confirmIssueBook() {
  showIssueModal.value = false

  showToastMessage(
    'Halaman peminjaman buku siap digunakan.'
  )
}

// =====================================================
// EXPORT
// =====================================================

function exportCSV() {
  const rows = filteredBorrowers.value

  const header = [
    'Nama',
    'Email',
    'NIS',
    'Kelas',
    'Buku',
    'Kode Katalog',
    'Tanggal Pinjam',
    'Jatuh Tempo',
    'Status',
  ]

  const csvRows = rows.map((borrower) => [
    borrower.name,
    borrower.email,
    borrower.nis,
    borrower.class,
    borrower.book,
    borrower.catalogCode,
    borrower.borrowDate,
    borrower.dueDate,
    borrower.status,
  ])

  const csvContent = [
    header,
    ...csvRows,
  ]
    .map((row) =>
      row
        .map((value) =>
          `"${String(value).replaceAll('"', '""')}"`
        )
        .join(',')
    )
    .join('\n')

  const blob = new Blob(
    [csvContent],
    { type: 'text/csv;charset=utf-8;' }
  )

  const url = URL.createObjectURL(blob)

  const link = document.createElement('a')

  link.href = url
  link.download = 'borrowers.csv'

  link.click()

  URL.revokeObjectURL(url)

  showToastMessage(
    'Data borrowers berhasil diekspor.'
  )
}

// =====================================================
// PRINT
// =====================================================

function printSheet() {
  window.print()
}

// =====================================================
// OVERDUE DIGEST
// =====================================================

function dispatchReminders() {
  showToastMessage(
    `Reminder dikirim ke ${overdueCount.value} siswa dengan keterlambatan.`
  )
}

// =====================================================
// TOAST
// =====================================================

let toastTimer

function showToastMessage(message) {
  toastMessage.value = message
  showToast.value = true

  clearTimeout(toastTimer)

  toastTimer = setTimeout(() => {
    showToast.value = false
  }, 3000)
}
</script>

<template>
  <div
    class="min-h-screen bg-[#e8fff2] font-['Plus_Jakarta_Sans',sans-serif] text-[#0c1f17]"
  >

    <!-- ================================================ -->
    <!-- SIDEBAR -->
    <!-- ================================================ -->

    <AdminSidebar />

    <!-- ================================================ -->
    <!-- MAIN -->
    <!-- ================================================ -->

    <div class="min-h-screen pl-64">

      <!-- HEADER -->
      <header
        class="fixed left-64 right-0 top-0 z-40 h-16 border-b border-[#d1e8db] bg-white/90 px-8 shadow-sm backdrop-blur-xl"
      >

        <div
          class="flex h-16 items-center justify-between gap-6"
        >

          <!-- Breadcrumb -->
          <div
            class="flex items-center gap-2 text-[#42474f]"
          >

            <span
              class="material-symbols-outlined text-[20px] text-[#25657e]"
            >
              group
            </span>

            <span class="text-xs text-[#737780]">
              /
            </span>

            <span class="text-sm font-semibold text-[#0c1f17]">
              Borrowers
            </span>

          </div>

          <!-- Header right -->
          <div class="flex items-center gap-6">

            <!-- Search -->
            <div class="relative hidden w-80 lg:block">

              <span
                class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-[#737780]"
              >
                search
              </span>

              <input
                v-model="searchQuery"
                @input="applyFilter"
                type="text"
                placeholder="Search catalog, ISBN, student ID..."
                class="w-full rounded-xl bg-[#e2f9ec] py-2.5 pl-10 pr-4 text-xs text-[#0c1f17] outline-none placeholder:text-[#737780] focus:bg-white focus:ring-2 focus:ring-[#25657e]/30"
              />

            </div>

            <!-- Notification -->
            <button
              type="button"
              class="relative rounded-xl p-2 text-[#42474f] transition hover:bg-[#dcf3e6]"
            >

              <span class="material-symbols-outlined text-[20px]">
                notifications
              </span>

              <span
                class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#ba1a1a]"
              ></span>

            </button>

            <div class="h-6 w-px bg-[#c3c6d0]"></div>

            <!-- Profile -->
            <div class="flex items-center gap-2">

              <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-[#002b51] text-white"
              >
                <span class="material-symbols-outlined text-[18px]">
                  person
                </span>
              </div>

              <div class="hidden text-left md:flex md:flex-col">

                <span class="text-xs font-semibold text-[#0c1f17]">
                  Sarah Jenkins
                </span>

                <span class="text-[10px] text-[#737780]">
                  Head Librarian
                </span>

              </div>

            </div>

          </div>

        </div>

      </header>

      <!-- ============================================== -->
      <!-- PAGE -->
      <!-- ============================================== -->

      <main
        class="min-h-screen bg-[#e8fff2] px-8 pb-10 pt-24"
      >

        <div class="flex flex-col gap-6">

          <!-- PAGE HEADER -->
          <section
            class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
          >

            <div class="max-w-2xl">

              <div
                class="mb-1 flex items-center gap-2 text-[#25657e]"
              >

                <span
                  class="material-symbols-outlined text-[18px]"
                >
                  verified_user
                </span>

                <span
                  class="text-[10px] font-bold uppercase tracking-wider"
                >
                  Active Circulation Register
                </span>

              </div>

              <h1
                class="text-[32px] font-bold tracking-tight text-[#002b51]"
              >
                Borrowers
              </h1>

              <p
                class="mt-2 text-sm leading-relaxed text-[#42474f]"
              >
                Monitor data siswa, peminjaman buku,
                pengembalian, keterlambatan, dan riwayat
                sirkulasi perpustakaan.
              </p>

            </div>

            <!-- ACTION -->
            <div
              class="flex items-center gap-2 rounded-xl bg-white p-1 shadow-sm"
            >

              <div
                class="flex items-center gap-2 rounded-lg bg-[#e2f9ec] px-3 py-2"
              >

                <span
                  class="h-2.5 w-2.5 animate-pulse rounded-full bg-[#7fd9a7]"
                ></span>

                <span class="text-xs font-semibold text-[#0c1f17]">
                  Circulation Sync:
                  <strong class="text-[#002b51]">
                    Live
                  </strong>
                </span>

              </div>

              <button
                type="button"
                @click="openIssueBook"
                class="flex items-center gap-2 rounded-lg bg-[#002b51] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#124170]"
              >

                <span class="material-symbols-outlined text-[18px]">
                  add_circle
                </span>

                Issue Book

              </button>

            </div>

          </section>

          <!-- ========================================== -->
          <!-- METRICS -->
          <!-- ========================================== -->

          <section
            class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4"
          >

            <!-- Active -->
            <div
              class="group relative overflow-hidden rounded-xl bg-white p-5 shadow-sm transition hover:shadow-md"
            >

              <div
                class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-[#a8e2ff]/20 transition-transform group-hover:scale-110"
              ></div>

              <div
                class="flex items-start justify-between"
              >

                <span
                  class="text-xs font-semibold text-[#42474f]"
                >
                  Total Active Borrowers
                </span>

                <div
                  class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#a8e2ff]/40 text-[#26667f]"
                >
                  <span class="material-symbols-outlined text-[20px]">
                    groups
                  </span>
                </div>

              </div>

              <div class="mt-4">

                <span
                  class="text-[32px] font-bold tracking-tight text-[#002b51]"
                >
                  142
                </span>

                <div
                  class="mt-1 flex flex-wrap items-center gap-2 text-[11px] text-[#737780]"
                >

                  <span>
                    Across Grades 7-12
                  </span>

                  <span
                    class="h-1 w-1 rounded-full bg-[#737780]"
                  ></span>

                  <span
                    class="font-bold text-[#25657e]"
                  >
                    96% active rate
                  </span>

                </div>

              </div>

            </div>

            <!-- Currently Borrowed -->
            <div
              class="group relative overflow-hidden rounded-xl bg-white p-5 shadow-sm transition hover:shadow-md"
            >

              <div
                class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-[#7fd9a7]/20 transition-transform group-hover:scale-110"
              ></div>

              <div
                class="flex items-start justify-between"
              >

                <span
                  class="text-xs font-semibold text-[#42474f]"
                >
                  Currently Borrowed
                </span>

                <div
                  class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#d7eee1] text-[#00311d]"
                >
                  <span class="material-symbols-outlined text-[20px]">
                    auto_stories
                  </span>
                </div>

              </div>

              <div class="mt-4">

                <span
                  class="text-[32px] font-bold tracking-tight text-[#002b51]"
                >
                  86
                  <span class="text-base font-normal text-[#42474f]">
                    Books
                  </span>
                </span>

                <div
                  class="mt-1 flex items-center gap-2 text-[11px] text-[#737780]"
                >

                  <span
                    class="material-symbols-outlined text-[16px] text-[#7fd9a7]"
                  >
                    schedule
                  </span>

                  On regular loan schedule

                </div>

              </div>

            </div>

            <!-- Overdue -->
            <div
              class="group relative overflow-hidden rounded-xl bg-white p-5 shadow-sm transition hover:shadow-md"
            >

              <div
                class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-[#ffdad6]/40 transition-transform group-hover:scale-110"
              ></div>

              <div
                class="flex items-start justify-between"
              >

                <span
                  class="text-xs font-semibold text-[#42474f]"
                >
                  Overdue Returns
                </span>

                <div
                  class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#ffdad6] text-[#93000a]"
                >
                  <span class="material-symbols-outlined text-[20px]">
                    warning
                  </span>
                </div>

              </div>

              <div class="mt-4">

                <div class="flex items-baseline gap-2">

                  <span
                    class="text-[32px] font-bold tracking-tight text-[#ba1a1a]"
                  >
                    9
                  </span>

                  <span
                    class="rounded-full bg-[#ffdad6] px-2 py-0.5 text-[10px] font-bold text-[#93000a]"
                  >
                    Action Needed
                  </span>

                </div>

                <div
                  class="mt-1 text-[11px] text-[#ba1a1a]"
                >
                  Requires immediate follow-up
                </div>

              </div>

            </div>

            <!-- Returned -->
            <div
              class="group relative overflow-hidden rounded-xl bg-white p-5 shadow-sm transition hover:shadow-md"
            >

              <div
                class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-[#bee9ff]/40 transition-transform group-hover:scale-110"
              ></div>

              <div
                class="flex items-start justify-between"
              >

                <span
                  class="text-xs font-semibold text-[#42474f]"
                >
                  Returned This Week
                </span>

                <div
                  class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e2f9ec] text-[#25657e]"
                >
                  <span class="material-symbols-outlined text-[20px]">
                    assignment_turned_in
                  </span>
                </div>

              </div>

              <div class="mt-4">

                <span
                  class="text-[32px] font-bold tracking-tight text-[#002b51]"
                >
                  54
                </span>

                <div
                  class="mt-1 flex items-center gap-2 text-[11px] text-[#737780]"
                >

                  <span
                    class="material-symbols-outlined text-[16px] text-[#00311d]"
                  >
                    inventory_2
                  </span>

                  Catalog fully restocked

                </div>

              </div>

            </div>

          </section>

          <!-- ========================================== -->
          <!-- TABLE CARD -->
          <!-- ========================================== -->

          <section
            class="overflow-hidden rounded-xl bg-white shadow-sm"
          >

            <!-- CONTROLS -->
            <div class="flex flex-col gap-4 p-5">

              <!-- TABS -->
              <div
                class="flex flex-wrap items-center justify-between gap-4"
              >

                <div
                  class="flex items-center gap-1 rounded-xl bg-[#e2f9ec] p-1"
                >

                  <!-- ALL -->
                  <button
                    type="button"
                    @click="setTab('all')"
                    :class="
                      activeTab === 'all'
                        ? 'bg-white text-[#002b51] shadow-sm'
                        : 'text-[#42474f] hover:bg-[#d7eee1]'
                    "
                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition"
                  >

                    All

                    <span
                      class="rounded-full bg-[#dcf3e6] px-1.5 py-0.5 text-[10px] font-bold"
                    >
                      291
                    </span>

                  </button>

                  <!-- BORROWED -->
                  <button
                    type="button"
                    @click="setTab('borrowed')"
                    :class="
                      activeTab === 'borrowed'
                        ? 'bg-white text-[#002b51] shadow-sm'
                        : 'text-[#42474f] hover:bg-[#d7eee1]'
                    "
                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition"
                  >

                    Borrowed

                    <span
                      class="rounded-full bg-[#7fd9a7]/40 px-1.5 py-0.5 text-[10px] font-bold text-[#00311d]"
                    >
                      {{ borrowedCount }}
                    </span>

                  </button>

                  <!-- OVERDUE -->
                  <button
                    type="button"
                    @click="setTab('overdue')"
                    :class="
                      activeTab === 'overdue'
                        ? 'bg-white text-[#ba1a1a] shadow-sm'
                        : 'text-[#42474f] hover:bg-[#d7eee1]'
                    "
                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition"
                  >

                    Overdue

                    <span
                      class="rounded-full bg-[#ffdad6] px-1.5 py-0.5 text-[10px] font-bold text-[#93000a]"
                    >
                      {{ overdueCount }}
                    </span>

                  </button>

                  <!-- RETURNED -->
                  <button
                    type="button"
                    @click="setTab('returned')"
                    :class="
                      activeTab === 'returned'
                        ? 'bg-white text-[#002b51] shadow-sm'
                        : 'text-[#42474f] hover:bg-[#d7eee1]'
                    "
                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition"
                  >

                    Returned

                    <span
                      class="rounded-full bg-[#d1e8db] px-1.5 py-0.5 text-[10px] font-bold text-[#42474f]"
                    >
                      {{ returnedCount }}
                    </span>

                  </button>

                </div>

                <!-- EXPORT -->
                <div class="flex items-center gap-2">

                  <button
                    type="button"
                    @click="exportCSV"
                    class="flex items-center gap-2 rounded-xl bg-[#e2f9ec] px-3 py-2 text-xs font-semibold text-[#25657e] transition hover:bg-[#dcf3e6]"
                  >

                    <span
                      class="material-symbols-outlined text-[18px]"
                    >
                      file_download
                    </span>

                    Export CSV

                  </button>

                  <button
                    type="button"
                    @click="printSheet"
                    class="flex items-center gap-2 rounded-xl bg-[#e2f9ec] px-3 py-2 text-xs font-semibold text-[#42474f] transition hover:bg-[#dcf3e6]"
                  >

                    <span
                      class="material-symbols-outlined text-[18px]"
                    >
                      print
                    </span>

                    Print Sheet

                  </button>

                </div>

              </div>

              <!-- FILTERS -->
              <div
                class="grid grid-cols-1 items-center gap-3 md:grid-cols-12"
              >

                <!-- SEARCH -->
                <div
                  class="relative md:col-span-6"
                >

                  <span
                    class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-[#737780]"
                  >
                    search
                  </span>

                  <input
                    v-model="searchQuery"
                    @input="applyFilter"
                    type="text"
                    placeholder="Search student by name, NIS, or book title..."
                    class="w-full rounded-xl bg-[#e2f9ec] py-2.5 pl-10 pr-4 text-xs outline-none placeholder:text-[#737780] focus:bg-white focus:ring-2 focus:ring-[#25657e]/30"
                  />

                </div>

                <!-- CLASS -->
                <div
                  class="relative md:col-span-3"
                >

                  <select
                    v-model="selectedClass"
                    @change="applyFilter"
                    class="w-full appearance-none rounded-xl bg-[#e2f9ec] py-2.5 pl-3 pr-10 text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#25657e]/30"
                  >

                    <option value="">
                      All Classes
                    </option>

                    <option value="10-A">
                      Class 10-A
                    </option>

                    <option value="10-B">
                      Class 10-B
                    </option>

                    <option value="11-Sci 2">
                      Class 11-Sci 2
                    </option>

                    <option value="11-A">
                      Class 11-A
                    </option>

                    <option value="11-Arts">
                      Class 11-Arts
                    </option>

                    <option value="12-Lit">
                      Class 12-Lit
                    </option>

                    <option value="12-Sci 1">
                      Class 12-Sci 1
                    </option>

                  </select>

                  <span
                    class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[18px] text-[#737780]"
                  >
                    expand_more
                  </span>

                </div>

                <!-- STATUS -->
                <div
                  class="relative md:col-span-3"
                >

                  <select
                    v-model="selectedStatus"
                    @change="applyFilter"
                    class="w-full appearance-none rounded-xl bg-[#e2f9ec] py-2.5 pl-3 pr-10 text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#25657e]/30"
                  >

                    <option value="">
                      All Statuses
                    </option>

                    <option value="borrowed">
                      Borrowed
                    </option>

                    <option value="overdue">
                      Overdue
                    </option>

                    <option value="returned">
                      Returned
                    </option>

                  </select>

                  <span
                    class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[18px] text-[#737780]"
                  >
                    filter_list
                  </span>

                </div>

              </div>

            </div>

            <!-- ======================================== -->
            <!-- TABLE -->
            <!-- ======================================== -->

            <div class="w-full overflow-x-auto">

              <table
                class="w-full border-collapse text-left"
              >

                <thead>

                  <tr
                    class="h-11 bg-[#e2f9ec]/70 text-[#42474f]"
                  >

                    <th
                      class="px-5 py-2 text-[11px] font-bold uppercase tracking-wider"
                    >
                      Student / Borrower
                    </th>

                    <th
                      class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider"
                    >
                      NIS
                    </th>

                    <th
                      class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider"
                    >
                      Class
                    </th>

                    <th
                      class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider"
                    >
                      Book Title & Catalog Code
                    </th>

                    <th
                      class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider"
                    >
                      Borrow Date
                    </th>

                    <th
                      class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider"
                    >
                      Due Date
                    </th>

                    <th
                      class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider"
                    >
                      Status
                    </th>

                    <th
                      class="px-5 py-2 text-right text-[11px] font-bold uppercase tracking-wider"
                    >
                      Actions
                    </th>

                  </tr>

                </thead>

                <tbody>

                  <!-- ROWS -->
                  <tr
                    v-for="borrower in paginatedBorrowers"
                    :key="borrower.id"
                    class="group border-b border-[#d1e8db]/60 transition hover:bg-[#e2f9ec]/50"
                  >

                    <!-- STUDENT -->
                    <td class="px-5 py-4">

                      <div
                        class="flex items-center gap-3"
                      >

                        <div
                          :class="borrower.avatarClass"
                          class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold"
                        >
                          {{ borrower.initials }}
                        </div>

                        <div class="min-w-0">

                          <p
                            class="truncate text-sm font-semibold text-[#002b51] transition group-hover:text-[#25657e]"
                          >
                            {{ borrower.name }}
                          </p>

                          <p
                            class="truncate text-[11px] text-[#737780]"
                          >
                            {{ borrower.email }}
                          </p>

                        </div>

                      </div>

                    </td>

                    <!-- NIS -->
                    <td class="px-4 py-4">

                      <span
                        class="whitespace-nowrap rounded bg-[#d1e8db] px-2 py-1 font-mono text-[11px] text-[#0c1f17]"
                      >
                        {{ borrower.nis }}
                      </span>

                    </td>

                    <!-- CLASS -->
                    <td class="px-4 py-4">

                      <span
                        class="whitespace-nowrap text-xs font-medium text-[#0c1f17]"
                      >
                        Class {{ borrower.class }}
                      </span>

                    </td>

                    <!-- BOOK -->
                    <td class="px-4 py-4">

                      <div
                        class="flex items-center gap-2"
                      >

                        <span
                          class="material-symbols-outlined shrink-0 text-[18px] text-[#25657e]"
                        >
                          menu_book
                        </span>

                        <div class="min-w-0">

                          <p
                            class="max-w-xs truncate text-xs font-semibold text-[#002b51]"
                          >
                            {{ borrower.book }}
                          </p>

                          <p
                            class="font-mono text-[10px] text-[#737780]"
                          >
                            {{ borrower.catalogCode }}
                          </p>

                        </div>

                      </div>

                    </td>

                    <!-- BORROW DATE -->
                    <td
                      class="whitespace-nowrap px-4 py-4 text-xs text-[#42474f]"
                    >
                      {{ borrower.borrowDate }}
                    </td>

                    <!-- DUE DATE -->
                    <td class="px-4 py-4">

                      <span
                        :class="
                          borrower.status === 'overdue'
                            ? 'text-[#ba1a1a]'
                            : 'text-[#42474f]'
                        "
                        class="whitespace-nowrap text-xs font-medium"
                      >
                        {{ borrower.dueDate }}
                      </span>

                    </td>

                    <!-- STATUS -->
                    <td class="px-4 py-4">

                      <!-- OVERDUE -->
                      <span
                        v-if="borrower.status === 'overdue'"
                        class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-[#ffdad6] px-2.5 py-1 text-[10px] font-bold text-[#93000a]"
                      >

                        <span
                          class="h-1.5 w-1.5 animate-ping rounded-full bg-[#ba1a1a]"
                        ></span>

                        OVERDUE
                        (+{{ borrower.overdueDays }}d)

                      </span>

                      <!-- BORROWED -->
                      <span
                        v-else-if="borrower.status === 'borrowed'"
                        class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-[#d7eee1] px-2.5 py-1 text-[10px] font-bold text-[#00311d]"
                      >

                        <span
                          class="h-1.5 w-1.5 rounded-full bg-[#7fd9a7]"
                        ></span>

                        BORROWED

                      </span>

                      <!-- RETURNED -->
                      <span
                        v-else
                        class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-[#d1e8db] px-2.5 py-1 text-[10px] font-bold text-[#42474f]"
                      >

                        <span
                          class="material-symbols-outlined text-[14px]"
                        >
                          check
                        </span>

                        RETURNED

                      </span>

                    </td>

                    <!-- ACTION -->
                    <td class="px-5 py-4 text-right">

                      <button
                        type="button"
                        @click="openBorrowerDetail(borrower)"
                        title="More options"
                        class="rounded-lg p-1.5 text-[#737780] transition hover:bg-[#d1e8db] hover:text-[#002b51]"
                      >

                        <span
                          class="material-symbols-outlined text-[18px]"
                        >
                          more_vert
                        </span>

                      </button>

                    </td>

                  </tr>

                  <!-- EMPTY -->
                  <tr
                    v-if="paginatedBorrowers.length === 0"
                  >

                    <td
                      colspan="8"
                      class="px-6 py-16 text-center"
                    >

                      <span
                        class="material-symbols-outlined text-5xl text-[#c3c6d0]"
                      >
                        person_search
                      </span>

                      <p
                        class="mt-3 text-sm font-semibold text-[#002b51]"
                      >
                        Data borrower tidak ditemukan
                      </p>

                      <p
                        class="mt-1 text-xs text-[#737780]"
                      >
                        Coba ubah pencarian atau filter.
                      </p>

                    </td>

                  </tr>

                </tbody>

              </table>

            </div>

            <!-- ======================================== -->
            <!-- PAGINATION -->
            <!-- ======================================== -->

            <div
              class="flex flex-col items-center justify-between gap-4 bg-[#e2f9ec]/40 px-5 py-4 sm:flex-row"
            >

              <div
                class="text-xs text-[#42474f]"
              >

                Showing

                <strong class="text-[#0c1f17]">
                  {{
                    filteredBorrowers.length
                      ? (currentPage - 1) * rowsPerPage + 1
                      : 0
                  }}
                </strong>

                to

                <strong class="text-[#0c1f17]">
                  {{
                    Math.min(
                      currentPage * rowsPerPage,
                      filteredBorrowers.length
                    )
                  }}
                </strong>

                of

                <strong class="text-[#0c1f17]">
                  {{ filteredBorrowers.length }}
                </strong>

                borrowers

              </div>

              <div
                class="flex flex-wrap items-center gap-4"
              >

                <!-- ROWS -->
                <div
                  class="flex items-center gap-2 text-xs text-[#42474f]"
                >

                  <span>
                    Rows per page:
                  </span>

                  <select
                    v-model.number="rowsPerPage"
                    @change="changeRowsPerPage"
                    class="cursor-pointer rounded-lg bg-white px-2 py-1 text-xs outline-none ring-1 ring-[#d1e8db] focus:ring-[#25657e]"
                  >

                    <option :value="8">
                      8
                    </option>

                    <option :value="15">
                      15
                    </option>

                    <option :value="25">
                      25
                    </option>

                    <option :value="50">
                      50
                    </option>

                  </select>

                </div>

                <!-- PAGINATION BUTTONS -->
                <div
                  class="flex items-center gap-1"
                >

                  <button
                    type="button"
                    @click="previousPage"
                    :disabled="currentPage === 1"
                    class="rounded-lg p-1.5 text-[#737780] transition hover:bg-[#d1e8db] disabled:pointer-events-none disabled:opacity-40"
                  >

                    <span
                      class="material-symbols-outlined text-[18px]"
                    >
                      chevron_left
                    </span>

                  </button>

                  <button
                    v-for="page in totalPages"
                    :key="page"
                    type="button"
                    @click="changePage(page)"
                    :class="
                      currentPage === page
                        ? 'bg-[#002b51] text-white shadow-sm'
                        : 'text-[#0c1f17] hover:bg-[#d1e8db]'
                    "
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-xs font-semibold transition"
                  >
                    {{ page }}
                  </button>

                  <button
                    type="button"
                    @click="nextPage"
                    :disabled="
                      currentPage === totalPages
                    "
                    class="rounded-lg p-1.5 text-[#0c1f17] transition hover:bg-[#d1e8db] disabled:pointer-events-none disabled:opacity-40"
                  >

                    <span
                      class="material-symbols-outlined text-[18px]"
                    >
                      chevron_right
                    </span>

                  </button>

                </div>

              </div>

            </div>

          </section>

          <!-- ========================================== -->
          <!-- SUPPORT CARDS -->
          <!-- ========================================== -->

          <section
            class="grid grid-cols-1 gap-5 lg:grid-cols-3"
          >

            <!-- POLICY -->
            <div
              class="flex flex-col justify-between rounded-xl bg-white p-5 shadow-sm"
            >

              <div>

                <div
                  class="mb-2 flex items-center gap-2"
                >

                  <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e2f9ec] text-[#25657e]"
                  >
                    <span class="material-symbols-outlined text-[18px]">
                      policy
                    </span>
                  </div>

                  <span
                    class="text-sm font-semibold text-[#002b51]"
                  >
                    Circulation Policy Rule
                  </span>

                </div>

                <p
                  class="text-xs leading-relaxed text-[#42474f]"
                >
                  Standard loan window adalah 14 hari
                  kalender. Buku referensi dengan permintaan
                  tinggi dibatasi 3 hari. Siswa dengan lebih
                  dari 1 buku terlambat tidak dapat melakukan
                  peminjaman baru sampai masalah diselesaikan.
                </p>

              </div>

              <button
                type="button"
                @click="showToastMessage('Halaman aturan perpustakaan dibuka.')"
                class="mt-4 flex items-center justify-between border-t border-[#d1e8db] pt-3 text-left text-[10px] font-bold text-[#25657e]"
              >

                Review Library Bylaws

                <span
                  class="material-symbols-outlined text-[16px]"
                >
                  arrow_forward
                </span>

              </button>

            </div>

            <!-- SCANNER -->
            <div
              class="flex flex-col justify-between rounded-xl bg-white p-5 shadow-sm"
            >

              <div>

                <div
                  class="mb-2 flex items-center gap-2"
                >

                  <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e2f9ec] text-[#00311d]"
                  >
                    <span class="material-symbols-outlined text-[18px]">
                      barcode_scanner
                    </span>
                  </div>

                  <span
                    class="text-sm font-semibold text-[#002b51]"
                  >
                    Barcode Handheld Mode
                  </span>

                </div>

                <p
                  class="text-xs leading-relaxed text-[#42474f]"
                >
                  USB / Bluetooth barcode reader dapat
                  digunakan pada halaman ini. Scan student ID
                  atau ISBN untuk membantu mencari data
                  sirkulasi secara otomatis.
                </p>

              </div>

              <div
                class="mt-4 flex items-center gap-2 border-t border-[#d1e8db] pt-3 text-[10px] text-[#42474f]"
              >

                <span
                  class="h-2 w-2 rounded-full bg-[#7fd9a7]"
                ></span>

                Scanner HID driver: Connected

              </div>

            </div>

            <!-- OVERDUE DIGEST -->
            <div
              class="relative flex flex-col justify-between overflow-hidden rounded-xl bg-[#002b51] p-5 text-white shadow-sm"
            >

              <div
                class="pointer-events-none absolute -bottom-6 -right-6 h-28 w-28 rounded-full bg-white/5"
              ></div>

              <div>

                <div
                  class="flex items-center justify-between"
                >

                  <span
                    class="text-[10px] font-bold uppercase tracking-wider text-[#7fd9a7]"
                  >
                    Automated Digest
                  </span>

                  <span
                    class="material-symbols-outlined text-[18px] text-[#7fd9a7]"
                  >
                    outgoing_mail
                  </span>

                </div>

                <h3
                  class="mt-1 text-base font-semibold"
                >
                  Daily Overdue Digest
                </h3>

                <p
                  class="mt-1 text-xs leading-relaxed text-[#87aee3]"
                >
                  Kirim reminder kepada siswa yang memiliki
                  keterlambatan pengembalian buku.
                </p>

              </div>

              <div class="mt-4">

                <button
                  type="button"
                  @click="dispatchReminders"
                  class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#9bf5c1] px-4 py-2 text-xs font-bold text-[#00311d] transition hover:bg-[#7fd9a7]"
                >

                  <span
                    class="material-symbols-outlined text-[18px]"
                  >
                    send
                  </span>

                  Dispatch {{ overdueCount }} Reminders

                </button>

              </div>

            </div>

          </section>

        </div>

      </main>

    </div>

    <!-- ================================================ -->
    <!-- BORROWER DETAIL MODAL -->
    <!-- ================================================ -->

    <div
      v-if="showDetailModal && selectedBorrower"
      class="fixed inset-0 z-[100] flex items-center justify-center bg-[#002b51]/50 p-4 backdrop-blur-sm"
      @click.self="closeBorrowerDetail"
    >

      <div
        class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl"
      >

        <div
          class="flex items-start justify-between"
        >

          <div>

            <p
              class="text-[10px] font-bold uppercase tracking-wider text-[#25657e]"
            >
              Borrower Details
            </p>

            <h2
              class="mt-1 text-xl font-bold text-[#002b51]"
            >
              {{ selectedBorrower.name }}
            </h2>

          </div>

          <button
            type="button"
            @click="closeBorrowerDetail"
            class="rounded-lg p-2 text-[#737780] hover:bg-[#e2f9ec] hover:text-[#002b51]"
          >

            <span class="material-symbols-outlined">
              close
            </span>

          </button>

        </div>

        <div
          class="mt-5 grid grid-cols-2 gap-3"
        >

          <div
            class="rounded-xl bg-[#e2f9ec] p-3"
          >

            <p class="text-[10px] text-[#737780]">
              NIS
            </p>

            <p class="mt-1 font-mono text-xs font-semibold text-[#002b51]">
              {{ selectedBorrower.nis }}
            </p>

          </div>

          <div
            class="rounded-xl bg-[#e2f9ec] p-3"
          >

            <p class="text-[10px] text-[#737780]">
              Class
            </p>

            <p class="mt-1 text-xs font-semibold text-[#002b51]">
              {{ selectedBorrower.class }}
            </p>

          </div>

          <div
            class="col-span-2 rounded-xl bg-[#e2f9ec] p-3"
          >

            <p class="text-[10px] text-[#737780]">
              Email
            </p>

            <p class="mt-1 text-xs font-semibold text-[#002b51]">
              {{ selectedBorrower.email }}
            </p>

          </div>

          <div
            class="col-span-2 rounded-xl bg-[#e2f9ec] p-3"
          >

            <p class="text-[10px] text-[#737780]">
              Book
            </p>

            <p class="mt-1 text-xs font-semibold text-[#002b51]">
              {{ selectedBorrower.book }}
            </p>

            <p class="mt-1 font-mono text-[10px] text-[#737780]">
              {{ selectedBorrower.catalogCode }}
            </p>

          </div>

          <div
            class="rounded-xl bg-[#e2f9ec] p-3"
          >

            <p class="text-[10px] text-[#737780]">
              Borrow Date
            </p>

            <p class="mt-1 text-xs font-semibold text-[#002b51]">
              {{ selectedBorrower.borrowDate }}
            </p>

          </div>

          <div
            class="rounded-xl bg-[#e2f9ec] p-3"
          >

            <p class="text-[10px] text-[#737780]">
              Due Date
            </p>

            <p
              :class="
                selectedBorrower.status === 'overdue'
                  ? 'text-[#ba1a1a]'
                  : 'text-[#002b51]'
              "
              class="mt-1 text-xs font-semibold"
            >
              {{ selectedBorrower.dueDate }}
            </p>

          </div>

        </div>

        <div class="mt-5 flex justify-end">

          <button
            type="button"
            @click="closeBorrowerDetail"
            class="rounded-xl bg-[#002b51] px-5 py-2.5 text-xs font-semibold text-white hover:bg-[#124170]"
          >
            Tutup
          </button>

        </div>

      </div>

    </div>

    <!-- ================================================ -->
    <!-- ISSUE BOOK MODAL -->
    <!-- ================================================ -->

    <div
      v-if="showIssueModal"
      class="fixed inset-0 z-[100] flex items-center justify-center bg-[#002b51]/50 p-4 backdrop-blur-sm"
      @click.self="closeIssueBook"
    >

      <div
        class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
      >

        <div class="flex items-start justify-between">

          <div>

            <p
              class="text-[10px] font-bold uppercase tracking-wider text-[#25657e]"
            >
              Circulation
            </p>

            <h2
              class="mt-1 text-xl font-bold text-[#002b51]"
            >
              Issue Book
            </h2>

          </div>

          <button
            type="button"
            @click="closeIssueBook"
            class="rounded-lg p-2 text-[#737780] hover:bg-[#e2f9ec]"
          >

            <span class="material-symbols-outlined">
              close
            </span>

          </button>

        </div>

        <div
          class="mt-5 rounded-xl bg-[#e2f9ec] p-5 text-center"
        >

          <span
            class="material-symbols-outlined text-5xl text-[#25657e]"
          >
            qr_code_scanner
          </span>

          <h3
            class="mt-3 text-sm font-bold text-[#002b51]"
          >
            Scan Student ID & ISBN
          </h3>

          <p
            class="mt-1 text-xs leading-relaxed text-[#737780]"
          >
            Gunakan scanner untuk memilih siswa dan buku
            yang akan dipinjam.
          </p>

        </div>

        <div class="mt-5 flex justify-end gap-2">

          <button
            type="button"
            @click="closeIssueBook"
            class="rounded-xl bg-[#e2f9ec] px-4 py-2.5 text-xs font-semibold text-[#42474f]"
          >
            Batal
          </button>

          <button
            type="button"
            @click="confirmIssueBook"
            class="rounded-xl bg-[#002b51] px-4 py-2.5 text-xs font-semibold text-white"
          >
            Mulai Scan
          </button>

        </div>

      </div>

    </div>

    <!-- ================================================ -->
    <!-- TOAST -->
    <!-- ================================================ -->

    <Transition name="toast">

      <div
        v-if="showToast"
        class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 rounded-xl bg-[#002b51] px-4 py-3 text-white shadow-xl"
      >

        <span
          class="material-symbols-outlined text-[#9bf5c1]"
        >
          check_circle
        </span>

        <span class="text-xs font-semibold">
          {{ toastMessage }}
        </span>

      </div>

    </Transition>

  </div>
</template>

<style scoped>
button {
  cursor: pointer;
}

button:disabled {
  cursor: not-allowed;
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.25s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

@media print {
  aside,
  header,
  button,
  .fixed {
    display: none !important;
  }

  .pl-64 {
    padding-left: 0 !important;
  }

  main {
    padding: 0 !important;
  }
}
</style>