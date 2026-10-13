<script setup>
import { ref, onBeforeUnmount } from 'vue'
import AdminSidebar from '../../components/adminsidebar.vue'

// =====================================================
// FORM DATA
// =====================================================

const bookTitle = ref('')
const bookAuthor = ref('')
const bookIsbn = ref('')
const bookPublisher = ref('')
const bookCategory = ref('')
const bookSynopsis = ref('')
const bookShelf = ref('')
const bookCopies = ref(1)

// =====================================================
// CAMERA
// =====================================================

const isScanning = ref(false)
const videoElement = ref(null)
const cameraStream = ref(null)
const currentCamera = ref('environment')
const cameraError = ref('')

let scanInterval = null

// =====================================================
// UI STATE
// =====================================================

const isSaving = ref(false)
const showSuccess = ref(false)
const successMessage = ref('')

// =====================================================
// SAMPLE BOOK
// Dipakai sebagai fallback kalau BarcodeDetector
// belum tersedia di browser.
// =====================================================

const sampleBooks = [
  {
    title: 'To Kill a Mockingbird',
    author: 'Harper Lee',
    isbn: '9780061120084',
    publisher: 'Harper Perennial Modern Classics',
    category: 'lit',
    synopsis:
      'A classic novel exploring justice, morality, and human compassion through the experiences of Scout Finch.',
    shelf: 'Fiction Wing • Shelf A-04',
    copies: 5,
  },
  {
    title: 'Cosmos: A Personal Voyage',
    author: 'Carl Sagan',
    isbn: '9780345331359',
    publisher: 'Random House / Ballantine Books',
    category: 'science',
    synopsis:
      'An exploration of fifteen billion years of cosmic evolution, science, civilization, and humanity’s place in the universe.',
    shelf: 'Science Wing • Shelf B-14',
    copies: 3,
  },
  {
    title: 'A Brief History of Time',
    author: 'Stephen Hawking',
    isbn: '9780553380163',
    publisher: 'Bantam Books',
    category: 'science',
    synopsis:
      'An introduction to the history and fundamental concepts of the universe, including time, space, black holes, and cosmology.',
    shelf: 'Science Wing • Shelf B-18',
    copies: 4,
  },
]

let sampleIndex = 0

// =====================================================
// OPEN CAMERA
// =====================================================

async function startScanner() {
  cameraError.value = ''

  try {
    // Pastikan kamera sebelumnya ditutup
    stopScanner()

    const stream = await navigator.mediaDevices.getUserMedia({
      video: {
        facingMode: currentCamera.value,
        width: {
          ideal: 1280,
        },
        height: {
          ideal: 720,
        },
      },
      audio: false,
    })

    cameraStream.value = stream
    isScanning.value = true

    // Tunggu video element muncul
    setTimeout(() => {
      if (videoElement.value) {
        videoElement.value.srcObject = stream

        videoElement.value
          .play()
          .catch((error) => {
            console.error('Video play error:', error)
          })

        startBarcodeDetection()
      }
    }, 100)
  } catch (error) {
    console.error('Camera error:', error)

    cameraError.value =
      'Kamera tidak dapat diakses. Pastikan izin kamera sudah diberikan kepada browser.'

    isScanning.value = false
  }
}

// =====================================================
// STOP CAMERA
// =====================================================

function stopScanner() {
  // Hentikan barcode scanning
  if (scanInterval) {
    clearInterval(scanInterval)
    scanInterval = null
  }

  // Hentikan semua track kamera
  if (cameraStream.value) {
    cameraStream.value.getTracks().forEach((track) => {
      track.stop()
    })

    cameraStream.value = null
  }

  if (videoElement.value) {
    videoElement.value.srcObject = null
  }

  isScanning.value = false
}

// =====================================================
// SWITCH CAMERA
// =====================================================

async function switchCamera() {
  if (currentCamera.value === 'environment') {
    currentCamera.value = 'user'
  } else {
    currentCamera.value = 'environment'
  }

  if (isScanning.value) {
    await startScanner()
  }
}

// =====================================================
// BARCODE DETECTION
// =====================================================

