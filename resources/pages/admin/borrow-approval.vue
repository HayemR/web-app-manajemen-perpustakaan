<script setup>
import { computed, ref } from 'vue'
import AdminSidebar from '../../components/adminsidebar.vue'

const searchQuery = ref('')
const selectedClass = ref('all')
const selectedSort = ref('newest')
const showEmptyPreview = ref(false)

const requests = ref([
  {
    id: 1,
    borrower: 'Alex Morgan',
    nis: 'NIS-2023-04921',
    className: '10-A',
    bookTitle: 'Cosmos: A Personal Voyage',
    author: 'Carl Sagan',
    bookCode: 'LIB-AST-520.1',
    shelf: 'B-14',
    category: 'Science',
    icon: 'science',
    requestDate: 'Today, 08:45 AM',
    requestInfo: '42 minutes ago',
    returnDate: 'Nov 28, 2026',
    returnInfo: '14 Days Standard',
  },
  {
    id: 2,
    borrower: 'Elena Rostova',
    nis: 'NIS-2022-01844',
    className: '11-Honors',
    bookTitle: 'To Kill a Mockingbird',
    author: 'Harper Lee',
    bookCode: 'LIB-LIT-813.5',
    shelf: 'C-08',
    category: 'Fiction',
    icon: 'menu_book',
    requestDate: 'Today, 09:12 AM',
    requestInfo: '15 minutes ago',
    returnDate: 'Nov 28, 2026',
    returnInfo: '14 Days Standard',
  },
  {
    id: 3,
    borrower: 'Marcus Chen',
    nis: 'NIS-2021-09312',
    className: '12-Science',
    bookTitle: 'A Brief History of Time',
    author: 'Stephen Hawking',
    bookCode: 'LIB-PHY-530.1',
    shelf: 'A-02',
    category: 'Physics',
    icon: 'history_edu',
    requestDate: 'Today, 09:05 AM',
    requestInfo: '22 minutes ago',
    returnDate: 'Nov 28, 2026',
    returnInfo: '14 Days Standard',
  },
  {
    id: 4,
    borrower: 'Sofia Ramirez',
    nis: 'NIS-2022-03190',
    className: '11-Honors',
    bookTitle: '1984: Graphic Edition',
    author: 'George Orwell',
    bookCode: 'LIB-FIC-823.9',
    shelf: 'D-11',
    category: 'Classics',
    icon: 'visibility',
    requestDate: 'Today, 08:30 AM',
    requestInfo: '57 minutes ago',
    returnDate: 'Nov 28, 2026',
    returnInfo: '14 Days Standard',
  },
  {
    id: 5,
    borrower: 'David Kim',
    nis: 'NIS-2023-05187',
    className: '10-B',
    bookTitle: 'Principles of Physics, 11th Ed',
    author: 'Halliday & Resnick',
    bookCode: 'LIB-SCI-530.0',
    shelf: 'E-03',
    category: 'STEM',
    icon: 'science',
    requestDate: 'Today, 08:14 AM',
    requestInfo: '1 hr 13m ago',
    returnDate: 'Nov 28, 2026',
    returnInfo: '14 Days Standard',
  },
  {
    id: 6,
    borrower: 'Chloe Bennett',
    nis: 'NIS-2021-08832',
    className: '12-B',
    bookTitle: 'The Great Gatsby',
    author: 'F. Scott Fitzgerald',
    bookCode: 'LIB-LIT-813.2',
    shelf: 'C-02',
    category: 'Fiction',
    icon: 'theater_comedy',
    requestDate: 'Yesterday, 04:30 PM',
    requestInfo: 'After-hours hold',
    returnDate: 'Nov 27, 2026',
    returnInfo: '14 Days Standard',
  },
  {
    id: 7,
    borrower: "Liam O'Connor",
    nis: 'NIS-2023-04988',
    className: '10-A',
    bookTitle: 'Chemistry: Molecular Nature',
    author: 'Martin Silberberg',
    bookCode: 'LIB-CHM-540.2',
    shelf: 'E-09',
    category: 'Science',
    icon: 'biotech',
    requestDate: 'Yesterday, 03:15 PM',
    requestInfo: 'Overnight request',
    returnDate: 'Nov 27, 2026',
    returnInfo: '14 Days Standard',
  },
])

