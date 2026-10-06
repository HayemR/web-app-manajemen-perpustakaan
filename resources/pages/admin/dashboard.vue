<script setup>
import AdminSidebar from '../../components/adminsidebar.vue'

const approvalRequests = [
  {
    id: 1,
    name: 'Aisha Rahman',
    class: 'XII SIJA A',
    book: 'The Design of Everyday Things',
    time: '10 min ago',
    initials: 'AR',
  },
  {
    id: 2,
    name: 'Raka Pratama',
    class: 'XI SIJA B',
    book: 'Clean Code',
    time: '18 min ago',
    initials: 'RP',
  },
  {
    id: 3,
    name: 'Nadia Putri',
    class: 'XII RPL A',
    book: 'Atomic Habits',
    time: '25 min ago',
    initials: 'NP',
  },
  {
    id: 4,
    name: 'Dimas Saputra',
    class: 'XI TKJ A',
    book: 'The Pragmatic Programmer',
    time: '31 min ago',
    initials: 'DS',
  },
  {
    id: 5,
    name: 'Salsa Amelia',
    class: 'XII SIJA B',
    book: 'Don’t Make Me Think',
    time: '42 min ago',
    initials: 'SA',
  },
]

const overdueAlerts = [
  {
    name: 'Bima Arya',
    book: 'Atomic Habits',
    days: '3 days overdue',
  },
  {
    name: 'Citra Lestari',
    book: 'Clean Architecture',
    days: '5 days overdue',
  },
  {
    name: 'Rizky Maulana',
    book: 'Digital Minimalism',
    days: '7 days overdue',
  },
]

function approveRequest(request) {
  alert(`Peminjaman ${request.name} disetujui`)
}

function rejectRequest(request) {
  alert(`Peminjaman ${request.name} ditolak`)
}

function sendAlert(alertData) {
  alert(`Pengingat dikirim ke ${alertData.name}`)
}

function refreshData() {
  alert('Data berhasil diperbarui')
}
</script>