async function startBarcodeDetection() {
  // BarcodeDetector hanya tersedia di browser tertentu
  if (!('BarcodeDetector' in window)) {
    console.warn(
      'BarcodeDetector tidak tersedia di browser ini.'
    )

    return
  }

  try {
    const supportedFormats =
      await window.BarcodeDetector.getSupportedFormats()

    const formats = [
      'ean_13',
      'ean_8',
      'upc_a',
      'upc_e',
      'code_128',
      'code_39',
    ].filter((format) =>
      supportedFormats.includes(format)
    )

    const detector = new window.BarcodeDetector({
      formats,
    })

    scanInterval = setInterval(async () => {
      if (
        !videoElement.value ||
        videoElement.value.readyState < 2 ||
        !isScanning.value
      ) {
        return
      }

      try {
        const barcodes = await detector.detect(
          videoElement.value
        )

        if (barcodes.length > 0) {
          const barcodeValue = barcodes[0].rawValue

          if (barcodeValue) {
            handleBarcodeResult(barcodeValue)
          }
        }
      } catch (error) {
        console.warn(
          'Barcode detection error:',
          error
        )
      }
    }, 500)
  } catch (error) {
    console.warn(
      'BarcodeDetector initialization failed:',
      error
    )
  }
}

// =====================================================
// BARCODE RESULT
// =====================================================

function handleBarcodeResult(barcode) {
  console.log('Barcode detected:', barcode)

  // Bersihkan karakter selain angka
  const cleanBarcode = barcode.replace(/\D/g, '')

  bookIsbn.value = cleanBarcode

  // Cari data contoh berdasarkan ISBN
  const foundBook = sampleBooks.find(
    (book) => book.isbn === cleanBarcode
  )

  if (foundBook) {
    fillBookData(foundBook)
  } else {
    // Kalau ISBN tidak ada di data contoh,
    // minimal ISBN tetap dimasukkan.
    bookIsbn.value = cleanBarcode
  }

  stopScanner()
}

// =====================================================
// FILL BOOK DATA
// =====================================================

function fillBookData(book) {
  bookTitle.value = book.title
  bookAuthor.value = book.author
  bookIsbn.value = book.isbn
  bookPublisher.value = book.publisher
  bookCategory.value = book.category
  bookSynopsis.value = book.synopsis
  bookShelf.value = book.shelf
  bookCopies.value = book.copies
}

// =====================================================
// DEMO SCAN
// Dipakai sebagai fallback / testing.
// =====================================================

function captureBarcode() {
  const book = sampleBooks[sampleIndex]

  fillBookData(book)

  sampleIndex++

  if (sampleIndex >= sampleBooks.length) {
    sampleIndex = 0
  }

  showSuccess.value = false
}

// =====================================================
// UPLOAD IMAGE
// =====================================================

function uploadImage(event) {
  const file = event.target.files?.[0]

  if (!file) {
    return
  }

  console.log('Selected image:', file.name)

  // Untuk sekarang gunakan fallback sample.
  // Nanti bisa disambungkan ke decoder gambar.
  captureBarcode()

  event.target.value = ''
}

// =====================================================
// RESET FORM
// =====================================================

function resetForm() {
  stopScanner()

  bookTitle.value = ''
  bookAuthor.value = ''
  bookIsbn.value = ''
  bookPublisher.value = ''
  bookCategory.value = ''
  bookSynopsis.value = ''
  bookShelf.value = ''
  bookCopies.value = 1

  showSuccess.value = false
  successMessage.value = ''
  cameraError.value = ''
}

// =====================================================
// SAVE BOOK
// =====================================================

async function saveBook() {
  if (
    !bookTitle.value ||
    !bookAuthor.value ||
    !bookIsbn.value ||
    !bookCategory.value
  ) {
    alert(
      'Lengkapi minimal judul, penulis, ISBN, dan kategori buku.'
    )

    return
  }

  isSaving.value = true

  // Simulasi proses penyimpanan
  await new Promise((resolve) =>
    setTimeout(resolve, 800)
  )

  isSaving.value = false
  showSuccess.value = true

  successMessage.value =
    'Book has been successfully added to the library catalog.'

  console.log('BOOK DATA:', {
    title: bookTitle.value,
    author: bookAuthor.value,
    isbn: bookIsbn.value,
    publisher: bookPublisher.value,
    category: bookCategory.value,
    synopsis: bookSynopsis.value,
    shelf: bookShelf.value,
    copies: bookCopies.value,
  })
}

// =====================================================
// CLEANUP
// =====================================================

onBeforeUnmount(() => {
  stopScanner()
})
</script>

