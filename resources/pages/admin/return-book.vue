<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import AdminSidebar from '../../components/adminsidebar.vue'

// =========================
// STATE SCANNER
// =========================
const videoRef = ref(null)
const stream = ref(null)

const scannerActive = ref(false)
const scannerMessage = ref('Scanner siap digunakan')
const cameraError = ref('')

const isChecking = ref(false)
const returnSuccess = ref(false)

// =========================
// DATA RETURN
// =========================
const selectedBorrower = ref('alex')

const condition = ref('good')

const borrowers = [
  {
    id: 'alex',
    name: 'Alex Morgan',
    nis: 'NIS-2023-04921',
    className: 'Class 10-A',
    status: 'Active Standing (No Prior Flags)',
    checkoutDate: '10 November 2024',
    dueDate: '24 November 2024',
    initials: 'AM',
  },
  {
    id: 'sophia',
    name: 'Sophia Patel',
    nis: 'NIS-2023-01824',
    className: 'Class 11-B',
    status: 'Active Standing',
    checkoutDate: '15 November 2024',
    dueDate: '29 November 2024',
    initials: 'SP',
  },
  {
    id: 'marcus',
    name: 'Marcus Vance',
    nis: 'NIS-2022-09412',
    className: 'Class 12-C',
    status: 'Active Standing',
    checkoutDate: '18 November 2024',
    dueDate: '02 December 2024',
    initials: 'MV',
  },
]

const selectedBorrowerData = ref(borrowers[0])

// =========================
// DATA BUKU
// =========================
const book = ref({
  inventoryCode: 'LIB-AST-520.1',
  isbn: '978-0345331359',
  title: 'Cosmos: A Personal Voyage',
  author: 'Carl Sagan',
  publisher: 'Random House / Ballantine Books',
  shelf: 'Stack 03 · Shelf B2',
  category: '520.1',
  cover:
    'https://lh3.googleusercontent.com/aida-public/AB6AXuAGMEXzq7AnW0lkhoMyUkjc9ucRb35YBl14DfcCTfVbfCk14K7OqBGb8dVj3oYyaYw1NmDzJ97hHlDo5jcqdBGDCk0714QVgR0V-Zqcw3CMBi2Jdm34DwR-1idvksJ2LRXmXPBJ3RFUP221L5mVSXgymyMZ19znmhylDPU5Hx7_XIjjyTceEAKqz5cUjmr9-vxu19yLZkh0Uz68rpn7nH7i77AcmbSaNTw3GTASYygOyFiugCr8-jkUKw',
})

const scannedBooks = [
  {
    inventoryCode: 'LIB-AST-520.1',
    isbn: '978-0345331359',
    title: 'Cosmos: A Personal Voyage',
    author: 'Carl Sagan',
    publisher: 'Random House / Ballantine Books',
    shelf: 'Stack 03 · Shelf B2',
    category: '520.1',
    borrower: 'alex',
  },
  {
    inventoryCode: 'LIB-LIT-813.5',
    isbn: '978-0061120084',
    title: 'To Kill a Mockingbird',
    author: 'Harper Lee',
    publisher: 'Harper Perennial Modern Classics',
    shelf: 'Stack 02 · Shelf C8',
    category: '813.5',
    borrower: 'sophia',
  },
  {
    inventoryCode: 'LIB-PHY-530.1',
    isbn: '978-0553380163',
    title: 'A Brief History of Time',
    author: 'Stephen Hawking',
    publisher: 'Bantam Books',
    shelf: 'Stack 01 · Shelf A2',
    category: '530.1',
    borrower: 'marcus',
  },
]

let scanIndex = 0

// =========================
// CAMERA
// =========================
async function startCamera() {
  cameraError.value = ''

  if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
    cameraError.value =
      'Browser tidak mendukung akses kamera. Gunakan Chrome atau Edge.'
    return
  }

  try {
    stream.value = await navigator.mediaDevices.getUserMedia({
      video: {
        facingMode: {
          ideal: 'environment',
        },
      },
      audio: false,
    })

    scannerActive.value = true

    if (videoRef.value) {
      videoRef.value.srcObject = stream.value
      await videoRef.value.play()
    }

    scannerMessage.value = 'Kamera aktif — arahkan barcode ke kotak scanner'
  } catch (error) {
    console.error(error)

    cameraError.value =
      'Kamera tidak dapat diakses. Pastikan izin kamera sudah diberikan.'
  }
}

function stopCamera() {
  if (stream.value) {
    stream.value.getTracks().forEach((track) => track.stop())
    stream.value = null
  }

  scannerActive.value = false
}