<template>
  <!-- SIDEBAR -->
  <AdminSidebar />

  <!-- CONTENT AREA -->
  <div class="min-h-screen pl-64 bg-[#e8fff2] text-[#0c1f17]">

    <!-- HEADER -->
    <header
      class="fixed left-64 right-0 top-0 z-30 h-16 border-b border-[#d1e8db] bg-white/90 backdrop-blur"
    >
      <div class="flex h-full items-center justify-between px-8">

        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm">
          <span class="text-[#737780]">Admin</span>

          <span class="material-symbols-outlined text-[18px] text-[#737780]">
            chevron_right
          </span>

          <span class="font-semibold text-[#124170]">
            Dashboard
          </span>
        </div>

        <!-- Header Right -->
        <div class="flex items-center gap-5">

          <!-- Search -->
          <div class="relative hidden lg:block">
            <span
              class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-[#737780]"
            >
              search
            </span>

            <input
              type="text"
              placeholder="Search..."
              class="w-64 rounded-xl border border-[#d1e8db] bg-[#e8fff2] py-2 pl-10 pr-4 text-sm outline-none transition focus:border-[#124170]"
            />
          </div>

          <!-- Notification -->
          <button
            class="relative flex h-10 w-10 items-center justify-center rounded-full hover:bg-[#e2f9ec]"
          >
            <span class="material-symbols-outlined text-[#124170]">
              notifications
            </span>

            <span
              class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#ba1a1a]"
            ></span>
          </button>

          <!-- Profile -->
          <div class="flex items-center gap-3 border-l border-[#d1e8db] pl-5">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-full bg-[#124170] font-bold text-white"
            >
              SJ
            </div>

            <div class="hidden md:block">
              <p class="text-sm font-bold text-[#0c1f17]">
                Sarah Jenkins
              </p>

              <p class="text-xs text-[#737780]">
                Head Librarian
              </p>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- MAIN -->
    <main class="px-8 pb-10 pt-24">

      <!-- GREETING -->
      <section class="mb-8 flex flex-col justify-between gap-5 xl:flex-row xl:items-end">

        <div>
          <div class="mb-3 flex items-center gap-3">

            <span
              class="rounded-full bg-[#9bf5c1] px-3 py-1 text-[11px] font-extrabold tracking-wider text-[#00311d]"
            >
              OPERATIONAL HUB · LIVE
            </span>

            <span class="text-xs font-semibold text-[#737780]">
              SYSTEM STABLE · CLOUD SYNCED
            </span>

          </div>

          <h1 class="text-3xl font-extrabold tracking-tight text-[#124170] md:text-4xl">
            Good morning, Sarah
          </h1>

          <p class="mt-2 max-w-2xl text-sm leading-6 text-[#737780]">
            Here's what's happening across the library today.
            Keep an eye on pending approvals, circulation activity,
            and overdue returns.
          </p>
        </div>

        <!-- Date -->
        <div class="flex items-center gap-3">

          <div
            class="rounded-xl border border-[#d1e8db] bg-white px-4 py-3"
          >
            <p class="text-[10px] font-bold uppercase tracking-wider text-[#737780]">
              Academic Term
            </p>

            <p class="mt-1 text-sm font-bold text-[#124170]">
              Term 1 · 2026/2027
            </p>
          </div>

          <button
            @click="refreshData"
            class="flex items-center gap-2 rounded-xl bg-[#124170] px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#002b51]"
          >
            <span class="material-symbols-outlined text-[19px]">
              refresh
            </span>

            Refresh Data
          </button>

        </div>
      </section>

      <!-- STAT CARDS -->
      <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <!-- Pending -->
        <div
          class="rounded-2xl border border-[#d1e8db] bg-white p-5 shadow-sm"
        >
          <div class="flex items-start justify-between">

            <div>
              <p class="text-sm font-semibold text-[#737780]">
                Pending Approvals
              </p>

              <p class="mt-2 text-3xl font-extrabold text-[#124170]">
                12
              </p>
            </div>

            <div
              class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e2f9ec]"
            >
              <span class="material-symbols-outlined text-[#124170]">
                pending_actions
              </span>
            </div>

          </div>

          <p class="mt-4 text-xs font-semibold text-[#737780]">
            Requires librarian review
          </p>
        </div>

        <!-- Borrowed -->
        <div
          class="rounded-2xl border border-[#d1e8db] bg-white p-5 shadow-sm"
        >
          <div class="flex items-start justify-between">

            <div>
              <p class="text-sm font-semibold text-[#737780]">
                Currently Borrowed
              </p>

              <p class="mt-2 text-3xl font-extrabold text-[#124170]">
                86
              </p>
            </div>

            <div
              class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e2f9ec]"
            >
              <span class="material-symbols-outlined text-[#25657e]">
                menu_book
              </span>
            </div>

          </div>

          <p class="mt-4 text-xs font-semibold text-[#25657e]">
            +4 borrowed today
          </p>
        </div>

        <!-- Overdue -->
        <div
          class="rounded-2xl border border-[#d1e8db] bg-white p-5 shadow-sm"
        >
          <div class="flex items-start justify-between">

            <div>
              <p class="text-sm font-semibold text-[#737780]">
                Overdue Returns
              </p>

              <p class="mt-2 text-3xl font-extrabold text-[#ba1a1a]">
                9
              </p>
            </div>

            <div
              class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#ffdad6]"
            >
              <span class="material-symbols-outlined text-[#ba1a1a]">
                warning
              </span>
            </div>

          </div>

          <p class="mt-4 text-xs font-bold text-[#ba1a1a]">
            3 require urgent attention
          </p>
        </div>

        <!-- Books -->
        <div
          class="rounded-2xl border border-[#d1e8db] bg-white p-5 shadow-sm"
        >
          <div class="flex items-start justify-between">

            <div>
              <p class="text-sm font-semibold text-[#737780]">
                Total Books
              </p>

              <p class="mt-2 text-3xl font-extrabold text-[#124170]">
                10,482
              </p>
            </div>

            <div
              class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e2f9ec]"
            >
              <span class="material-symbols-outlined text-[#00311d]">
                library_books
              </span>
            </div>

          </div>

          <p class="mt-4 text-xs font-semibold text-[#00311d]">
            94% currently on shelf
          </p>
        </div>

      </section>

      <!-- MAIN GRID -->
      <section class="grid grid-cols-1 gap-6 xl:grid-cols-12">

        <!-- LEFT -->
        <div class="space-y-6 xl:col-span-8">

          <!-- APPROVAL REQUESTS -->
          <div
            class="overflow-hidden rounded-2xl border border-[#d1e8db] bg-white shadow-sm"
          >

            <!-- Card Header -->
            <div
              class="flex items-center justify-between border-b border-[#d1e8db] px-6 py-5"
            >
              <div>
                <h2 class="font-extrabold text-[#124170]">
                  Recent Approval Requests
                </h2>

                <p class="mt-1 text-xs text-[#737780]">
                  Review and approve student borrowing requests.
                </p>
              </div>

              <button
                class="text-sm font-bold text-[#25657e] hover:underline"
              >
                View all
              </button>
            </div>

            <!-- Requests -->
            <div class="divide-y divide-[#d1e8db]">

              <div
                v-for="request in approvalRequests"
                :key="request.id"
                class="flex flex-col gap-4 px-6 py-5 transition hover:bg-[#e8fff2] md:flex-row md:items-center md:justify-between"
              >

                <!-- Student -->
                <div class="flex items-center gap-4">

                  <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#124170] text-sm font-bold text-white"
                  >
                    {{ request.initials }}
                  </div>

                  <div>
                    <p class="font-bold text-[#0c1f17]">
                      {{ request.name }}
                    </p>

                    <p class="text-xs text-[#737780]">
                      {{ request.class }}
                      · {{ request.time }}
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#25657e]">
                      {{ request.book }}
                    </p>
                  </div>

                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">

                  <button
                    @click="rejectRequest(request)"
                    class="rounded-lg border border-[#d1e8db] px-3 py-2 text-xs font-bold text-[#ba1a1a] transition hover:bg-[#ffdad6]"
                  >
                    Reject
                  </button>

                  <button
                    @click="approveRequest(request)"
                    class="rounded-lg bg-[#124170] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#002b51]"
                  >
                    Approve
                  </button>

                </div>

              </div>

            </div>
          </div>

          <!-- CIRCULATION FLOW -->
          <div
            class="rounded-2xl border border-[#d1e8db] bg-white p-6 shadow-sm"
          >

            <div class="mb-6">
              <h2 class="font-extrabold text-[#124170]">
                Circulation Flow
              </h2>

              <p class="mt-1 text-xs text-[#737780]">
                Borrowing and returning activity this week.
              </p>
            </div>

            <!-- Simple chart -->
            <div class="flex h-56 items-end gap-3 border-b border-l border-[#d1e8db] px-4 pb-0">

              <div class="flex h-full flex-1 flex-col justify-end gap-2">
                <div class="rounded-t-lg bg-[#124170] h-[55%]"></div>
                <span class="text-center text-xs text-[#737780]">Mon</span>
              </div>

              <div class="flex h-full flex-1 flex-col justify-end gap-2">
                <div class="rounded-t-lg bg-[#25657e] h-[72%]"></div>
                <span class="text-center text-xs text-[#737780]">Tue</span>
              </div>

              <div class="flex h-full flex-1 flex-col justify-end gap-2">
                <div class="rounded-t-lg bg-[#124170] h-[63%]"></div>
                <span class="text-center text-xs text-[#737780]">Wed</span>
              </div>

              <div class="flex h-full flex-1 flex-col justify-end gap-2">
                <div class="rounded-t-lg bg-[#25657e] h-[82%]"></div>
                <span class="text-center text-xs text-[#737780]">Thu</span>
              </div>

              <div class="flex h-full flex-1 flex-col justify-end gap-2">
                <div class="rounded-t-lg bg-[#124170] h-[70%]"></div>
                <span class="text-center text-xs text-[#737780]">Fri</span>
              </div>

              <div class="flex h-full flex-1 flex-col justify-end gap-2">
                <div class="rounded-t-lg bg-[#25657e] h-[45%]"></div>
                <span class="text-center text-xs text-[#737780]">Sat</span>
              </div>

              <div class="flex h-full flex-1 flex-col justify-end gap-2">
                <div class="rounded-t-lg bg-[#d1e8db] h-[30%]"></div>
                <span class="text-center text-xs text-[#737780]">Sun</span>
              </div>

            </div>

          </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-6 xl:col-span-4">

          <!-- QUICK ACTIONS -->
          <div
            class="rounded-2xl border border-[#d1e8db] bg-white p-6 shadow-sm"
          >

            <h2 class="font-extrabold text-[#124170]">
              Quick Actions
            </h2>

            <div class="mt-4 grid grid-cols-2 gap-3">

              <button
                class="flex flex-col items-center gap-2 rounded-xl border border-[#d1e8db] p-4 transition hover:bg-[#e2f9ec]"
              >
                <span class="material-symbols-outlined text-[#124170]">
                  add_circle
                </span>

                <span class="text-xs font-bold">
                  Add Book
                </span>
              </button>

              <button
                class="flex flex-col items-center gap-2 rounded-xl border border-[#d1e8db] p-4 transition hover:bg-[#e2f9ec]"
              >
                <span class="material-symbols-outlined text-[#124170]">
                  barcode_scanner
                </span>

                <span class="text-xs font-bold">
                  Scan Book
                </span>
              </button>

              <button
                class="flex flex-col items-center gap-2 rounded-xl border border-[#d1e8db] p-4 transition hover:bg-[#e2f9ec]"
              >
                <span class="material-symbols-outlined text-[#25657e]">
                  person_add
                </span>

                <span class="text-xs font-bold">
                  Add Member
                </span>
              </button>

              <button
                class="flex flex-col items-center gap-2 rounded-xl border border-[#d1e8db] p-4 transition hover:bg-[#e2f9ec]"
              >
                <span class="material-symbols-outlined text-[#25657e]">
                  assessment
                </span>

                <span class="text-xs font-bold">
                  Reports
                </span>
              </button>

            </div>
          </div>

          <!-- OVERDUE ALERTS -->
          <div
            class="rounded-2xl border border-[#d1e8db] bg-white p-6 shadow-sm"
          >

            <div class="flex items-center justify-between">

              <div>
                <h2 class="font-extrabold text-[#124170]">
                  Overdue Alerts
                </h2>

                <p class="mt-1 text-xs text-[#737780]">
                  Students needing reminders.
                </p>
              </div>

              <span
                class="rounded-full bg-[#ffdad6] px-2.5 py-1 text-xs font-bold text-[#ba1a1a]"
              >
                9 total
              </span>

            </div>

            <div class="mt-5 space-y-3">

              <div
                v-for="item in overdueAlerts"
                :key="item.name"
                class="rounded-xl bg-[#e8fff2] p-4"
              >

                <div class="flex items-start justify-between gap-3">

                  <div>
                    <p class="text-sm font-bold text-[#0c1f17]">
                      {{ item.name }}
                    </p>

                    <p class="mt-1 text-xs text-[#737780]">
                      {{ item.book }}
                    </p>

                    <p class="mt-2 text-xs font-bold text-[#ba1a1a]">
                      {{ item.days }}
                    </p>
                  </div>

                  <button
                    @click="sendAlert(item)"
                    class="rounded-lg border border-[#d1e8db] bg-white px-3 py-2 text-xs font-bold text-[#124170] hover:bg-[#e2f9ec]"
                  >
                    Remind
                  </button>

                </div>

              </div>

            </div>
          </div>

          <!-- SCANNER -->
          <div
            class="rounded-2xl bg-[#124170] p-6 text-white shadow-sm"
          >

            <div class="flex items-start gap-4">

              <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10"
              >
                <span class="material-symbols-outlined text-[#9bf5c1]">
                  barcode_scanner
                </span>
              </div>

              <div>
                <p class="font-bold">
                  Scanner Ready
                </p>

                <p class="mt-1 text-xs leading-5 text-white/70">
                  Scan a book barcode to quickly view inventory,
                  borrower, and circulation details.
                </p>
              </div>

            </div>

            <button
              class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-[#9bf5c1] py-3 text-sm font-extrabold text-[#00311d] transition hover:opacity-90"
            >
              <span class="material-symbols-outlined text-[19px]">
                qr_code_scanner
              </span>

              Open Scanner
            </button>

          </div>

        </div>

      </section>

    </main>
  </div>
</template>