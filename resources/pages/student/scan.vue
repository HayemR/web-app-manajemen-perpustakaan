<script setup>
import { ref, onBeforeUnmount } from 'vue'
import StudentNavbar from '../../components/student/StudentNavbar.vue'
import StudentFooter from '../../components/student/StudentFooter.vue'

const scannerBackground =
  'https://lh3.googleusercontent.com/aida-public/AB6AXuAdQ5msAymAVFQKMbxL99KdVq9ntbHjy3Lmi3ACAk5iTkO38D5RfJbk814LEkC_D0tefyuIA2tP9rq59SglrpokwEfiyszdGAxHh00isfLCcgztz5icOjUzPMDWFm8nN91TqcSViqhgOiS7gYod3plnmEhLh2cJ4IIQELuDTSUeelwaAMf3KF9OaCLs367NGUNvXtsQe_tfs0XBH_66qf6khTQKQBEl4dQ99wgWHqbBLzfO6JiTVW_-IQ'

const bookCover =
  'https://lh3.googleusercontent.com/aida-public/AB6AXuBCHOBu_HEY84sLgXbJ5mRCo538jE6Akgn7fuSwUoFGvwEhNEJIOn-aQQNCRkzFm5O1MLJqmX8UbJm3fiByxf0nVf3Q5tbsgxMhig_iiW7pM09caWSariz0qHZE5syVtfpN0xBJzdHQD_BEtLv7ZBAAUKvH263XDn4hJliIjFV1uuHX11ZPhkW34auN3p51eqPJicZgi9Z1RIzQ6jlh_P4Sc72b-EA1h7xyMkIDBMAYETLTxkknqmaaFg'

const bookTitle = ref('To Kill a Mockingbird')
const bookAuthor = ref('Harper Lee')
const bookIsbn = ref('9780061120084')

const selectedDevice = ref('camera')
const flashEnabled = ref(false)
const barcodeInput = ref('')
const scanMessage = ref('')
const scanMessageType = ref('success')

const activeLoans = ref(2)
const loanLimit = ref(3)

const isProcessing = ref(false)
const isComplete = ref(false)

const videoElement = ref(null)
const cameraStream = ref(null)
const isCameraOn = ref(false)
const cameraMessage = ref('')
const cameraError = ref(false)
const isStartingCamera = ref(false)

function scanBarcode() {
  const code = barcodeInput.value.trim()

  if (!code) {
    scanMessage.value = 'Silakan masukkan kode barcode buku terlebih dahulu.'
    scanMessageType.value = 'error'
    return
  }

  if (code === bookIsbn.value) {
    scanMessage.value = 'Buku berhasil ditemukan di katalog simulasi.'
    scanMessageType.value = 'success'
  } else {
    scanMessage.value =
      'Kode tidak ditemukan dalam data simulasi. Coba gunakan ISBN yang tersedia.'
    scanMessageType.value = 'error'
  }
}

async function toggleFullscreen() {
  try {
    const element = document.querySelector('main')

    if (!document.fullscreenElement) {
      await element?.requestFullscreen?.()
    } else {
      await document.exitFullscreen?.()
    }
  } catch (error) {
    console.error('Tidak dapat mengaktifkan layar penuh:', error)
  }
}

function confirmBorrow() {
  if (isProcessing.value || isComplete.value) return

  if (activeLoans.value >= loanLimit.value) {
    scanMessage.value =
      'Kuota peminjaman sudah penuh. Kembalikan buku terlebih dahulu.'
    scanMessageType.value = 'error'
    return
  }

  isProcessing.value = true

  // Simulasi proses konfirmasi. Belum mengirim data ke backend.
  setTimeout(() => {
    isProcessing.value = false
    isComplete.value = true
    activeLoans.value += 1
  }, 1200)
}