// =========================
// SIMULASI DECODE BARCODE
// =========================
// Sementara tombol Capture & Decode
// mengganti data buku berdasarkan sample.
// Nanti bagian ini bisa diganti dengan
// library barcode asli / API Laravel.
function captureBarcode() {
  if (isChecking.value) return

  isChecking.value = true
  scannerMessage.value = 'Membaca barcode...'

  setTimeout(() => {
    const scannedBook = scannedBooks[scanIndex]

    book.value = {
      ...scannedBook,
    }

    selectedBorrower.value = scannedBook.borrower

    const borrower = borrowers.find(
      (item) => item.id === scannedBook.borrower,
    )

    if (borrower) {
      selectedBorrowerData.value = borrower
    }

    scannerMessage.value = 'Barcode berhasil dibaca — data ditemukan'
    isChecking.value = false

    scanIndex++

    if (scanIndex >= scannedBooks.length) {
      scanIndex = 0
    }
  }, 900)
}

// =========================
// SWITCH CAMERA
// =========================
async function switchCamera() {
  stopCamera()
  await startCamera()
}

// =========================
// MANUAL ENTRY
// =========================
function manualEntry() {
  const isbn = window.prompt(
    'Masukkan ISBN atau kode inventaris buku:',
  )

  if (!isbn) return

  const foundBook = scannedBooks.find(
    (item) =>
      item.isbn === isbn ||
      item.inventoryCode === isbn,
  )

  if (!foundBook) {
    scannerMessage.value = 'Buku tidak ditemukan'
    return
  }

  book.value = {
    ...foundBook,
  }

  selectedBorrower.value = foundBook.borrower

  const borrower = borrowers.find(
    (item) => item.id === foundBook.borrower,
  )

  if (borrower) {
    selectedBorrowerData.value = borrower
  }

  scannerMessage.value = 'Data buku ditemukan melalui input manual'
}

// =========================
// BORROWER CHANGE
// =========================
function changeBorrower() {
  const borrower = borrowers.find(
    (item) => item.id === selectedBorrower.value,
  )

  if (borrower) {
    selectedBorrowerData.value = borrower
  }
}

// =========================
// SIMPAN PENGEMBALIAN
// =========================
function confirmReturn() {
  if (isChecking.value) return

  isChecking.value = true
  returnSuccess.value = false

  setTimeout(() => {
    isChecking.value = false
    returnSuccess.value = true

    setTimeout(() => {
      returnSuccess.value = false
    }, 2400)
  }, 800)
}

// =========================
// WAIVE PENALTY
// =========================
function waivePenalty() {
  window.alert('Denda keterlambatan berhasil ditandai untuk dihapus.')
}

// =========================
// UPDATE BORROWER
// =========================
function updateBorrower() {
  changeBorrower()
}

// =========================
// CLEANUP
// =========================
onMounted(() => {
  // Kamera tidak langsung dinyalakan
  // supaya browser tidak langsung meminta izin.
})

onBeforeUnmount(() => {
  stopCamera()
})
</script>