const pendingCount = computed(() => requests.value.length)

const filteredRequests = computed(() => {
  let result = [...requests.value]

  const query = searchQuery.value.toLowerCase().trim()

  if (query) {
    result = result.filter((request) => {
      const searchableText = [
        request.borrower,
        request.nis,
        request.className,
        request.bookTitle,
        request.author,
        request.bookCode,
        request.shelf,
      ]
        .join(' ')
        .toLowerCase()

      return searchableText.includes(query)
    })
  }

  if (selectedClass.value !== 'all') {
    result = result.filter(
      (request) => request.className === selectedClass.value
    )
  }

  if (selectedSort.value === 'oldest') {
    result.reverse()
  }

  if (selectedSort.value === 'shelf') {
    result.sort((a, b) => a.shelf.localeCompare(b.shelf))
  }

  return result
})

function approveRequest(id) {
  const request = requests.value.find((item) => item.id === id)

  if (!request) return

  requests.value = requests.value.filter((item) => item.id !== id)

  alert(
    `Peminjaman ${request.bookTitle} untuk ${request.borrower} berhasil disetujui.`
  )
}

function rejectRequest(id) {
  const request = requests.value.find((item) => item.id === id)

  if (!request) return

  const confirmed = confirm(
    `Tolak permintaan peminjaman dari ${request.borrower}?`
  )

  if (!confirmed) return

  requests.value = requests.value.filter((item) => item.id !== id)

  alert(
    `Permintaan ${request.bookTitle} dari ${request.borrower} berhasil ditolak.`
  )
}

function inspectRequest(request) {
  alert(
    `Detail Peminjaman\n\n` +
      `Borrower: ${request.borrower}\n` +
      `NIS: ${request.nis}\n` +
      `Class: ${request.className}\n` +
      `Book: ${request.bookTitle}\n` +
      `Author: ${request.author}\n` +
      `Book Code: ${request.bookCode}\n` +
      `Shelf: ${request.shelf}`
  )
}

function refreshQueue() {
  window.location.reload()
}
</script>