function resetScan() {
  barcodeInput.value = ''
  scanMessage.value = ''
  scanMessageType.value = 'success'
  isProcessing.value = false
  isComplete.value = false
}
async function startCamera() {
  cameraError.value = false
  cameraMessage.value = ''

  if (!navigator.mediaDevices?.getUserMedia) {
    cameraError.value = true
    cameraMessage.value =
      'Browser tidak mendukung kamera atau halaman belum menggunakan HTTPS/localhost.'
    return
  }

  try {
    // Kamera belakang diutamakan, cocok untuk memindai barcode buku.
    const stream = await navigator.mediaDevices.getUserMedia({
      audio: false,
      video: {
        facingMode: { ideal: 'environment' },
        width: { ideal: 1280 },
        height: { ideal: 720 },
      },
    })

    cameraStream.value = stream

    if (videoElement.value) {
      videoElement.value.srcObject = stream
      await videoElement.value.play()
      isCameraOn.value = true
      cameraMessage.value = 'Kamera aktif. Arahkan barcode ke dalam bingkai.'
    } else {
      stream.getTracks().forEach((track) => track.stop())
      cameraStream.value = null
      cameraError.value = true
      cameraMessage.value = 'Elemen video belum siap. Coba lagi.'
    }
  } catch (error) {
    cameraError.value = true

    if (error.name === 'NotAllowedError') {
      cameraMessage.value =
        'Izin kamera ditolak. Izinkan akses kamera melalui pengaturan browser.'
    } else if (error.name === 'NotFoundError') {
      cameraMessage.value = 'Kamera tidak ditemukan pada perangkat ini.'
    } else if (error.name === 'NotReadableError') {
      cameraMessage.value =
        'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.'
    } else {
      cameraMessage.value =
        'Kamera gagal dibuka. Periksa izin kamera dan koneksi halaman.'
    }

    stopCamera()
  }
}

function stopCamera() {
  if (cameraStream.value) {
    cameraStream.value.getTracks().forEach((track) => track.stop())
    cameraStream.value = null
  }

  if (videoElement.value) {
    videoElement.value.srcObject = null
  }

  isCameraOn.value = false
}

onBeforeUnmount(() => {
  stopCamera()
})
</script>

<style scoped>
@keyframes scan {
  0%,
  100% {
    top: 12%;
  }

  50% {
    top: 86%;
  }
}
</style>