<template>
  <div
    class="min-h-screen bg-[#e8fff2] font-['Plus_Jakarta_Sans',sans-serif] text-[#0c1f17]"
  >
    <!-- SIDEBAR -->
    <AdminSidebar />

    <!-- MAIN -->
    <main class="ml-64 min-h-screen">
      <!-- HEADER -->
      <header
        class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-[#d1e8db] bg-white/95 px-8 backdrop-blur"
      >
        <div>
          <p
            class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#737780]"
          >
            Admin / Catalog
          </p>

          <h1 class="mt-0.5 text-sm font-extrabold text-[#124170]">
            Add Book
            <span class="font-normal text-[#737780]">
              (Scan ISBN)
            </span>
          </h1>
        </div>

        <div
          class="flex items-center gap-2 rounded-full border border-[#d1e8db] bg-[#e2f9ec] px-3 py-1.5"
        >
          <span
            class="h-2 w-2 animate-pulse rounded-full bg-[#006c45]"
          ></span>

          <span
            class="text-[10px] font-bold uppercase tracking-wider text-[#006c45]"
          >
            Barcode Scanner: Ready
          </span>
        </div>
      </header>

      <!-- CONTENT -->
      <div class="mx-auto max-w-7xl px-8 py-8">
        <!-- PAGE TITLE -->
        <div class="mb-7">
          <p
            class="mb-2 text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#25657e]"
          >
            Library Catalog
          </p>

          <div
            class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
          >
            <div>
              <h2
                class="text-3xl font-extrabold tracking-tight text-[#124170]"
              >
                Add Book to Catalog
              </h2>

              <p class="mt-2 max-w-2xl text-sm text-[#737780]">
                Scan the ISBN barcode to automatically capture
                book information and add it to the library inventory.
              </p>
            </div>

            <div
              class="flex items-center gap-2 rounded-lg border border-[#d1e8db] bg-white px-3 py-2"
            >
              <span
                class="material-symbols-outlined text-[18px] text-[#124170]"
              >
                inventory_2
              </span>

              <span
                class="text-xs font-bold text-[#42474f]"
              >
                Inventory Management
              </span>
            </div>
          </div>
        </div>

        <!-- SUCCESS -->
        <div
          v-if="showSuccess"
          class="mb-6 flex items-start gap-3 rounded-xl border border-[#9bf5c1] bg-[#e2f9ec] p-4"
        >
          <span
            class="material-symbols-outlined text-[#006c45]"
          >
            check_circle
          </span>

          <div class="flex-1">
            <p
              class="text-sm font-extrabold text-[#00311d]"
            >
              Book Added Successfully
            </p>

            <p class="mt-1 text-xs text-[#42474f]">
              {{ successMessage }}
            </p>
          </div>

          <button
            type="button"
            class="text-[#42474f] hover:text-[#0c1f17]"
            @click="showSuccess = false"
          >
            <span class="material-symbols-outlined text-[18px]">
              close
            </span>
          </button>
        </div>

        <!-- CAMERA SECTION -->
        <section
          class="overflow-hidden rounded-2xl border border-[#d1e8db] bg-white shadow-sm"
        >
          <!-- SECTION HEADER -->
          <div
            class="flex flex-col justify-between gap-4 border-b border-[#d1e8db] px-6 py-5 md:flex-row md:items-center"
          >
            <div class="flex items-center gap-3">
              <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e2f9ec]"
              >
                <span
                  class="material-symbols-outlined text-[#124170]"
                >
                  barcode_scanner
                </span>
              </div>

              <div>
                <h3
                  class="text-sm font-extrabold text-[#124170]"
                >
                  ISBN Barcode Scanner
                </h3>

                <p class="mt-0.5 text-xs text-[#737780]">
                  Position the barcode inside the scanning frame.
                </p>
              </div>
            </div>

            <!-- STATUS -->
            <div
              class="flex items-center gap-2 rounded-full border border-[#d1e8db] px-3 py-1.5"
            >
              <span
                class="h-2 w-2 rounded-full"
                :class="
                  isScanning
                    ? 'animate-pulse bg-[#006c45]'
                    : 'bg-[#737780]'
                "
              ></span>

              <span
                class="text-[10px] font-bold uppercase tracking-wider text-[#42474f]"
              >
                {{
                  isScanning
                    ? 'Camera Active'
                    : 'Scanner Ready'
                }}
              </span>
            </div>
          </div>

          <div class="grid gap-6 p-6 lg:grid-cols-[1.5fr_1fr]">
            <!-- CAMERA VIEW -->
            <div>
              <div
                class="relative aspect-video overflow-hidden rounded-2xl bg-[#002b51]"
              >
                <!-- CAMERA -->
                <video
                  v-if="isScanning"
                  ref="videoElement"
                  autoplay
                  playsinline
                  muted
                  class="absolute inset-0 h-full w-full object-cover"
                ></video>

                <!-- EMPTY STATE -->
                <div
                  v-else
                  class="absolute inset-0 flex flex-col items-center justify-center"
                >
                  <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10"
                  >
                    <span
                      class="material-symbols-outlined text-[38px] text-[#9bf5c1]"
                    >
                      barcode_scanner
                    </span>
                  </div>

                  <p
                    class="text-sm font-extrabold text-white"
                  >
                    Camera is Ready
                  </p>

                  <p
                    class="mt-1 text-xs text-white/55"
                  >
                    Click Capture & Decode to start scanning
                  </p>
                </div>

                <!-- SCANNER FRAME -->
                <div
                  v-if="isScanning"
                  class="absolute inset-0 flex items-center justify-center"
                >
                  <div
                    class="relative h-36 w-[75%] max-w-md"
                  >
                    <!-- TOP LEFT -->
                    <span
                      class="absolute left-0 top-0 h-7 w-7 border-l-4 border-t-4 border-[#9bf5c1]"
                    ></span>

                    <!-- TOP RIGHT -->
                    <span
                      class="absolute right-0 top-0 h-7 w-7 border-r-4 border-t-4 border-[#9bf5c1]"
                    ></span>

                    <!-- BOTTOM LEFT -->
                    <span
                      class="absolute bottom-0 left-0 h-7 w-7 border-b-4 border-l-4 border-[#9bf5c1]"
                    ></span>

                    <!-- BOTTOM RIGHT -->
                    <span
                      class="absolute bottom-0 right-0 h-7 w-7 border-b-4 border-r-4 border-[#9bf5c1]"
                    ></span>

                    <!-- LASER -->
                    <div
                      class="absolute left-3 right-3 top-1/2 h-0.5 -translate-y-1/2 animate-pulse bg-[#9bf5c1] shadow-[0_0_15px_#9bf5c1]"
                    ></div>
                  </div>
                </div>

                <!-- CAMERA STATUS -->
                <div
                  class="absolute left-4 top-4 flex items-center gap-2 rounded-full bg-black/50 px-3 py-1.5 backdrop-blur"
                >
                  <span
                    class="material-symbols-outlined text-[15px] text-[#9bf5c1]"
                  >
                    videocam
                  </span>

                  <span
                    class="text-[10px] font-bold text-white"
                  >
                    {{
                      isScanning
                        ? 'LIVE CAMERA'
                        : 'CAMERA OFF'
                    }}
                  </span>
                </div>

                <!-- AUTO OCR -->
                <div
                  v-if="isScanning"
                  class="absolute bottom-4 left-4 flex items-center gap-2 rounded-lg bg-black/50 px-3 py-2 backdrop-blur"
                >
                  <span
                    class="material-symbols-outlined text-[16px] text-[#9bf5c1]"
                  >
                    auto_awesome
                  </span>

                  <span
                    class="text-[10px] font-bold text-white"
                  >
                    Auto Barcode Detection
                  </span>
                </div>
              </div>

              <!-- CAMERA ERROR -->
              <div
                v-if="cameraError"
                class="mt-3 flex items-start gap-2 rounded-lg border border-[#ffdad6] bg-[#fff5f4] p-3"
              >
                <span
                  class="material-symbols-outlined text-[18px] text-[#ba1a1a]"
                >
                  error
                </span>

                <p
                  class="text-xs leading-relaxed text-[#ba1a1a]"
                >
                  {{ cameraError }}
                </p>
              </div>

              <!-- CONTROLS -->
              <div
                class="mt-4 flex flex-wrap gap-2"
              >
                <!-- SWITCH CAMERA -->
                <button
                  type="button"
                  @click="switchCamera"
                  class="flex items-center gap-2 rounded-lg border border-[#d1e8db] bg-white px-4 py-2.5 text-xs font-bold text-[#42474f] transition hover:bg-[#e2f9ec]"
                >
                  <span
                    class="material-symbols-outlined text-[18px]"
                  >
                    cameraswitch
                  </span>

                  Switch Camera
                </button>

                <!-- UPLOAD -->
                <label
                  class="flex cursor-pointer items-center gap-2 rounded-lg border border-[#d1e8db] bg-white px-4 py-2.5 text-xs font-bold text-[#42474f] transition hover:bg-[#e2f9ec]"
                >
                  <span
                    class="material-symbols-outlined text-[18px]"
                  >
                    upload
                  </span>

                  Upload Image

                  <input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="uploadImage"
                  />
                </label>

                <!-- START / STOP -->
                <button
                  v-if="!isScanning"
                  type="button"
                  @click="startScanner"
                  class="flex items-center gap-2 rounded-lg bg-[#124170] px-5 py-2.5 text-xs font-extrabold text-white transition hover:bg-[#002b51]"
                >
                  <span
                    class="material-symbols-outlined text-[18px]"
                  >
                    barcode_scanner
                  </span>

                  Capture & Decode Barcode
                </button>

                <button
                  v-else
                  type="button"
                  @click="stopScanner"
                  class="flex items-center gap-2 rounded-lg bg-[#ba1a1a] px-5 py-2.5 text-xs font-extrabold text-white transition hover:bg-[#8f1414]"
                >
                  <span
                    class="material-symbols-outlined text-[18px]"
                  >
                    videocam_off
                  </span>

                  Stop Scanner
                </button>

                <!-- DEMO SCAN -->
                <button
                  type="button"
                  @click="captureBarcode"
                  class="flex items-center gap-2 rounded-lg border border-dashed border-[#25657e] px-4 py-2.5 text-xs font-bold text-[#25657e] transition hover:bg-[#e2f9ec]"
                >
                  <span
                    class="material-symbols-outlined text-[18px]"
                  >
                    science
                  </span>

                  Test Scan
                </button>
              </div>
            </div>

            <!-- INSTRUCTION -->
            <div
              class="flex flex-col justify-between rounded-2xl bg-[#e2f9ec] p-6"
            >
              <div>
                <p
                  class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#006c45]"
                >
                  How to scan
                </p>

                <h4
                  class="mt-2 text-lg font-extrabold text-[#124170]"
                >
                  Scan the ISBN barcode
                </h4>

                <div
                  class="mt-5 space-y-4"
                >
                  <div
                    class="flex gap-3"
                  >
                    <div
                      class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#124170] text-xs font-extrabold text-white"
                    >
                      1
                    </div>

                    <div>
                      <p
                        class="text-xs font-extrabold text-[#0c1f17]"
                      >
                        Allow camera access
                      </p>

                      <p
                        class="mt-1 text-[11px] leading-relaxed text-[#737780]"
                      >
                        Give the browser permission to access
                        your camera.
                      </p>
                    </div>
                  </div>

                  <div
                    class="flex gap-3"
                  >
                    <div
                      class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#124170] text-xs font-extrabold text-white"
                    >
                      2
                    </div>

                    <div>
                      <p
                        class="text-xs font-extrabold text-[#0c1f17]"
                      >
                        Position the barcode
                      </p>

                      <p
                        class="mt-1 text-[11px] leading-relaxed text-[#737780]"
                      >
                        Place the ISBN barcode inside the scanning
                        frame.
                      </p>
                    </div>
                  </div>

                  <div
                    class="flex gap-3"
                  >
                    <div
                      class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#124170] text-xs font-extrabold text-white"
                    >
                      3
                    </div>

                    <div>
                      <p
                        class="text-xs font-extrabold text-[#0c1f17]"
                      >
                        Wait for detection
                      </p>

                      <p
                        class="mt-1 text-[11px] leading-relaxed text-[#737780]"
                      >
                        The ISBN number will be detected
                        automatically.
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="mt-6 rounded-xl border border-[#c2e2d4] bg-white/70 p-4"
              >
                <div
                  class="flex items-start gap-3"
                >
                  <span
                    class="material-symbols-outlined text-[19px] text-[#124170]"
                  >
                    info
                  </span>

                  <p
                    class="text-[10px] leading-relaxed text-[#737780]"
                  >
                    Make sure the barcode is clearly visible
                    and has enough lighting for better scanning
                    results.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- DIVIDER -->
        <div class="my-8 flex items-center gap-4">
          <div
            class="h-px flex-1 bg-[#d1e8db]"
          ></div>

          <div
            class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.15em] text-[#737780]"
          >
            <span
              class="material-symbols-outlined text-[16px]"
            >
              verified
            </span>

            Verified Book Record
          </div>

          <div
            class="h-px flex-1 bg-[#d1e8db]"
          ></div>
        </div>

        <!-- FORM -->
        <section
          class="rounded-2xl border border-[#d1e8db] bg-white shadow-sm"
        >
          <!-- FORM HEADER -->
          <div
            class="border-b border-[#d1e8db] px-6 py-5"
          >
            <h3
              class="text-sm font-extrabold text-[#124170]"
            >
              Book Information
            </h3>

            <p
              class="mt-1 text-xs text-[#737780]"
            >
              Review the information before adding the book
              to the catalog.
            </p>
          </div>

          <div class="space-y-6 p-6">
            <!-- ROW 1 -->
            <div
              class="grid gap-5 md:grid-cols-2"
            >
              <!-- TITLE -->
              <div>
                <label
                  class="mb-2 block text-xs font-extrabold text-[#42474f]"
                >
                  Book Title
                </label>

                <input
                  v-model="bookTitle"
                  type="text"
                  placeholder="Enter book title"
                  class="w-full rounded-lg border border-[#d1e8db] bg-white px-4 py-3 text-sm outline-none transition placeholder:text-[#a0aaa5] focus:border-[#124170] focus:ring-2 focus:ring-[#124170]/10"
                />
              </div>

              <!-- AUTHOR -->
              <div>
                <label
                  class="mb-2 block text-xs font-extrabold text-[#42474f]"
                >
                  Author
                </label>

                <input
                  v-model="bookAuthor"
                  type="text"
                  placeholder="Enter author name"
                  class="w-full rounded-lg border border-[#d1e8db] bg-white px-4 py-3 text-sm outline-none transition placeholder:text-[#a0aaa5] focus:border-[#124170] focus:ring-2 focus:ring-[#124170]/10"
                />
              </div>
            </div>

            <!-- ROW 2 -->
            <div
              class="grid gap-5 md:grid-cols-2"
            >
              <!-- ISBN -->
              <div>
                <label
                  class="mb-2 flex items-center gap-2 text-xs font-extrabold text-[#42474f]"
                >
                  ISBN

                  <span
                    class="rounded bg-[#e2f9ec] px-2 py-0.5 text-[9px] font-bold text-[#006c45]"
                  >
                    SCANNED
                  </span>
                </label>

                <div class="relative">
                  <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-[#737780]"
                  >
                    barcode
                  </span>

                  <input
                    v-model="bookIsbn"
                    type="text"
                    placeholder="9780000000000"
                    class="w-full rounded-lg border border-[#d1e8db] bg-white py-3 pl-10 pr-4 text-sm font-bold tracking-wide outline-none transition placeholder:text-[#a0aaa5] focus:border-[#124170] focus:ring-2 focus:ring-[#124170]/10"
                  />
                </div>
              </div>

              <!-- PUBLISHER -->
              <div>
                <label
                  class="mb-2 block text-xs font-extrabold text-[#42474f]"
                >
                  Publisher
                </label>

                <input
                  v-model="bookPublisher"
                  type="text"
                  placeholder="Enter publisher"
                  class="w-full rounded-lg border border-[#d1e8db] bg-white px-4 py-3 text-sm outline-none transition placeholder:text-[#a0aaa5] focus:border-[#124170] focus:ring-2 focus:ring-[#124170]/10"
                />
              </div>
            </div>

            <!-- ROW 3 -->
            <div
              class="grid gap-5 md:grid-cols-2"
            >
              <!-- CATEGORY -->
              <div>
                <label
                  class="mb-2 block text-xs font-extrabold text-[#42474f]"
                >
                  Category
                </label>

                <select
                  v-model="bookCategory"
                  class="w-full rounded-lg border border-[#d1e8db] bg-white px-4 py-3 text-sm outline-none transition focus:border-[#124170] focus:ring-2 focus:ring-[#124170]/10"
                >
                  <option value="">
                    Select category
                  </option>

                  <option value="lit">
                    Literature
                  </option>

                  <option value="science">
                    Science
                  </option>

                  <option value="technology">
                    Technology
                  </option>

                  <option value="history">
                    History
                  </option>

                  <option value="education">
                    Education
                  </option>

                  <option value="fiction">
                    Fiction
                  </option>
                </select>
              </div>

              <!-- SHELF -->
              <div>
                <label
                  class="mb-2 block text-xs font-extrabold text-[#42474f]"
                >
                  Shelf Location
                </label>

                <input
                  v-model="bookShelf"
                  type="text"
                  placeholder="Example: Science Wing • Shelf B-14"
                  class="w-full rounded-lg border border-[#d1e8db] bg-white px-4 py-3 text-sm outline-none transition placeholder:text-[#a0aaa5] focus:border-[#124170] focus:ring-2 focus:ring-[#124170]/10"
                />
              </div>
            </div>

            <!-- SYNOPSIS -->
            <div>
              <label
                class="mb-2 block text-xs font-extrabold text-[#42474f]"
              >
                Synopsis
              </label>

              <textarea
                v-model="bookSynopsis"
                rows="4"
                placeholder="Write a short synopsis of the book..."
                class="w-full resize-none rounded-lg border border-[#d1e8db] bg-white px-4 py-3 text-sm outline-none transition placeholder:text-[#a0aaa5] focus:border-[#124170] focus:ring-2 focus:ring-[#124170]/10"
              ></textarea>
            </div>

            <!-- COPIES -->
            <div
              class="max-w-xs"
            >
              <label
                class="mb-2 block text-xs font-extrabold text-[#42474f]"
              >
                Initial Copy Count
              </label>

              <div
                class="flex items-center rounded-lg border border-[#d1e8db] bg-white"
              >
                <button
                  type="button"
                  class="flex h-11 w-11 items-center justify-center text-[#737780] transition hover:bg-[#e2f9ec]"
                  @click="
                    bookCopies = Math.max(
                      1,
                      bookCopies - 1
                    )
                  "
                >
                  <span
                    class="material-symbols-outlined text-[18px]"
                  >
                    remove
                  </span>
                </button>

                <input
                  v-model.number="bookCopies"
                  type="number"
                  min="1"
                  class="h-11 flex-1 border-x border-[#d1e8db] text-center text-sm font-extrabold outline-none"
                />

                <button
                  type="button"
                  class="flex h-11 w-11 items-center justify-center text-[#737780] transition hover:bg-[#e2f9ec]"
                  @click="bookCopies++"
                >
                  <span
                    class="material-symbols-outlined text-[18px]"
                  >
                    add
                  </span>
                </button>
              </div>
            </div>
          </div>

          <!-- FORM FOOTER -->
          <div
            class="flex flex-col-reverse gap-3 border-t border-[#d1e8db] bg-[#f8fffb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between"
          >
            <button
              type="button"
              @click="resetForm"
              class="flex items-center justify-center gap-2 rounded-lg border border-[#d1e8db] bg-white px-5 py-3 text-xs font-extrabold text-[#42474f] transition hover:bg-[#e2f9ec]"
            >
              <span
                class="material-symbols-outlined text-[18px]"
              >
                restart_alt
              </span>

              Reset Form
            </button>

            <button
              type="button"
              :disabled="isSaving"
              @click="saveBook"
              class="flex items-center justify-center gap-2 rounded-lg bg-[#124170] px-6 py-3 text-xs font-extrabold text-white transition hover:bg-[#002b51] disabled:cursor-not-allowed disabled:opacity-60"
            >
              <span
                v-if="isSaving"
                class="material-symbols-outlined animate-spin text-[18px]"
              >
                progress_activity
              </span>

              <span
                v-else
                class="material-symbols-outlined text-[18px]"
              >
                library_add
              </span>

              {{
                isSaving
                  ? 'Saving Book...'
                  : 'Save to Catalog'
              }}
            </button>
          </div>
        </section>

        <!-- BOTTOM INFO -->
        <div
          class="mt-5 flex items-center gap-2 px-1"
        >
          <span
            class="material-symbols-outlined text-[16px] text-[#737780]"
          >
            security
          </span>

          <p
            class="text-[10px] text-[#737780]"
          >
            Book information is verified before being added
            to the library inventory.
          </p>
        </div>
      </div>
    </main>
  </div>
</template>