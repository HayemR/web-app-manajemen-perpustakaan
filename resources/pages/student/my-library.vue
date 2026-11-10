<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import StudentLayout from '../../components/student/StudentLayout.vue'
import StudentPageHeading from '../../components/student/StudentPageHeading.vue'

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
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAOgxKinXrk18jmiPCr--Mgv5nUdiW-6WAYOVJO_68pKGyK8LiFaBbUY5ufBVGMNl9xugzqbDeODy7PsQUTZIm8ocBaPDYFvt3gGplV2sOqrvZReDW1qLdVKzH9Vw8OaC5F3KFd4SATvUBxJAij5AgWYAElLp-QU8VA31Vw2755j859pz1txOi01bg5oSNsz3iBh1N1HtWASoDOmpBgFpITVU1lobjrvrDeT1cEY-3SAgk38fprTrX2QA',
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
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAqJHywXo533vqEBIR-vDv6CTcs1ATd3vuIAkdpqvHhkF_CsTpnYClwlc2MYqISwTUq00d-PntcUVrSDPHOmfcc8hd-c8j7VVs2TddlLkVhYJSzb11ygixYyoS_xej6IlkXhcN0kz8rCHqFZfxU_7f5JnyGsov8BTwZroVUMIIJtVGU7CW9Jzsu2zfgDTN1EMONi_8MaEhu1vH3Q6uiI1a2SYt15xphqOz7kaHb4iTbJDSOKT4Fg-Hixg',
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
  <StudentLayout>

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
        class="fixed right-6 top-24 z-[60] flex items-center gap-2 rounded-xl bg-[#20384D] px-4 py-3 text-white shadow-xl"
      >
        <span class="material-symbols-outlined text-[#67C090]">
          check_circle
        </span>
        <span class="text-sm font-semibold">
          {{ toastMessage }}
        </span>
      </div>
    </Transition>

    <!-- Main -->
    <main class="pt-20">
      <div class="mx-auto flex w-full max-w-[1200px] flex-col gap-6 px-4 py-6 md:px-5">
        <!-- Page Heading -->
        <StudentPageHeading
          title="Buku yang Dipinjam"
          description="Kelola buku yang sedang kamu pinjam dan lihat riwayat peminjamanmu."
          eyebrow="Perpustakaanku"
        >
          <template #actions>
            <div class="inline-flex self-start rounded-xl bg-white p-1 shadow-sm md:self-auto">
              <button
                type="button"
                class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold transition-all"
                :class="
                  activeTab === 'borrowed'
                    ? 'bg-[#315D78] text-white shadow-sm'
                    : 'text-[#637587] hover:text-[#20384D]'
                "
                @click="switchTab('borrowed')"
              >
                <span>Sedang Dipinjam</span>
                <span
                  class="rounded-full px-1.5 py-0.5 text-[11px]"
                  :class="
                    activeTab === 'borrowed'
                      ? 'bg-white/20 text-white'
                      : 'bg-[#EAF0F4] text-[#637587]'
                  "
                >
                  {{ activeBookCount }}
                </span>
              </button>

              <button
                type="button"
                class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold transition-all"
                :class="
                  activeTab === 'history'
                    ? 'bg-[#315D78] text-white shadow-sm'
                    : 'text-[#637587] hover:text-[#20384D]'
                "
                @click="switchTab('history')"
              >
                <span>Loan History</span>

                <span
                  class="rounded-full px-1.5 py-0.5 text-[11px]"
                  :class="
                    activeTab === 'history'
                      ? 'bg-white/20 text-white'
                      : 'bg-[#EAF0F4] text-[#637587]'
                  "
                >
                  14
                </span>
              </button>
            </div>
          </template>
        </StudentPageHeading>

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
                    class="text-sm font-semibold text-[#20384D] md:text-base"
                  >
                    1 buku jatuh tempo dalam 5 hari • 1 buku terlambat 2 hari
                  </span>

                  <span
                    class="rounded-full bg-[#E55353] px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-white"
                  >
                    Action Needed
                  </span>
                </div>

                <p class="mt-0.5 text-xs leading-5 text-[#637587]">
                  Segera kembalikan buku yang terlambat ke meja sirkulasi
                  perpustakaan.
                </p>
              </div>
            </div>

            <button
              class="shrink-0 self-start rounded-xl bg-[#EAF0F4] px-4 py-2 text-sm font-semibold text-[#20384D] transition-colors hover:bg-[#D1E8DB] lg:self-auto"
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
                  class="text-[11px] font-semibold uppercase tracking-wider text-[#637587]"
                >
                  Borrowing Quota
                </span>

                <div
                  class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#EAF0F4] text-[#315D78]"
                >
                  <span class="material-symbols-outlined text-lg">
                    menu_book
                  </span>
                </div>
              </div>

              <div>
                <div class="flex items-baseline justify-between">
                  <span class="text-xl font-semibold text-[#20384D]">
                    {{ activeBookCount }} of 3 Books
                  </span>

                  <span class="text-[11px] font-semibold text-[#315D78]">
                    {{ quotaPercentage }}% Active
                  </span>
                </div>

                <div
                  class="mt-2 h-2 w-full overflow-hidden rounded-full bg-[#EAF0F4]"
                >
                  <div
                    class="h-full rounded-full bg-[#315D78] transition-all"
                    :style="{ width: `${quotaPercentage}%` }"
                  />
                </div>
              </div>

              <span class="mt-2 text-xs text-[#637587]">
                {{ 3 - activeBookCount }} slot peminjaman masih tersedia
              </span>
            </div>

            <!-- Balance -->
            <div
              class="flex flex-col justify-between rounded-2xl bg-white p-5 shadow-sm"
            >
              <div class="mb-2 flex items-center justify-between">
                <span
                  class="text-[11px] font-semibold uppercase tracking-wider text-[#637587]"
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

                <span class="mt-1 block text-xs text-[#637587]">
                  Denda dihitung berdasarkan keterlambatan pengembalian.
                </span>
              </div>

              <span class="mt-2 text-xs font-semibold text-[#637587]">
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
                  <span class="material-symbols-outlined text-[#315D78]">
                    bookmark
                  </span>

                  <h2
                    class="text-xl font-semibold text-[#20384D]"
                  >
                    Active Physical Book Loans
                  </h2>

                  <span
                    class="rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-[#315D78] shadow-sm"
                  >
                    {{ activeBookCount }} items
                  </span>
                </div>

                <div
                  class="rounded-xl bg-white px-3 py-2 text-xs font-semibold text-[#637587] shadow-sm"
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
                  class="relative h-52 w-full shrink-0 overflow-hidden rounded-xl bg-[#EAF0F4] shadow-sm sm:w-36"
                >
                  <img
                    v-if="book.image"
                    :src="book.image"
                    :alt="book.title"
                    class="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                  />

                  <div
                    v-else
                    class="flex h-full w-full flex-col justify-end bg-[#315D78] p-3 text-white"
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

                  <div class="absolute left-2 top-2 rounded bg-white/90 px-1.5 py-0.5 font-mono text-[11px] font-semibold text-[#20384D] shadow-sm backdrop-blur-sm">
                    {{ book.code }}
                  </div>
                </div>

                <!-- Information -->
                <div class="flex min-w-0 flex-1 flex-col justify-between">
                  <div>
                    <div class="mb-1 flex flex-wrap items-center justify-between gap-2">
                      <span
                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold"
                        :class="
                          book.status === 'overdue'
                            ? 'bg-[#FDE8E8] text-[#E55353]'
                            : 'bg-[#EDF3F5] text-[#006C45]'
                        "
                      >
                        <span
                          class="h-1.5 w-1.5 rounded-full"
                          :class="book.status === 'overdue' ? 'bg-[#E55353]' : 'bg-[#67C090]'"
                        />

                        {{ book.statusText }} • Jatuh tempo {{ book.dueDate }}
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
                        class="font-mono text-[11px] text-[#637587]"
                      >
                        ID Peminjaman: #{{ book.loanId }}
                      </span>
                    </div>

                    <h3 class="truncate text-base font-semibold text-[#20384D] transition-colors hover:text-[#315D78]">
                      {{ book.title }}
                    </h3>

                    <span class="mt-0.5 block text-xs text-[#637587]">
                      Oleh {{ book.author }} • {{ book.category }}
                    </span>

                    <!-- Metadata -->
                    <div
                      class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 rounded-xl p-3 text-[#637587]"
                      :class="book.status === 'overdue' ? 'bg-[#FDE8E8]/40' : 'bg-[#EAF0F4]'"
                    >
                      <div class="flex flex-col">
                        <span class="text-[11px] font-semibold">
                          Dipinjam Pada
                        </span>
                        <span class="text-xs text-[#293A49]">
                          {{ book.borrowedOn }}
                        </span>
                      </div>

                      <div class="flex flex-col">
                        <span class="text-[11px] font-semibold">
                          Tempat Pengembalian
                        </span>
                        <span class="text-xs text-[#293A49]">
                          {{ book.returnStation }}
                        </span>
                      </div>

                      <div class="flex flex-col">
                        <span class="text-[11px] font-semibold">
                          Status
                        </span>
                        <span
                          class="text-xs font-semibold"
                          :class="book.status === 'overdue' ? 'text-[#E55353]' : 'text-[#006C45]'"
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
                          Terkait Mata Pelajaran
                        </span>
                        <span class="text-xs text-[#293A49]">
                          {{ book.course }}
                        </span>
                      </div>
                    </div>

                    <div
                      class="mt-2 flex items-start gap-1.5"
                      :class="book.status === 'overdue' ? 'text-[#E55353]' : 'text-[#637587]'"
                    >
                      <span class="material-symbols-outlined mt-0.5 text-base">
                        {{ book.status === 'overdue' ? 'error' : 'info' }}
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
                  <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-[#EAF0F4] pt-3">
                    <RouterLink :to="`/book/${book.id}`" class="rounded-xl bg-[#EAF0F4] px-3 py-2 text-sm font-semibold text-[#20384D] transition-colors hover:bg-[#D1E8DB]">
                        Detail Buku
                    </RouterLink>
                  </div>
                </div>
              </article>
            </div>

            <!-- Sidebar -->
            <aside class="flex flex-col gap-5 lg:col-span-4">
              <!-- Student Card -->
              <div class="flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#315D78]">
                      badge
                    </span>

                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#637587]">
                      Kartu Siswa
                    </span>
                  </div>

                  <span class="inline-flex items-center gap-1 rounded-full bg-[#EDF3F5] px-2 py-0.5 text-[11px] font-semibold text-[#006C45]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]" />
                    Aktif
                  </span>
                </div>

                <div class="flex items-center gap-3">
                  <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#315D78] text-lg font-bold text-white shadow-sm">
                    AD
                  </div>

                  <div class="flex min-w-0 flex-col">
                    <span class="text-xl font-semibold leading-tight text-[#20384D]">
                      Adel
                    </span>

                    <span class="text-xs text-[#637587]">
                      Siswa
                    </span>

                    <span class="mt-0.5 font-mono text-xs text-[#315D78]">
                      LIB-8849
                    </span>
                  </div>
                </div>

                <div class="rounded-xl bg-[#EAF0F4] p-3 text-xs leading-5 text-[#637587]">
                  <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-base text-[#006C45]">
                      info
                    </span>

                    <span>
                      Kartu siswa digunakan untuk proses peminjaman di perpustakaan.
                      Peminjaman hanya dapat dilakukan setelah siswa berada di area
                      perpustakaan dan melakukan scan kartu.
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
                  <span class="material-symbols-outlined text-[#20384D]">
                    gavel
                  </span>

                  <h3 class="text-base font-semibold text-[#20384D]">
                    Kebijakan Peminjaman
                  </h3>
                </div>

                <ul class="flex flex-col gap-3">
                  <li class="flex items-start gap-2">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#EAF0F4] text-[#315D78]">
                      <span class="material-symbols-outlined text-sm">
                        calendar_today
                      </span>
                    </div>

                    <div>
                      <span class="block text-sm font-semibold text-[#20384D]">
                        Masa Peminjaman 14 Hari
                      </span>
                      <span class="text-xs leading-5 text-[#637587]">
                        Buku dapat dipinjam selama maksimal 14 hari.
                      </span>
                    </div>
                  </li>

                  <li class="flex items-start gap-2">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#EAF0F4] text-[#315D78]">
                      <span class="material-symbols-outlined text-sm">
                        format_list_numbered
                      </span>
                    </div>

                    <div>
                      <span class="block text-sm font-semibold text-[#20384D]">
                        Maksimal 3 Buku Fisik
                      </span>
                      <span class="text-xs leading-5 text-[#637587]">
                        Maksimal 3 buku dapat dipinjam secara bersamaan.
                      </span>
                    </div>
                  </li>

                  <li class="flex items-start gap-2">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#EAF0F4] text-[#315D78]">
                      <span class="material-symbols-outlined text-sm">
                        location_on
                      </span>
                    </div>

                    <div>
                      <span class="block text-sm font-semibold text-[#20384D]">
                        Peminjaman di Perpustakaan
                      </span>
                      <span class="text-xs leading-5 text-[#637587]">
                        Peminjaman harus dilakukan langsung di perpustakaan menggunakan scan kartu siswa.
                      </span>
                    </div>
                  </li>

                  <li class="flex items-start gap-2">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-[#FDE8E8] text-[#E55353]">
                      <span class="material-symbols-outlined text-sm">
                        monetization_on
                      </span>
                    </div>

                    <div>
                      <span class="block text-sm font-semibold text-[#E55353]">
                        Denda Keterlambatan
                      </span>
                      <span class="text-xs leading-5 text-[#637587]">
                        Keterlambatan dapat dikenakan denda sesuai kebijakan perpustakaan.
                      </span>
                    </div>
                  </li>
                </ul>
              </div>
            </aside>
          </section>

          <!-- Recently Returned -->
          <section class="flex flex-col gap-5 rounded-2xl bg-white p-5 shadow-sm md:p-6">
            <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
              <div>
                <h3 class="text-xl font-semibold text-[#20384D]">
                  Buku yang Baru Dikembalikan
                </h3>

                <p class="text-xs text-[#637587]">
                  3 buku terakhir yang sudah kamu kembalikan.
                </p>
              </div>
            </div>

            <div class="flex flex-col gap-2">
              <div
                v-for="book in loanHistory.slice(0, 3)"
                :key="book.id"
                class="flex flex-col justify-between gap-3 rounded-xl bg-[#EAF0F4] p-3 transition-colors hover:bg-[#D1E8DB] sm:flex-row sm:items-center"
              >
                <div class="flex items-center gap-3">
                  <div class="flex h-16 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white shadow-sm">
                    <img
                      v-if="book.image"
                      :src="book.image"
                      :alt="book.title"
                      class="h-full w-full object-cover"
                    />

                    <span
                      v-else-if="book.title === '1984'"
                      class="font-mono text-sm font-bold text-[#20384D]"
                    >
                      1984
                    </span>

                    <span
                      v-else
                      class="material-symbols-outlined text-2xl text-[#315D78]"
                    >
                      auto_stories
                    </span>
                  </div>

                  <div class="flex flex-col">
                    <span class="text-sm font-semibold text-[#20384D]">
                      {{ book.title }}
                    </span>

                    <span class="text-xs text-[#637587]">
                      {{ book.author }} • {{ book.category }}
                    </span>

                    <span class="mt-0.5 flex items-center gap-1 text-[11px] font-semibold text-[#006C45]">
                      <span class="material-symbols-outlined text-sm">
                        check_circle
                      </span>
                      Dikembalikan {{ book.returnedDate }} • {{ book.status }}
                    </span>
                  </div>
                </div>

                <div class="flex items-center gap-2 self-end sm:self-auto">
                  <span class="rounded-lg bg-white px-2.5 py-1 font-mono text-[11px] font-semibold text-[#20384D] shadow-sm">
                    {{ book.duration }}
                  </span>

                  <button
                    class="rounded-lg p-1.5 text-[#637587] transition-colors hover:bg-white hover:text-[#20384D]"
                    title="Pinjam lagi"
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
          <section class="flex flex-col gap-5 rounded-2xl bg-white p-5 shadow-sm md:p-6">
            <div>
              <span class="text-[11px] font-semibold uppercase tracking-wider text-[#006C45]">
                Riwayat Peminjaman
              </span>

              <h2 class="mt-1 text-2xl font-semibold tracking-tight text-[#20384D]">
                Riwayat Bacaanmu
              </h2>

              <p class="mt-1 text-sm text-[#637587]">
                Daftar buku yang pernah kamu pinjam dan kembalikan.
              </p>
            </div>

            <div class="flex flex-col gap-2">
              <div
                v-for="book in loanHistory"
                :key="book.id"
                class="flex flex-col justify-between gap-3 rounded-xl bg-[#EAF0F4] p-4 transition-colors hover:bg-[#D1E8DB] sm:flex-row sm:items-center"
              >
                <div class="flex items-center gap-3">
                  <div class="flex h-20 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white shadow-sm">
                    <img
                      v-if="book.image"
                      :src="book.image"
                      :alt="book.title"
                      class="h-full w-full object-cover"
                    />

                    <span
                      v-else-if="book.title === '1984'"
                      class="font-mono text-sm font-bold text-[#20384D]"
                    >
                      1984
                    </span>

                    <span
                      v-else
                      class="material-symbols-outlined text-2xl text-[#315D78]"
                    >
                      auto_stories
                    </span>
                  </div>

                  <div>
                    <h3 class="text-base font-semibold text-[#20384D]">
                      {{ book.title }}
                    </h3>

                    <p class="text-xs text-[#637587]">
                      {{ book.author }} • {{ book.category }}
                    </p>

                    <p class="mt-1 text-xs font-semibold text-[#006C45]">
                      Dikembalikan {{ book.returnedDate }} • {{ book.status }}
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-3 self-end sm:self-auto">
                  <span class="rounded-lg bg-white px-3 py-1.5 font-mono text-xs font-semibold text-[#20384D] shadow-sm">
                    {{ book.duration }}
                  </span>

                  <button
                    class="rounded-xl bg-white px-3 py-2 text-xs font-semibold text-[#315D78] shadow-sm transition-colors hover:bg-[#F4F6F8]"
                    @click="borrowAgain(book)"
                  >
                    Pinjam Lagi
                  </button>
                </div>
              </div>
            </div>
          </section>
        </template>
      </div>
    </main>


  </StudentLayout>
</template>