<template>
  <div class="min-h-screen bg-[#DDF4E7] font-body-md text-[#0c1f17] antialiased">
    <!-- NAVBAR -->
    <StudentNavbar />

    <!-- MAIN -->
    <main class="w-full min-h-screen bg-[#DDF4E7] pt-20">
      <div class="flex w-full flex-col">
        <div
          class="mx-auto flex w-full max-w-[1200px] flex-col gap-6 px-4 py-6 md:px-5"
        >
          <!-- HEADER & BREADCRUMB -->
          <section class="flex flex-col gap-2">
            <div
              class="flex flex-col justify-between gap-3 pt-1 md:flex-row md:items-end"
            >
              <div>
                <h1
                  class="text-[26px] font-bold tracking-tight text-[#124170] md:text-[32px] md:leading-10"
                >
                  Pindai untuk Meminjam Buku
                </h1>

                <p class="mt-1 max-w-2xl text-sm leading-relaxed text-[#6F8B95]">
                  Arahkan barcode buku ke pemindai atau masukkan kode buku
                  untuk melanjutkan proses peminjaman.
                </p>
              </div>

              <div
                class="flex items-center gap-2 self-start rounded-xl bg-[#E2F9EC] px-3 py-2 text-[#26667F] shadow-sm md:self-auto"
              >
                <span class="material-symbols-outlined text-xl">
                  schedule
                </span>

                <span class="text-xs font-semibold">
                  Jam Layanan: 07.30–17.30
                </span>
              </div>
            </div>
          </section>

          <!-- MAIN WORKFLOW -->
          <section class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <!-- SCANNER -->
            <div class="flex flex-col gap-4 lg:col-span-7">
              <div
                class="group relative flex min-h-[380px] flex-col justify-between overflow-hidden rounded-2xl bg-[#21342c] p-4 shadow-md sm:min-h-[440px] sm:p-6"
              >

                <!-- Background -->
                <div
                class="pointer-events-none absolute inset-0 z-0 scale-105 bg-cover bg-center opacity-40 blur-[2px]"
                :style="{ backgroundImage: `url('${scannerBackground}')` }"
                ></div>

                <div
                class="pointer-events-none absolute inset-0 z-0 bg-gradient-to-t from-[#21342c]/90 via-[#21342c]/40 to-[#21342c]/80"
                ></div>
                
                <!-- Live Camera View -->
                <div
                class="relative z-10 my-auto h-[260px] w-full shrink-0 overflow-hidden rounded-xl bg-black sm:h-[320px]"
                >
                    <video
                        ref="videoElement"
                        autoplay
                        muted
                        playsinline
                        class="absolute inset-0 z-0 block h-full w-full object-cover"
                    ></video>

                    <div
                        v-if="!isCameraOn"
                        class="pointer-events-none absolute inset-0 z-10 flex flex-col items-center justify-center gap-3 bg-[#21342c] px-4 text-center text-white"
                    >
                        <span class="material-symbols-outlined text-5xl text-[#67C090]">
                        videocam
                        </span>

                        <p class="text-sm font-semibold">
                        Kamera belum diaktifkan
                        </p>

                        <p class="max-w-xs text-xs text-white/70">
                        Tekan tombol di bawah untuk mengizinkan akses kamera.
                        </p>
                    </div>

                    <!-- Bingkai pemindai -->
                    <div
                        v-if="isCameraOn"
                        class="pointer-events-none absolute inset-0 z-10 flex items-center justify-center"
                    >
                        <div class="relative h-36 w-64 max-w-[80%] sm:h-44 sm:w-72">
                            <span class="absolute left-0 top-0 h-7 w-7 rounded-tl-lg border-l-4 border-t-4 border-[#67C090]"></span>
                            <span class="absolute right-0 top-0 h-7 w-7 rounded-tr-lg border-r-4 border-t-4 border-[#67C090]"></span>
                            <span class="absolute bottom-0 left-0 h-7 w-7 rounded-bl-lg border-b-4 border-l-4 border-[#67C090]"></span>
                            <span class="absolute bottom-0 right-0 h-7 w-7 rounded-br-lg border-b-4 border-r-4 border-[#67C090]"></span>

                            <div
                                class="absolute inset-x-2 top-1/2 h-0.5 -translate-y-1/2 animate-pulse bg-[#67C090] shadow-[0_0_12px_#67C090]"
                            ></div>
                        </div>
                    </div>
                </div>
         
                <!-- Camera controls -->
                <div class="relative z-30 flex w-full flex-wrap gap-2">
                <button
                    v-if="!isCameraOn"
                    type="button"
                    :disabled="isStartingCamera"
                    @click="startCamera"
                    class="relative z-30 flex min-h-12 flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#26667F] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#004e65] disabled:cursor-wait disabled:opacity-70"
                >
                    <span class="material-symbols-outlined">
                    {{ isStartingCamera ? 'progress_activity' : 'videocam' }}
                    </span>

                    {{ isStartingCamera ? 'Membuka Kamera...' : 'Buka Kamera' }}
                </button>

                <button
                    v-else
                    type="button"
                    @click="stopCamera"
                    class="relative z-30 flex min-h-12 flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#E55353] px-4 py-3 text-sm font-semibold text-white hover:bg-red-700"
                >
                    <span class="material-symbols-outlined">videocam_off</span>
                    Matikan Kamera
                </button>
                </div>

                <p
                v-if="cameraMessage"
                class="relative z-10 text-xs leading-relaxed"
                :class="cameraError ? 'text-[#E55353]' : 'text-[#67C090]'"
                role="status"
                >
                {{ cameraMessage }}
                </p>

                <!-- Scanner Status -->
                <div
                  class="relative z-10 flex flex-col items-center justify-between gap-2 text-center sm:flex-row sm:text-left"
                >
                  <div
                    class="inline-flex items-center gap-2 rounded-full bg-[#21342c]/85 px-3 py-1.5 text-[11px] font-semibold text-white backdrop-blur-md"
                  >
                    <span
                      class="material-symbols-outlined animate-pulse text-lg text-[#67C090]"
                    >
                      qr_code_scanner
                    </span>

                    Arahkan barcode ke dalam bingkai
                  </div>

                  <span class="text-[11px] text-white/70">
                    Mode simulasi pemindaian
                  </span>
                </div>
              </div>

              <!-- Manual Barcode Input -->
              <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="mb-3 flex items-start gap-3">
                  <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E2F9EC] text-[#26667F]"
                  >
                    <span class="material-symbols-outlined text-xl">
                      barcode
                    </span>
                  </div>

                  <div>
                    <h2 class="font-semibold text-[#124170]">
                      Masukkan Kode Buku
                    </h2>

                    <p class="mt-1 text-xs leading-relaxed text-[#6F8B95]">
                      Gunakan cara manual jika barcode tidak dapat dipindai.
                    </p>
                  </div>
                </div>

                <form
                  class="flex flex-col gap-2 sm:flex-row"
                  @submit.prevent="scanBarcode"
                >
                  <input
                    v-model="barcodeInput"
                    type="text"
                    placeholder="Contoh: 9780061120084"
                    aria-label="Kode barcode buku"
                    class="min-w-0 flex-1 rounded-xl border border-[#C2E2D4] bg-[#F8FCF9] px-4 py-3 text-sm text-[#124170] outline-none transition focus:border-[#26667F] focus:ring-2 focus:ring-[#26667F]/10"
                  />

                  <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#26667F] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#004e65]"
                  >
                    <span class="material-symbols-outlined text-lg">
                      search
                    </span>
                    Cari Buku
                  </button>
                </form>

                <p
                  v-if="scanMessage"
                  class="mt-3 text-xs font-medium"
                  :class="
                    scanMessageType === 'success'
                      ? 'text-[#006c45]'
                      : 'text-[#E55353]'
                  "
                  role="status"
                >
                  {{ scanMessage }}
                </p>
              </div>
            </div>

            <!-- BOOK INFORMATION -->
            <div class="flex flex-col gap-4 lg:col-span-5">
              <!-- Identification Status -->
              <div
                class="flex items-center justify-between gap-3 rounded-xl bg-[#E8F8F0] px-4 py-3 shadow-sm"
              >
                <div class="flex min-w-0 items-center gap-3">
                  <div
                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#67C090] text-white"
                  >
                    <span class="material-symbols-outlined text-lg">
                      done
                    </span>
                  </div>

                  <div>
                    <p class="text-sm font-semibold text-[#006c45]">
                      Buku Berhasil Dikenali
                    </p>

                    <p class="mt-0.5 text-[11px] text-[#6F8B95]">
                      Data buku ditemukan di katalog perpustakaan
                    </p>
                  </div>
                </div>

                <span
                  class="shrink-0 rounded-md bg-white px-2 py-1 text-[10px] font-semibold text-[#006c45] shadow-sm"
                >
                  Terverifikasi
                </span>
              </div>

              <!-- Book Card -->
              <div class="flex flex-col gap-4 rounded-2xl bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-start gap-4">
                  <div
                    class="relative aspect-[3/4] w-28 shrink-0 overflow-hidden rounded-xl bg-[#E2F9EC] shadow-md sm:w-32"
                  >
                    <img
                      :src="bookCover"
                      alt="Sampul buku To Kill a Mockingbird"
                      class="h-full w-full object-cover"
                    />

                    <span
                      class="absolute left-2 top-2 rounded bg-white/90 px-1.5 py-0.5 text-[10px] font-semibold text-[#124170] shadow-sm backdrop-blur-sm"
                    >
                      Sampul keras
                    </span>
                  </div>

                  <div class="flex min-w-0 flex-1 flex-col">
                    <div
                      class="mb-2 inline-flex w-fit items-center gap-1.5 rounded-full bg-[#E8F8F0] px-2 py-1 text-[10px] font-semibold text-[#006c45]"
                    >
                      <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]"></span>
                      Tersedia untuk Dipinjam
                    </div>

                    <h2
                      class="line-clamp-2 text-lg font-bold leading-snug text-[#124170]"
                    >
                      {{ bookTitle }}
                    </h2>

                    <p class="mt-1 text-sm text-[#6F8B95]">
                      {{ bookAuthor }}
                    </p>

                    <p class="mt-1 text-xs text-[#26667F]">
                      Fiksi Klasik · 1960
                    </p>

                    <div class="mt-3 flex flex-wrap gap-1.5">
                      <span
                        class="rounded bg-[#E2F9EC] px-2 py-1 font-mono text-[10px] font-medium text-[#124170]"
                      >
                        #LIT-823.9
                      </span>

                      <span
                        class="rounded bg-[#E2F9EC] px-2 py-1 font-mono text-[10px] text-[#6F8B95]"
                      >
                        ISBN: {{ bookIsbn }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Loan Metrics -->
                <div class="grid grid-cols-2 gap-2 pt-1">
                  <div
                    class="flex flex-col justify-between rounded-xl bg-[#E2F9EC] p-3"
                  >
                    <div
                      class="mb-2 flex items-center justify-between gap-1 text-[#26667F]"
                    >
                      <span class="text-[10px] font-semibold uppercase tracking-wide">
                        Durasi Pinjam
                      </span>

                      <span class="material-symbols-outlined text-xl">
                        event_available
                      </span>
                    </div>

                    <span class="text-xl font-bold text-[#124170]">
                      14 Hari
                    </span>

                    <span class="mt-1 text-[11px] text-[#6F8B95]">
                      Jatuh tempo: 14 hari
                    </span>
                  </div>

                  <div
                    class="flex flex-col justify-between rounded-xl bg-[#E2F9EC] p-3"
                  >
                    <div
                      class="mb-2 flex items-center justify-between gap-1 text-[#26667F]"
                    >
                      <span class="text-[10px] font-semibold uppercase tracking-wide">
                        Kuota Pinjaman
                      </span>

                      <span class="material-symbols-outlined text-xl">
                        account_balance_wallet
                      </span>
                    </div>

                    <div class="flex flex-wrap items-baseline gap-1">
                      <span class="text-xl font-bold text-[#124170]">
                        {{ activeLoans }} dari {{ loanLimit }}
                      </span>
                    </div>

                    <span class="mt-1 text-[11px] text-[#6F8B95]">
                      Buku sedang dipinjam
                    </span>

                    <div class="mt-2 flex items-center gap-1.5">
                      <span
                        v-for="slot in loanLimit"
                        :key="slot"
                        class="h-3 w-3 rounded-full"
                        :class="
                          slot <= activeLoans
                            ? 'bg-[#26667F]'
                            : 'bg-[#C0C8CD]'
                        "
                      ></span>

                      <span class="ml-1 text-[10px] font-semibold text-[#006c45]">
                        {{ loanLimit - activeLoans }} tersisa
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Policy -->
                <div
                  class="flex items-start gap-3 rounded-xl bg-[#DDF4E7]/60 p-3"
                >
                  <span
                    class="material-symbols-outlined mt-0.5 shrink-0 text-xl text-[#26667F]"
                  >
                    info
                  </span>

                  <div>
                    <h3 class="text-sm font-semibold text-[#124170]">
                      Kebijakan Peminjaman
                    </h3>

                    <p class="mt-1 text-xs leading-relaxed text-[#6F8B95]">
                      Buku harus dikembalikan atau diperpanjang sebelum batas
                      waktu peminjaman. Perpanjangan dapat dilakukan maksimal
                      dua kali selama tidak ada antrean pemesanan dari siswa
                      lain.
                    </p>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-2 pt-1">
                  <button
                    type="button"
                    :disabled="isProcessing || isComplete"
                    @click="confirmBorrow"
                    class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#26667F] text-sm font-semibold text-white shadow-md transition hover:bg-[#004e65] active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-80"
                  >
                    <span
                      v-if="isProcessing"
                      class="material-symbols-outlined animate-spin text-xl"
                    >
                      progress_activity
                    </span>

                    <span
                      v-else
                      class="material-symbols-outlined text-xl"
                    >
                      {{ isComplete ? 'verified' : 'check_circle' }}
                    </span>

                    <span>
                      {{
                        isProcessing
                          ? 'Memproses Peminjaman...'
                          : isComplete
                            ? 'Peminjaman Berhasil!'
                            : 'Konfirmasi Peminjaman'
                      }}
                    </span>
                  </button>

                  <p
                    v-if="isComplete"
                    class="rounded-lg bg-[#E8F8F0] p-3 text-center text-xs leading-relaxed text-[#006c45]"
                    role="status"
                  >
                    Simulasi peminjaman selesai. Data belum disimpan ke server.
                  </p>

                  <button
                    type="button"
                    @click="resetScan"
                    class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-white text-sm font-semibold text-[#26667F] transition hover:bg-[#E2F9EC]"
                  >
                    <span class="material-symbols-outlined text-lg">
                      close
                    </span>

                    Batalkan dan Pindai Lagi
                  </button>
                </div>
              </div>
            </div>
          </section>

          <!-- SCANNING GUIDELINES -->
          <section class="grid grid-cols-1 gap-4 pt-2 md:grid-cols-3">
            <article
              class="flex items-start gap-3 rounded-2xl bg-white p-5 shadow-sm"
            >
              <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E2F9EC] text-[#26667F]"
              >
                <span class="material-symbols-outlined text-xl">
                  center_focus_strong
                </span>
              </div>

              <div>
                <h2 class="text-sm font-semibold text-[#124170]">
                  Posisi Barcode yang Tepat
                </h2>

                <p class="mt-1 text-xs leading-relaxed text-[#6F8B95]">
                  Letakkan barcode sekitar 10–15 cm dari pemindai. Pastikan
                  sampul buku rata dan tidak memantulkan cahaya.
                </p>
              </div>
            </article>

            <article
              class="flex items-start gap-3 rounded-2xl bg-white p-5 shadow-sm"
            >
              <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E2F9EC] text-[#26667F]"
              >
                <span class="material-symbols-outlined text-xl">
                  receipt_long
                </span>
              </div>

              <div>
                <h2 class="text-sm font-semibold text-[#124170]">
                  Bukti Peminjaman Digital
                </h2>

                <p class="mt-1 text-xs leading-relaxed text-[#6F8B95]">
                  Setelah peminjaman dikonfirmasi, bukti transaksi dan
                  pengingat pengembalian dapat diberikan melalui akun siswa.
                </p>
              </div>
            </article>

            <article
              class="flex items-start gap-3 rounded-2xl bg-white p-5 shadow-sm"
            >
              <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E2F9EC] text-[#26667F]"
              >
                <span class="material-symbols-outlined text-xl">
                  support_agent
                </span>
              </div>

              <div>
                <h2 class="text-sm font-semibold text-[#124170]">
                  Butuh Bantuan Pustakawan?
                </h2>

                <p class="mt-1 text-xs leading-relaxed text-[#6F8B95]">
                  Jika barcode rusak atau tidak terbaca, silakan kunjungi meja
                  pustakawan untuk mendapatkan bantuan.
                </p>
              </div>
            </article>
          </section>
        </div>
      </div>
    </main>

    <!-- FOOTER -->
    <StudentFooter />
  </div>
</template>