<template>
  <AdminSidebar />

  <div class="min-h-screen bg-[#e8fff2] pl-64 text-[#0c1f17]">
    <!-- HEADER -->
    <header
      class="fixed left-64 right-0 top-0 z-40 h-16 border-b border-[#d1e8db] bg-white/90 px-8 backdrop-blur-xl"
    >
      <div class="flex h-16 items-center justify-between gap-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-[#42474f]">
          <span class="material-symbols-outlined text-[20px] text-[#25657e]">
            menu_book
          </span>

          <span class="text-xs text-[#737780]">/</span>

          <span class="text-sm font-bold text-[#0c1f17]">
            Borrow Approval
          </span>
        </div>

        <!-- Right Header -->
        <div class="flex items-center gap-5">
          <!-- Search -->
          <div class="relative w-80">
            <span
              class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-[#737780]"
            >
              search
            </span>

            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search catalog, ISBN, student ID..."
              class="w-full rounded-xl bg-[#e2f9ec] py-2 pl-10 pr-4 text-xs outline-none transition focus:bg-white focus:ring-2 focus:ring-[#25657e]/20"
            />
          </div>

          <!-- Notification -->
          <button
            class="relative flex h-9 w-9 items-center justify-center rounded-xl text-[#42474f] transition hover:bg-[#dcf3e6]"
          >
            <span class="material-symbols-outlined text-[20px]">
              notifications
            </span>

            <span
              v-if="pendingCount > 0"
              class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#ba1a1a]"
            ></span>
          </button>

          <div class="h-6 w-px bg-[#c3c6d0]"></div>

          <!-- User -->
          <div class="flex items-center gap-2">
            <div
              class="flex h-8 w-8 items-center justify-center rounded-full bg-[#002b51]"
            >
              <span class="material-symbols-outlined text-[18px] text-white">
                person
              </span>
            </div>

            <div class="hidden flex-col md:flex">
              <span class="text-xs font-bold">S. Jenkins</span>
              <span class="text-[10px] text-[#737780]">Librarian</span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- MAIN -->
    <main class="min-h-screen px-8 pb-8 pt-24">
      <div class="flex flex-col gap-6">

        <!-- PAGE HEADER -->
        <section
          class="flex flex-col justify-between gap-4 xl:flex-row xl:items-end"
        >
          <div>
            <div class="flex flex-wrap items-center gap-3">
              <h1
                class="text-2xl font-extrabold tracking-tight text-[#002b51]"
              >
                Borrow Approval Queue
              </h1>

              <span
                class="inline-flex items-center gap-2 rounded-full bg-[#dcf3e6] px-3 py-1 text-xs font-bold text-[#004a2d]"
              >
                <span
                  class="h-2 w-2 animate-pulse rounded-full bg-[#64bd8d]"
                ></span>

                Live Circulation Stream
              </span>
            </div>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-[#42474f]">
              Review, verify, and approve incoming student loan reservations.
              Instant verification synchs physical tags directly with campus
              self-pickup lockers.
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-2">
            <div
              class="flex items-center gap-2 rounded-xl bg-white px-3 py-2 shadow-sm"
            >
              <span class="material-symbols-outlined text-[18px] text-[#25657e]">
                verified_user
              </span>

              <span class="text-[11px] text-[#42474f]">
                Librarian on Duty:
              </span>

              <span class="text-xs font-bold text-[#002b51]">
                Desk 02 (S. Jenkins)
              </span>
            </div>

            <div
              class="flex items-center gap-2 rounded-xl bg-[#d7eee1] px-3 py-2 text-[#004a2d] shadow-sm"
            >
              <span class="material-symbols-outlined text-[18px]">
                check_circle
              </span>

              <span class="text-[11px] font-bold">
                RFID Scanners Online
              </span>
            </div>
          </div>
        </section>

        <!-- STATS -->
        <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

          <!-- Pending -->
          <div
            class="group rounded-xl bg-white p-5 shadow-sm transition hover:-translate-y-0.5"
          >
            <div class="flex items-start justify-between">
              <div>
                <p
                  class="text-[10px] font-bold uppercase tracking-wider text-[#42474f]"
                >
                  Pending Approval
                </p>

                <div class="mt-1 flex items-baseline gap-2">
                  <span class="text-3xl font-extrabold text-[#002b51]">
                    {{ pendingCount }}
                  </span>

                  <span
                    class="rounded-full bg-[#dcf3e6] px-2 py-0.5 text-[9px] font-bold text-[#004a2d]"
                  >
                    Action Needed
                  </span>
                </div>
              </div>

              <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#dcf3e6] text-[#64bd8d]"
              >
                <span class="material-symbols-outlined">
                  pending_actions
                </span>
              </div>
            </div>

            <div
              class="mt-4 flex items-center gap-1.5 border-t border-[#e2f9ec] pt-3 text-xs text-[#42474f]"
            >
              <span class="material-symbols-outlined text-[16px] text-[#25657e]">
                schedule
              </span>

              Oldest reservation from 08:14 AM
            </div>
          </div>

          <!-- Approved -->
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
              <div>
                <p
                  class="text-[10px] font-bold uppercase tracking-wider text-[#42474f]"
                >
                  Approved Today
                </p>

                <div class="mt-1 flex items-baseline gap-2">
                  <span class="text-3xl font-extrabold text-[#002b51]">
                    24
                  </span>

                  <span class="text-xs text-[#42474f]">
                    Books Issued
                  </span>
                </div>
              </div>

              <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#a8e2ff]/40 text-[#25657e]"
              >
                <span class="material-symbols-outlined">
                  auto_stories
                </span>
              </div>
            </div>

            <div
              class="mt-4 flex items-center gap-1.5 border-t border-[#e2f9ec] pt-3 text-xs text-[#42474f]"
            >
              <span class="material-symbols-outlined text-[16px] text-[#64bd8d]">
                trending_up
              </span>

              +18% compared to yesterday
            </div>
          </div>

          <!-- Processing -->
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
              <div>
                <p
                  class="text-[10px] font-bold uppercase tracking-wider text-[#42474f]"
                >
                  Avg Processing Time
                </p>

                <div class="mt-1 flex items-baseline gap-2">
                  <span class="text-3xl font-extrabold text-[#002b51]">
                    12
                  </span>

                  <span class="text-base font-bold text-[#002b51]">
                    mins
                  </span>
                </div>
              </div>

              <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#d7eee1] text-[#25657e]"
              >
                <span class="material-symbols-outlined">
                  timelapse
                </span>
              </div>
            </div>

            <div
              class="mt-4 flex items-center gap-1.5 border-t border-[#e2f9ec] pt-3 text-xs text-[#42474f]"
            >
              <span class="material-symbols-outlined text-[16px] text-[#64bd8d]">
                speed
              </span>

              Optimal response SLA (&lt; 20 mins)
            </div>
          </div>

          <!-- Available -->
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
              <div>
                <p
                  class="text-[10px] font-bold uppercase tracking-wider text-[#42474f]"
                >
                  Available Copies Alert
                </p>

                <div class="mt-1">
                  <span class="text-xl font-extrabold text-[#002b51]">
                    All Ready
                  </span>
                </div>
              </div>

              <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e2f9ec] text-[#004a2d]"
              >
                <span class="material-symbols-outlined">
                  shelves
                </span>
              </div>
            </div>

            <div
              class="mt-4 flex items-center gap-1.5 border-t border-[#e2f9ec] pt-3 text-xs font-medium text-[#004a2d]"
            >
              <span class="material-symbols-outlined text-[16px]">
                done_all
              </span>

              All requested titles on shelf
            </div>
          </div>
        </section>

        <!-- FILTER -->
        <section
          class="flex flex-col items-stretch justify-between gap-4 rounded-xl bg-white p-4 shadow-sm lg:flex-row lg:items-center"
        >
          <div
            class="flex flex-1 flex-col gap-2 sm:flex-row sm:items-center"
          >
            <!-- Search -->
            <div class="relative flex-1">
              <span
                class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-[#25657e]"
              >
                search
              </span>

              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search borrower name, NIS, book title, or ISBN..."
                class="w-full rounded-xl bg-[#e2f9ec] py-2.5 pl-10 pr-10 text-xs outline-none transition focus:bg-white focus:ring-2 focus:ring-[#25657e]/20"
              />

              <span
                class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[18px] text-[#737780]"
              >
                barcode_scanner
              </span>
            </div>

            <!-- Class -->
            <div class="relative sm:w-48">
              <select
                v-model="selectedClass"
                class="w-full appearance-none rounded-xl bg-[#e2f9ec] px-3 py-2.5 pr-8 text-xs font-semibold outline-none"
              >
                <option value="all">All Classes</option>
                <option value="10-A">Grade 10-A</option>
                <option value="10-B">Grade 10-B</option>
                <option value="11-Honors">Grade 11 Honors</option>
                <option value="12-Science">Grade 12-Science</option>
                <option value="12-B">Grade 12-B</option>
              </select>

              <span
                class="material-symbols-outlined pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-[#737780]"
              >
                expand_more
              </span>
            </div>

            <!-- Sort -->
            <div class="relative sm:w-44">
              <select
                v-model="selectedSort"
                class="w-full appearance-none rounded-xl bg-[#e2f9ec] px-3 py-2.5 pr-8 text-xs font-semibold outline-none"
              >
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="shelf">By Shelf Location</option>
              </select>

              <span
                class="material-symbols-outlined pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-[18px] text-[#737780]"
              >
                sort
              </span>
            </div>
          </div>

          <!-- Pending Badge -->
          <div
            class="flex w-fit items-center gap-2 rounded-full bg-[#dcf3e6] px-3 py-1.5 text-xs font-bold text-[#004a2d]"
          >
            <span class="material-symbols-outlined text-[16px]">
              notifications_active
            </span>

            {{ pendingCount }} Pending Requests
          </div>
        </section>

        <!-- TABLE -->
        <section
          v-if="filteredRequests.length > 0"
          class="overflow-hidden rounded-xl bg-white shadow-sm"
        >
          <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[1100px] border-collapse text-left">
              <thead>
                <tr
                  class="h-11 bg-[#e2f9ec] text-[10px] font-bold uppercase tracking-wider text-[#42474f]"
                >
                  <th class="px-4 py-3">
                    Borrower & Student Info
                  </th>

                  <th class="px-4 py-3">
                    Book Details & Shelf
                  </th>

                  <th class="whitespace-nowrap px-4 py-3">
                    Request Date
                  </th>

                  <th class="whitespace-nowrap px-4 py-3">
                    Expected Return
                  </th>

                  <th class="px-4 py-3 text-right">
                    Circulation Decision
                  </th>
                </tr>
              </thead>

              <tbody class="divide-y divide-[#e2f9ec]">
                <tr
                  v-for="request in filteredRequests"
                  :key="request.id"
                  class="group transition hover:bg-[#e2f9ec]/40"
                >
                  <!-- Borrower -->
                  <td class="px-4 py-3.5">
                    <div class="flex flex-col">
                      <span class="text-sm font-bold text-[#002b51]">
                        {{ request.borrower }}
                      </span>

                      <span class="mt-0.5 text-[11px] text-[#42474f]">
                        {{ request.nis }}
                        • Class {{ request.className }}
                      </span>
                    </div>
                  </td>

                  <!-- Book -->
                  <td class="px-4 py-3.5">
                    <div class="flex items-center gap-3">
                      <div
                        class="flex h-14 w-10 shrink-0 flex-col items-center justify-center rounded-lg bg-[#d7eee1] p-1 text-center shadow-sm"
                      >
                        <span
                          class="material-symbols-outlined text-[18px] text-[#25657e]"
                        >
                          {{ request.icon }}
                        </span>

                        <span
                          class="mt-1 text-[8px] font-bold uppercase leading-tight text-[#002b51]"
                        >
                          {{ request.category }}
                        </span>
                      </div>

                      <div class="min-w-0">
                        <span
                          class="block truncate text-sm font-bold text-[#002b51]"
                        >
                          {{ request.bookTitle }}
                        </span>

                        <div class="mt-0.5 flex items-center gap-2">
                          <span class="text-[11px] text-[#42474f]">
                            {{ request.author }}
                          </span>

                          <span class="text-[11px] text-[#737780]">
                            •
                          </span>

                          <span
                            class="font-mono text-[11px] font-semibold text-[#25657e]"
                          >
                            {{ request.bookCode }}
                          </span>
                        </div>

                        <div class="mt-1">
                          <span
                            class="inline-flex items-center gap-1 rounded-md bg-[#d7eee1] px-2 py-0.5 text-[10px] text-[#42474f]"
                          >
                            <span
                              class="material-symbols-outlined text-[13px] text-[#25657e]"
                            >
                              grid_view
                            </span>

                            Shelf {{ request.shelf }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </td>

                  <!-- Request Date -->
                  <td class="whitespace-nowrap px-4 py-3.5">
                    <div class="flex flex-col">
                      <span class="text-xs font-bold text-[#002b51]">
                        {{ request.requestDate }}
                      </span>

                      <span class="text-[11px] text-[#42474f]">
                        {{ request.requestInfo }}
                      </span>
                    </div>
                  </td>

                  <!-- Return -->
                  <td class="whitespace-nowrap px-4 py-3.5">
                    <div class="flex flex-col">
                      <span class="text-xs font-bold text-[#002b51]">
                        {{ request.returnDate }}
                      </span>

                      <span class="text-[11px] text-[#42474f]">
                        {{ request.returnInfo }}
                      </span>
                    </div>
                  </td>

                  <!-- Actions -->
                  <td class="px-4 py-3.5">
                    <div class="flex items-center justify-end gap-2">
                      <!-- Approve -->
                      <button
                        type="button"
                        title="Approve Loan"
                        @click="approveRequest(request.id)"
                        class="inline-flex items-center gap-1 rounded-lg bg-[#dcf3e6] px-3 py-1.5 text-xs font-bold text-[#004a2d] shadow-sm transition hover:bg-[#d1e8db] active:scale-95"
                      >
                        <span class="material-symbols-outlined text-[18px]">
                          check
                        </span>

                        Approve
                      </button>

                      <!-- Reject -->
                      <button
                        type="button"
                        title="Reject Request"
                        @click="rejectRequest(request.id)"
                        class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs text-[#ba1a1a] transition hover:bg-[#ffdad6]/40 active:scale-95"
                      >
                        <span class="material-symbols-outlined text-[18px]">
                          close
                        </span>

                        Reject
                      </button>

                      <!-- Detail -->
                      <button
                        type="button"
                        title="Inspect Details"
                        @click="inspectRequest(request)"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-[#737780] transition hover:bg-[#e2f9ec] hover:text-[#002b51]"
                      >
                        <span class="material-symbols-outlined text-[20px]">
                          more_vert
                        </span>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- TABLE FOOTER -->
          <div
            class="flex flex-col items-center justify-between gap-4 bg-[#e2f9ec]/50 p-4 md:flex-row"
          >
            <div
              class="flex items-center gap-2 text-[11px] text-[#42474f]"
            >
              <span class="material-symbols-outlined text-[18px] text-[#25657e]">
                info
              </span>

              <span>
                Approving automatically updates the student's digital card
                and notifies campus circulation kiosks for pickup.
              </span>
            </div>

            <div class="flex items-center gap-4">
              <span class="text-[11px] text-[#42474f]">
                Showing 1-{{ filteredRequests.length }} of
                {{ pendingCount }} pending requests
              </span>

              <div class="flex items-center gap-1">
                <button
                  disabled
                  class="flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-lg bg-[#e2f9ec] text-[#737780] opacity-50"
                >
                  <span class="material-symbols-outlined text-[18px]">
                    chevron_left
                  </span>
                </button>

                <button
                  class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#002b51] text-xs font-bold text-white shadow-sm"
                >
                  1
                </button>

                <button
                  class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-xs transition hover:bg-[#e2f9ec]"
                >
                  2
                </button>

                <button
                  class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#0c1f17] transition hover:bg-[#e2f9ec]"
                >
                  <span class="material-symbols-outlined text-[18px]">
                    chevron_right
                  </span>
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- EMPTY STATE -->
        <section
          v-else
          class="flex flex-col items-center justify-center rounded-xl bg-white px-6 py-16 text-center shadow-sm"
        >
          <div
            class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#dcf3e6] text-[#004a2d]"
          >
            <span class="material-symbols-outlined text-[36px]">
              auto_stories
            </span>
          </div>

          <h3 class="text-xl font-extrabold text-[#002b51]">
            All Caught Up!
          </h3>

          <p class="mt-2 max-w-lg text-sm leading-6 text-[#42474f]">
            There are currently no pending borrow requests in the circulation
            queue. New student reservations submitted via the Libby mobile app
            or campus kiosks will appear here in real-time.
          </p>

          <div class="mt-6 flex items-center gap-2">
            <button
              @click="refreshQueue"
              class="inline-flex items-center gap-2 rounded-xl bg-[#25657e] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#002b51]"
            >
              <span class="material-symbols-outlined text-[18px]">
                refresh
              </span>

              Refresh Queue
            </button>

            <button
              class="inline-flex items-center gap-2 rounded-xl bg-[#e2f9ec] px-4 py-2 text-xs font-bold text-[#002b51] transition hover:bg-[#dcf3e6]"
            >
              <span class="material-symbols-outlined text-[18px]">
                history
              </span>

              View Historical Logs
            </button>
          </div>
        </section>

        <!-- EMPTY STATE SIMULATION -->
        <section class="flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <span
              class="text-[10px] font-bold uppercase tracking-wider text-[#42474f]"
            >
              Queue State Simulation
            </span>

            <button
              type="button"
              @click="showEmptyPreview = !showEmptyPreview"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#25657e] transition hover:text-[#002b51]"
            >
              <span class="material-symbols-outlined text-[16px]">
                visibility
              </span>

              {{
                showEmptyPreview
                  ? 'Hide "All Caught Up" Preview'
                  : 'Preview "All Caught Up" State'
              }}
            </button>
          </div>

          <div
            v-if="showEmptyPreview"
            class="flex flex-col items-center justify-center rounded-xl bg-white px-6 py-12 text-center shadow-sm"
          >
            <div
              class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#dcf3e6] text-[#004a2d]"
            >
              <span class="material-symbols-outlined text-[36px]">
                auto_stories
              </span>
            </div>

            <h3 class="text-xl font-extrabold text-[#002b51]">
              All Caught Up!
            </h3>

            <p class="mt-2 max-w-lg text-sm leading-6 text-[#42474f]">
              There are currently no pending borrow requests in the circulation
              queue.
            </p>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>