<template>
  <div class="min-h-screen bg-[#e8fff2] font-[Plus_Jakarta_Sans] text-[#0c1f17]">

    <!-- SIDEBAR -->
    <AdminSidebar />

    <!-- CONTENT -->
    <div class="pl-64 flex min-h-screen flex-col">

      <!-- HEADER -->
      <header
        class="fixed left-64 right-0 top-0 z-40 h-16 bg-white/90 px-8 shadow-[0_1px_8px_rgba(0,0,0,0.04)] backdrop-blur-xl"
      >
        <div class="flex h-16 items-center justify-between gap-6">

          <div class="flex items-center gap-2 text-[#42474f]">
            <span class="material-symbols-outlined text-[20px] text-[#25657e]">
              assignment_return
            </span>

            <span class="text-xs text-[#737780]">/</span>

            <span class="text-[13px] font-semibold text-[#0c1f17]">
              Return Book (Scan Barcode)
            </span>
          </div>

          <div class="flex items-center gap-6">

            <!-- SEARCH -->
            <div class="relative w-80">
              <span
                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-[#737780]"
              >
                search
              </span>

              <input
                class="w-full rounded-xl bg-[#e2f9ec] py-2 pl-10 pr-4 text-xs text-[#0c1f17] placeholder:text-[#737780] focus:outline-none focus:ring-2 focus:ring-[#25657e]/30"
                placeholder="Cari katalog, ISBN, ID siswa..."
                type="text"
              />
            </div>

            <!-- USER -->
            <div class="flex items-center gap-3">

              <button
                class="relative flex h-9 w-9 items-center justify-center rounded-xl text-[#42474f] hover:bg-[#dcf3e6]"
              >
                <span class="material-symbols-outlined text-[20px]">
                  notifications
                </span>

                <span
                  class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#ba1a1a]"
                ></span>
              </button>

              <div class="h-6 w-px bg-[#c3c6d0]"></div>

              <div class="flex items-center gap-2">
                <div
                  class="flex h-8 w-8 items-center justify-center rounded-full bg-[#002b51]"
                >
                  <span class="material-symbols-outlined text-[18px] text-white">
                    person
                  </span>
                </div>

                <div class="hidden flex-col text-left md:flex">
                  <span class="text-xs font-semibold text-[#0c1f17]">
                    S. Jenkins
                  </span>

                  <span class="text-[11px] text-[#737780]">
                    Pustakawan
                  </span>
                </div>
              </div>

            </div>
          </div>
        </div>
      </header>

      <!-- MAIN -->
      <main class="flex-1 bg-[#e8fff2] px-8 pb-8 pt-24">

        <!-- PAGE HEADER -->
        <div class="mb-6 flex flex-col gap-5">

          <div class="flex flex-wrap items-center justify-between gap-4">

            <div>
              <div class="mb-1 flex items-center gap-1">
                <span
                  class="inline-flex items-center rounded-full bg-[#dcf3e6] px-2 py-0.5 text-[10px] font-bold text-[#00311d]"
                >
                  <span
                    class="mr-1.5 h-1.5 w-1.5 animate-pulse rounded-full bg-[#64bd8d]"
                  ></span>

                  SIRKULASI MASUK
                </span>

                <span class="text-xs text-[#737780]">•</span>

                <span class="text-xs font-medium text-[#42474f]">
                  Terminal #04 (Meja Timur)
                </span>
              </div>

              <h1
                class="text-[32px] font-bold tracking-tight text-[#002b51]"
              >
                Pengembalian Buku
              </h1>

              <p class="mt-1 max-w-2xl text-sm text-[#42474f]">
                Scan buku yang dikembalikan untuk memverifikasi data
                peminjaman, mengecek tanggal jatuh tempo, kondisi buku,
                dan memperbarui inventaris secara otomatis.
              </p>
            </div>

            <!-- METRICS -->
            <div class="flex flex-wrap items-center gap-3">

              <!-- RETURNS -->
              <div
                class="flex items-center gap-3 rounded-xl bg-white p-3 shadow-sm"
              >
                <div
                  class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#dcf3e6] text-[#25657e]"
                >
                  <span class="material-symbols-outlined text-[22px]">
                    assignment_return
                  </span>
                </div>

                <div class="flex flex-col">
                  <span class="text-[22px] font-bold leading-tight text-[#002b51]">
                    38
                  </span>

                  <span class="text-[10px] font-bold text-[#737780]">
                    Pengembalian Hari Ini
                  </span>
                </div>
              </div>

              <!-- REVIEW -->
              <div
                class="flex items-center gap-3 rounded-xl bg-white p-3 shadow-sm"
              >
                <div
                  class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#ffdad6] text-[#ba1a1a]"
                >
                  <span class="material-symbols-outlined text-[22px]">
                    pending_actions
                  </span>
                </div>

                <div class="flex flex-col">
                  <span class="text-[22px] font-bold leading-tight text-[#ba1a1a]">
                    3
                  </span>

                  <span class="text-[10px] font-bold text-[#737780]">
                    Perlu Ditinjau
                  </span>
                </div>
              </div>

              <!-- SCANNER STATUS -->
              <div
                class="flex items-center gap-2 rounded-xl bg-white p-3 shadow-sm"
              >
                <div
                  class="h-2.5 w-2.5 rounded-full bg-[#64bd8d] shadow-[0_0_8px_rgba(100,189,141,0.8)]"
                ></div>

                <div class="flex flex-col">
                  <span class="text-xs font-semibold text-[#002b51]">
                    Scanner Siap
                  </span>

                  <span class="text-[10px] text-[#737780]">
                    Barcode & RFID Aktif
                  </span>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- MAIN GRID -->
        <div class="grid grid-cols-12 items-start gap-6">

          <!-- ========================= -->
          <!-- LEFT : SCANNER -->
          <!-- ========================= -->
          <div class="col-span-12 flex flex-col gap-4 xl:col-span-5">

            <div class="rounded-xl bg-white p-5 shadow-sm">

              <!-- SCANNER HEADER -->
              <div class="mb-4 flex items-center justify-between">

                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[22px] text-[#25657e]">
                    center_focus_strong
                  </span>

                  <h2 class="text-base font-semibold text-[#002b51]">
                    Kamera Scanner
                  </h2>
                </div>

                <div
                  class="flex items-center gap-1.5 rounded-full bg-[#e2f9ec] px-2.5 py-1 text-[10px] font-bold text-[#00311d]"
                >
                  <span
                    class="h-2 w-2 rounded-full"
                    :class="scannerActive ? 'bg-[#64bd8d] animate-pulse' : 'bg-[#737780]'"
                  ></span>

                  {{ scannerActive ? 'KAMERA AKTIF' : 'SCANNER SIAP' }}
                </div>

              </div>

              <!-- CAMERA VIEW -->
              <div
                class="relative aspect-[4/3] w-full overflow-hidden rounded-xl bg-[#21342c] shadow-inner"
              >

                <!-- ACTUAL VIDEO -->
                <video
                  ref="videoRef"
                  class="absolute inset-0 h-full w-full object-cover"
                  autoplay
                  muted
                  playsinline
                  :class="scannerActive ? 'opacity-100' : 'opacity-0'"
                ></video>

                <!-- CAMERA OFF SCREEN -->
                <div
                  v-if="!scannerActive"
                  class="absolute inset-0 flex flex-col items-center justify-center bg-[#21342c]"
                >
                  <span
                    class="material-symbols-outlined mb-3 text-5xl text-[#7fd9a7]"
                  >
                    qr_code_scanner
                  </span>

                  <p class="text-sm font-semibold text-white">
                    Kamera belum aktif
                  </p>

                  <p class="mt-1 text-xs text-white/50">
                    Tekan tombol "Aktifkan Kamera"
                  </p>
                </div>

                <!-- GRID -->
                <div
                  class="pointer-events-none absolute inset-0 opacity-20"
                  style="
                    background-image: radial-gradient(
                      #9bf5c1 1px,
                      transparent 1px
                    );
                    background-size: 16px 16px;
                  "
                ></div>

                <!-- SCANNER FRAME -->
                <div
                  class="pointer-events-none absolute left-1/2 top-1/2 h-48 w-64 -translate-x-1/2 -translate-y-1/2"
                >

                  <!-- TOP LEFT -->
                  <div
                    class="absolute left-0 top-0 h-8 w-8 rounded-tl-lg border-l-4 border-t-4 border-[#7fd9a7]"
                  ></div>

                  <!-- TOP RIGHT -->
                  <div
                    class="absolute right-0 top-0 h-8 w-8 rounded-tr-lg border-r-4 border-t-4 border-[#7fd9a7]"
                  ></div>

                  <!-- BOTTOM LEFT -->
                  <div
                    class="absolute bottom-0 left-0 h-8 w-8 rounded-bl-lg border-b-4 border-l-4 border-[#7fd9a7]"
                  ></div>

                  <!-- BOTTOM RIGHT -->
                  <div
                    class="absolute bottom-0 right-0 h-8 w-8 rounded-br-lg border-b-4 border-r-4 border-[#7fd9a7]"
                  ></div>

                  <!-- LASER -->
                  <div
                    v-if="scannerActive"
                    class="absolute left-0 top-1/2 h-0.5 w-full -translate-y-1/2 animate-pulse bg-[#9bf5c1] shadow-[0_0_12px_#7fd9a7]"
                  ></div>

                  <!-- CROSSHAIR -->
                  <div
                    class="absolute left-1/2 top-1/2 h-3 w-3 -translate-x-1/2 -translate-y-1/2 rounded-full border border-dashed border-[#7fd9a7]"
                  >
                    <div
                      class="absolute left-1/2 top-1/2 h-1 w-1 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#9bf5c1]"
                    ></div>
                  </div>
                </div>

                <!-- CAMERA HUD -->
                <div
                  class="absolute bottom-3 left-3 right-3 flex items-center justify-between rounded-lg bg-[#21342c]/80 px-3 py-1.5 backdrop-blur-md"
                >
                  <span class="flex items-center gap-1.5 text-[10px] font-semibold text-[#dff6e9]">
                    <span
                      class="material-symbols-outlined text-[16px] text-[#9bf5c1]"
                    >
                      sensors
                    </span>

                    {{ scannerMessage }}
                  </span>

                  <span
                    class="text-[10px] font-bold uppercase tracking-wider text-[#7fd9a7]"
                  >
                    {{ scannerActive ? 'Scan Aktif' : 'Siap Scan' }}
                  </span>
                </div>

              </div>

              <!-- CAMERA ERROR -->
              <div
                v-if="cameraError"
                class="mt-3 rounded-xl bg-[#ffdad6] p-3 text-xs font-medium text-[#93000a]"
              >
                <span class="material-symbols-outlined mr-1 align-middle text-[16px]">
                  error
                </span>

                {{ cameraError }}
              </div>

              <!-- READOUT -->
              <div
                class="mt-4 flex items-center justify-between rounded-xl bg-[#e2f9ec] p-2.5"
              >
                <div class="flex min-w-0 items-center gap-2">

                  <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#dcf3e6] text-[#64bd8d]"
                  >
                    <span class="material-symbols-outlined text-[18px]">
                      verified
                    </span>
                  </div>

                  <div class="flex min-w-0 flex-col">
                    <span class="text-[10px] font-bold text-[#737780]">
                      Hasil Pembacaan
                    </span>

                    <span
                      class="truncate font-mono text-xs font-bold text-[#002b51]"
                    >
                      {{ book.inventoryCode }} · MATCH
                    </span>
                  </div>
                </div>

                <span
                  class="rounded-full bg-[#dcf3e6] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[#00311d]"
                >
                  100% Match
                </span>
              </div>

              <!-- SCANNER BUTTONS -->
              <div class="mt-4 flex flex-col gap-2">

                <!-- START CAMERA -->
                <button
                  v-if="!scannerActive"
                  type="button"
                  @click="startCamera"
                  class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#124170] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#002b51]"
                >
                  <span class="material-symbols-outlined text-[20px]">
                    photo_camera
                  </span>

                  Aktifkan Kamera
                </button>

                <!-- CAPTURE -->
                <button
                  type="button"
                  :disabled="isChecking"
                  @click="captureBarcode"
                  class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#124170] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#002b51] disabled:cursor-not-allowed disabled:opacity-60"
                >
                  <span
                    class="material-symbols-outlined text-[20px]"
                    :class="{ 'animate-spin': isChecking }"
                  >
                    {{ isChecking ? 'sync' : 'qr_code_scanner' }}
                  </span>

                  {{
                    isChecking
                      ? 'Membaca Barcode...'
                      : 'Capture & Decode Barcode'
                  }}
                </button>

                <!-- SECONDARY -->
                <div class="grid grid-cols-2 gap-2">

                  <button
                    type="button"
                    @click="switchCamera"
                    class="flex items-center justify-center gap-1.5 rounded-xl bg-[#e2f9ec] px-2 py-2 text-xs font-semibold text-[#25657e] transition hover:bg-[#dcf3e6]"
                  >
                    <span class="material-symbols-outlined text-[18px]">
                      cameraswitch
                    </span>

                    Ganti Kamera
                  </button>

                  <button
                    type="button"
                    @click="manualEntry"
                    class="flex items-center justify-center gap-1.5 rounded-xl bg-[#e2f9ec] px-2 py-2 text-xs font-semibold text-[#25657e] transition hover:bg-[#dcf3e6]"
                  >
                    <span class="material-symbols-outlined text-[18px]">
                      keyboard
                    </span>

                    Input Manual
                  </button>

                </div>

              </div>

            </div>

            <!-- RECENT RETURNS -->
            <div class="rounded-xl bg-white p-4 shadow-sm">

              <div class="mb-2 flex items-center justify-between">
                <span
                  class="text-[10px] font-bold uppercase tracking-wider text-[#737780]"
                >
                  Diproses pada Shift Ini
                </span>

                <button
                  type="button"
                  class="text-[10px] font-semibold text-[#25657e] hover:underline"
                >
                  Lihat Riwayat
                </button>
              </div>

              <div class="flex gap-2 overflow-x-auto pb-1">

                <div
                  class="flex min-w-[210px] shrink-0 items-center gap-2 rounded-lg bg-[#e2f9ec] p-2"
                >
                  <div
                    class="flex h-10 w-8 items-center justify-center rounded bg-[#124170]/10 text-xs font-bold text-[#002b51]"
                  >
                    ENG
                  </div>

                  <div class="flex min-w-0 flex-col">
                    <span class="truncate text-xs font-semibold text-[#002b51]">
                      1984 (Centennial Ed.)
                    </span>

                    <span class="text-[11px] text-[#737780]">
                      Dikembalikan · Rak 4B
                    </span>
                  </div>
                </div>

                <div
                  class="flex min-w-[210px] shrink-0 items-center gap-2 rounded-lg bg-[#e2f9ec] p-2"
                >
                  <div
                    class="flex h-10 w-8 items-center justify-center rounded bg-[#25657e]/10 text-xs font-bold text-[#25657e]"
                  >
                    BIO
                  </div>

                  <div class="flex min-w-0 flex-col">
                    <span class="truncate text-xs font-semibold text-[#002b51]">
                      Campbell Biology 11th
                    </span>

                    <span class="text-[11px] text-[#737780]">
                      Dikembalikan · Rak 2A
                    </span>
                  </div>
                </div>

                <div
                  class="flex min-w-[210px] shrink-0 items-center gap-2 rounded-lg bg-[#e2f9ec] p-2"
                >
                  <div
                    class="flex h-10 w-8 items-center justify-center rounded bg-[#004a2d]/10 text-xs font-bold text-[#00311d]"
                  >
                    HIST
                  </div>

                  <div class="flex min-w-0 flex-col">
                    <span class="truncate text-xs font-semibold text-[#002b51]">
                      Guns, Germs & Steel
                    </span>

                    <span class="text-[11px] text-[#737780]">
                      Dikembalikan · Rak 8C
                    </span>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- ========================= -->
          <!-- RIGHT : RETURN DETAILS -->
          <!-- ========================= -->
          <div class="col-span-12 flex flex-col gap-4 xl:col-span-7">

            <div class="rounded-xl bg-white p-6 shadow-sm">

              <!-- HEADER -->
              <div class="pb-4">

                <div class="flex items-center gap-2">

                  <h2 class="text-xl font-semibold tracking-tight text-[#002b51]">
                    Detail Pengembalian
                  </h2>

                  <span
                    class="inline-flex items-center rounded-full bg-[#dcf3e6] px-2.5 py-0.5 text-[10px] font-bold tracking-wide text-[#00311d]"
                  >
                    <span
                      class="mr-1.5 h-1.5 w-1.5 rounded-full bg-[#64bd8d]"
                    ></span>

                    BUKU TERIDENTIFIKASI
                  </span>
                </div>

                <p class="mt-1 text-xs text-[#42474f]">
                  Konfirmasi kondisi buku, verifikasi identitas siswa,
                  dan selesaikan denda keterlambatan sebelum buku dikembalikan
                  ke rak.
                </p>
              </div>

              <!-- BOOK -->
              <div
                class="mb-6 flex flex-col items-start gap-4 rounded-xl bg-[#e2f9ec] p-4 sm:flex-row sm:items-center"
              >

                <div
                  class="relative h-28 w-20 shrink-0 overflow-hidden rounded-lg bg-[#124170] shadow-md"
                >
                  <img
                    :src="book.cover"
                    :alt="book.title"
                    class="h-full w-full object-cover"
                  />

                  <div
                    class="absolute inset-0 bg-gradient-to-t from-[#002b51]/80 via-transparent to-transparent"
                  ></div>

                  <span
                    class="absolute bottom-1 left-1.5 rounded bg-[#002b51]/60 px-1 font-mono text-[9px] text-white"
                  >
                    {{ book.category }}
                  </span>
                </div>

                <div class="flex min-w-0 flex-1 flex-col">

                  <div class="mb-1 flex flex-wrap items-center gap-2">
                    <span
                      class="rounded bg-[#dcf3e6] px-2 py-0.5 font-mono text-[10px] font-bold text-[#25657e]"
                    >
                      {{ book.inventoryCode }}
                    </span>

                    <span class="text-[10px] text-[#737780]">
                      ISBN {{ book.isbn }}
                    </span>
                  </div>

                  <h3
                    class="truncate text-lg font-bold text-[#002b51]"
                  >
                    {{ book.title }}
                  </h3>

                  <span class="text-sm text-[#42474f]">
                    oleh {{ book.author }}
                  </span>

                  <div
                    class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-[#737780]"
                  >
                    <span class="flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px]">
                        domain
                      </span>

                      {{ book.publisher }}
                    </span>

                    <span>•</span>

                    <span class="flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px]">
                        shelves
                      </span>

                      {{ book.shelf }}
                    </span>
                  </div>

                </div>
              </div>

              <!-- BORROWER -->
              <div class="mb-6">

                <div class="mb-2 flex items-center justify-between">
                  <span
                    class="text-[10px] font-bold uppercase tracking-wider text-[#737780]"
                  >
                    Peminjam & Riwayat Peminjaman
                  </span>

                  <button
                    type="button"
                    class="text-[10px] font-semibold text-[#25657e] hover:underline"
                  >
                    Profil Siswa
                  </button>
                </div>

                <label class="mb-1 block text-xs font-medium text-[#42474f]">
                  Pilih Peminjam / Data Peminjaman:
                </label>

                <div class="relative">

                  <select
                    v-model="selectedBorrower"
                    @change="updateBorrower"
                    class="w-full appearance-none rounded-xl border border-[#c3c6d0]/60 bg-[#e2f9ec] py-2 pl-10 pr-10 text-xs font-semibold text-[#002b51] focus:outline-none"
                  >
                    <option
                      v-for="item in borrowers"
                      :key="item.id"
                      :value="item.id"
                    >
                      {{ item.name }} ({{ item.nis }}) — {{ item.className }} · Jatuh tempo: {{ item.dueDate }}
                    </option>
                  </select>

                  <span
                    class="material-symbols-outlined absolute left-3 top-2 text-[20px] text-[#25657e]"
                  >
                    badge
                  </span>

                  <span
                    class="material-symbols-outlined pointer-events-none absolute right-3 top-2 text-[20px] text-[#737780]"
                  >
                    expand_more
                  </span>
                </div>

                <!-- BORROWER CARD -->
                <div
                  class="mt-2 flex flex-col justify-between gap-4 rounded-xl bg-[#d7eee1]/60 p-4 md:flex-row md:items-center"
                >

                  <div class="flex items-center gap-3">

                    <div
                      class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#25657e] font-bold text-white shadow-sm"
                    >
                      {{ selectedBorrowerData.initials }}
                    </div>

                    <div class="flex min-w-0 flex-col">

                      <div class="flex flex-wrap items-center gap-2">

                        <span class="text-sm font-semibold text-[#002b51]">
                          {{ selectedBorrowerData.name }}
                        </span>

                        <span
                          class="rounded-full bg-[#bee9ff] px-2 py-0.5 text-[10px] font-bold text-[#001f2a]"
                        >
                          {{ selectedBorrowerData.className }}
                        </span>
                      </div>

                      <div class="flex flex-wrap items-center gap-2 text-xs text-[#737780]">
                        <span class="font-mono">
                          {{ selectedBorrowerData.nis }}
                        </span>

                        <span>•</span>

                        <span>
                          {{ selectedBorrowerData.status }}
                        </span>
                      </div>

                    </div>
                  </div>

                  <div
                    class="flex items-center gap-4 border-l-0 pl-0 text-left md:border-l md:border-[#c3c6d0] md:pl-4 md:text-right"
                  >

                    <div class="flex flex-col">
                      <span class="text-[10px] text-[#737780]">
                        Tanggal Pinjam
                      </span>

                      <span class="text-xs font-semibold text-[#002b51]">
                        {{ selectedBorrowerData.checkoutDate }}
                      </span>
                    </div>

                    <div class="hidden h-px w-6 bg-[#c3c6d0] md:block"></div>

                    <div class="flex flex-col">
                      <span class="text-[10px] text-[#737780]">
                        Jatuh Tempo
                      </span>

                      <span class="text-xs font-semibold text-[#002b51]">
                        {{ selectedBorrowerData.dueDate }}
                      </span>
                    </div>

                  </div>
                </div>
              </div>

              <!-- DATE & CONDITION -->
              <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">

                <!-- DATE -->
                <div class="flex flex-col gap-1.5">

                  <label
                    class="flex items-center justify-between text-xs font-semibold text-[#002b51]"
                  >
                    <span>Tanggal Pengembalian</span>

                    <span class="text-[11px] font-normal text-[#737780]">
                      Otomatis hari ini
                    </span>
                  </label>

                  <div class="relative">

                    <input
                      readonly
                      type="text"
                      value="28 November 2024 (Hari Ini)"
                      class="w-full rounded-xl bg-[#e2f9ec] py-2.5 pl-10 pr-4 text-xs font-medium text-[#002b51] focus:outline-none"
                    />

                    <span
                      class="material-symbols-outlined absolute left-3 top-2.5 text-[20px] text-[#25657e]"
                    >
                      calendar_today
                    </span>
                  </div>
                </div>

                <!-- CONDITION -->
                <div class="flex flex-col gap-1.5">

                  <label class="text-xs font-semibold text-[#002b51]">
                    Kondisi Buku
                  </label>

                  <div class="relative">

                    <select
                      v-model="condition"
                      class="w-full appearance-none rounded-xl bg-[#e2f9ec] py-2.5 pl-10 pr-10 text-xs font-medium text-[#002b51] focus:outline-none"
                    >
                      <option value="good">
                        Kondisi Baik · Kembalikan ke Rak
                      </option>

                      <option value="pristine">
                        Sangat Baik / Seperti Baru
                      </option>

                      <option value="wear">
                        Ada Bekas Pemakaian · Perlu Dilapisi
                      </option>

                      <option value="damaged">
                        Sampul Rusak / Halaman Sobek · Karantina
                      </option>

                      <option value="missing">
                        CD / Lampiran Hilang
                      </option>
                    </select>

                    <span
                      class="material-symbols-outlined absolute left-3 top-2.5 text-[20px] text-[#25657e]"
                    >
                      fact_check
                    </span>

                    <span
                      class="material-symbols-outlined pointer-events-none absolute right-3 top-2.5 text-[20px] text-[#737780]"
                    >
                      expand_more
                    </span>

                  </div>
                </div>
              </div>

              <!-- OVERDUE -->
              <div
                class="mb-6 flex items-start gap-4 rounded-xl bg-[#ffdad6]/60 p-4"
              >

                <div
                  class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#ba1a1a] text-white shadow-sm"
                >
                  <span class="material-symbols-outlined text-[22px]">
                    warning
                  </span>
                </div>

                <div class="flex min-w-0 flex-1 flex-col">

                  <div
                    class="flex flex-wrap items-center justify-between gap-2"
                  >

                    <div class="flex flex-wrap items-center gap-2">

                      <span class="text-base font-bold text-[#93000a]">
                        Peminjaman Terlambat: 4 Hari
                      </span>

                      <span
                        class="rounded bg-[#ba1a1a] px-2 py-0.5 text-[10px] font-bold uppercase text-white"
                      >
                        Terlambat
                      </span>
                    </div>

                    <span
                      class="rounded bg-[#ffdad6] px-2 py-0.5 font-mono text-xs font-bold text-[#93000a]"
                    >
                      Denda: Rp7.500
                    </span>

                  </div>

                  <p class="mt-1 text-xs text-[#93000a]/90">
                    Jatuh tempo pada Minggu, 24 November 2024.
                    Tarif denda: Rp1.875 / hari.
                  </p>

                  <div class="mt-2 pt-1">
                    <button
                      type="button"
                      @click="waivePenalty"
                      class="text-[10px] font-bold text-[#93000a] hover:underline"
                    >
                      Hapus / Bebaskan Denda
                    </button>
                  </div>
                </div>
              </div>

              <!-- DESTINATION -->
              <div
                class="mb-6 flex items-center justify-between gap-4 rounded-xl bg-[#e2f9ec] p-4"
              >

                <div class="flex items-center gap-3">

                  <span class="material-symbols-outlined text-[22px] text-[#25657e]">
                    archive
                  </span>

                  <div class="flex flex-col">
                    <span class="text-xs font-semibold text-[#002b51]">
                      Lokasi Pengembalian: Astronomy A-3
                    </span>

                    <span class="text-xs text-[#737780]">
                      Keranjang rak #2 siap untuk dipindahkan ke lantai atas.
                    </span>
                  </div>

                </div>

                <span
                  class="shrink-0 rounded-lg bg-[#dcf3e6] px-2.5 py-1 text-[10px] font-bold text-[#002b51]"
                >
                  Keranjang #2
                </span>
              </div>

              <!-- SUCCESS -->
              <div
                v-if="returnSuccess"
                class="mb-4 flex items-center gap-3 rounded-xl bg-[#dcf3e6] p-4 text-[#00311d]"
              >
                <span class="material-symbols-outlined">
                  task_alt
                </span>

                <div>
                  <p class="text-sm font-bold">
                    Buku berhasil dikembalikan!
                  </p>

                  <p class="text-xs">
                    Data sirkulasi dan inventaris telah diperbarui.
                  </p>
                </div>
              </div>

              <!-- BUTTON -->
              <div class="flex items-center justify-end pt-2">

                <button
                  type="button"
                  :disabled="isChecking"
                  @click="confirmReturn"
                  class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#124170] px-6 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#002b51] disabled:cursor-not-allowed disabled:opacity-70 sm:w-auto"
                >

                  <span
                    class="material-symbols-outlined text-[20px]"
                    :class="{ 'animate-spin': isChecking }"
                  >
                    {{ isChecking ? 'sync' : returnSuccess ? 'task_alt' : 'check_circle' }}
                  </span>

                  {{
                    isChecking
                      ? 'Menyimpan...'
                      : returnSuccess
                        ? 'Berhasil Dikembalikan!'
                        : 'Simpan Pengembalian'
                  }}

                </button>

              </div>

            </div>
          </div>

        </div>
      </main>
    </div>
  </div>
</template>