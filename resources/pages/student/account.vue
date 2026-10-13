<script setup>
import { computed, onBeforeUnmount, ref } from 'vue'
import { RouterLink } from 'vue-router'
import StudentNavbar from '../../components/student/StudentNavbar.vue'
import StudentFooter from '../../components/student/StudentFooter.vue'

const student = ref({
  name: 'Adel',
  grade: 'Kelas XI',
  className: 'XII-A',
  major: 'Sistem Informasi Jaringan dan Aplikasi',
  memberSince: 'Agustus 2024',
  email: 'adel@siswa.sch.id',
  nis: '2023-04921',
  hometeacher: 'Ratna Yunitasari, S.T.',
})

const stats = [
  { icon: 'book_online', label: 'Sedang dipinjam', value: '2 / 3', note: '1 slot tersisa' },
  { icon: 'history', label: 'Buku dikembalikan', value: '14 buku', note: '' },
  { icon: 'pending_actions', label: 'Denda tertunda', value: 'Rp0', note: 'Tidak ada denda', green: true },
  { icon: 'bookmark', label: 'Buku tersimpan', value: '5 judul', note: '' },
]

const books = [
  { title: 'Principles of Physics', author: 'Halliday & Resnick', status: 'Tersedia', available: true },
  { title: 'The Great Gatsby', author: 'F. Scott Fitzgerald', status: 'Tersedia', available: true },
  { title: 'Molecular Biology', author: 'Bruce Alberts dkk.', status: 'Dalam reservasi', available: false },
]

const preferences = ref([
  {
    title: 'Pengingat batas pengembalian',
    description: 'Terima notifikasi 2 hari sebelum masa peminjaman berakhir.',
    icon: 'alarm',
    enabled: true,
  },
  {
    title: 'Riwayat membaca dan peminjaman',
    description: 'Simpan riwayat buku yang pernah dipinjam dan catatan bacaan.',
    icon: 'menu_book',
    enabled: true,
  },
  {
    title: 'Notifikasi buku baru',
    description: 'Dapatkan informasi saat buku baru tersedia di bidang pilihanmu.',
    icon: 'new_releases',
    enabled: true,
  },
])

const barcodeOpen = ref(false)
const editOpen = ref(false)
const rfidBusy = ref(false)
const feedback = ref('')
const lostCardReported = ref(false)

let rfidTimer
let feedbackTimer

const savedPreferenceCount = computed(
  () => preferences.value.filter((item) => item.enabled).length,
)

function showFeedback(message) {
  feedback.value = message
  clearTimeout(feedbackTimer)
  feedbackTimer = setTimeout(() => {
    feedback.value = ''
  }, 3500)
}

function saveDetails() {
  editOpen.value = false
  showFeedback('Perubahan profil berhasil diterapkan pada tampilan ini.')
}

function reportLostCard() {
  lostCardReported.value = true
  showFeedback('Laporan simulasi diterima. Hubungi petugas perpustakaan untuk bantuan lebih lanjut.')
}

function logoutNotice() {
  showFeedback('Fitur keluar akun belum terhubung dengan sistem autentikasi.')
}

onBeforeUnmount(() => {
  clearTimeout(rfidTimer)
  clearTimeout(feedbackTimer)
})
</script>

<template>
  <div class="min-h-screen bg-[#DDF4E7]">
    <StudentNavbar />

    <main class="pt-20">
      <div class="mx-auto flex w-full max-w-[1200px] flex-col gap-6 px-4 py-6 md:px-6">

        <!-- Judul halaman -->
        <section class="flex flex-col gap-2">
          <h1 class="text-2xl font-bold tracking-tight text-[#124170] md:text-[32px]">
            Akun dan Profil Siswa
          </h1>
          <p class="max-w-3xl text-sm leading-6 text-[#6F8B95]">
            Kelola informasi siswa, kartu perpustakaan, hak peminjaman, dan preferensi notifikasi.
          </p>
        </section>

        <!-- Notifikasi tindakan -->
        <div
          v-if="feedback"
          role="status"
          class="flex items-start gap-2 rounded-xl border border-[#C2E2D4] bg-white p-4 text-sm text-[#124170] shadow-sm"
        >
          <span class="material-symbols-outlined text-[#26667F]">info</span>
          <span>{{ feedback }}</span>
          <button
            type="button"
            aria-label="Tutup notifikasi"
            class="ml-auto"
            @click="feedback = ''"
          >
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">

          <!-- KOLOM KIRI -->
          <div class="flex flex-col gap-6 lg:col-span-8">

            <!-- Profil dan statistik -->
            <section class="relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_18px_rgba(18,65,112,0.05)] md:p-6">
              <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-[#26667F]/5 blur-2xl"></div>

              <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                <div class="flex items-center gap-4">
                  <div class="relative flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-[#26667F] text-2xl font-bold text-white shadow-sm">
                    {{ student.name.charAt(0).toUpperCase() }}
                    <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-[#67C090] text-white shadow-sm">
                      <span class="material-symbols-outlined text-sm">check</span>
                    </span>
                  </div>

                  <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                      <h2 class="text-xl font-bold tracking-tight text-[#124170]">
                        {{ student.name }}
                      </h2>
                      <span class="inline-flex items-center gap-1 rounded-full bg-[#E8F8F0] px-2.5 py-1 text-[11px] font-semibold text-[#006c45]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]"></span>
                        Anggota aktif
                      </span>
                    </div>

                    <p class="mt-1 text-sm font-medium text-[#26667F]">
                      {{ student.grade }} · {{ student.className }}
                    </p>
                    <p class="mt-1 text-xs text-[#6F8B95]">
                      {{ student.major }}
                    </p>
                    <p class="mt-2 flex items-center gap-1 text-xs text-[#6F8B95]">
                      <span class="material-symbols-outlined text-sm">calendar_month</span>
                      Anggota sejak {{ student.memberSince }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Form edit profil -->
              <form
                v-if="editOpen"
                class="relative mt-6 grid grid-cols-1 gap-4 rounded-xl bg-[#F4F8F6] p-4 sm:grid-cols-2"
                @submit.prevent="saveDetails"
              >
                <label class="flex flex-col gap-1.5 text-xs font-semibold text-[#6F8B95]">
                  Nama siswa
                  <input
                    v-model="student.name"
                    required
                    class="rounded-lg border border-[#CFE5D8] bg-white px-3 py-2.5 text-sm text-[#124170] outline-none focus:border-[#26667F]"
                  />
                </label>

                <label class="flex flex-col gap-1.5 text-xs font-semibold text-[#6F8B95]">
                  Kelas
                  <input
                    v-model="student.className"
                    required
                    class="rounded-lg border border-[#CFE5D8] bg-white px-3 py-2.5 text-sm text-[#124170] outline-none focus:border-[#26667F]"
                  />
                </label>

                <label class="flex flex-col gap-1.5 text-xs font-semibold text-[#6F8B95] sm:col-span-2">
                  Email sekolah
                  <input
                    v-model="student.email"
                    type="email"
                    required
                    class="rounded-lg border border-[#CFE5D8] bg-white px-3 py-2.5 text-sm text-[#124170] outline-none focus:border-[#26667F]"
                  />
                </label>

                <div class="flex flex-wrap gap-2 sm:col-span-2">
                  <button
                    type="submit"
                    class="rounded-xl bg-[#26667F] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#124170]"
                  >
                    Simpan perubahan
                  </button>
                  <button
                    type="button"
                    class="rounded-xl border border-[#CFE5D8] px-4 py-2.5 text-sm font-semibold text-[#124170] hover:bg-white"
                    @click="editOpen = false"
                  >
                    Batal
                  </button>
                </div>
              </form>

              <!-- Statistik -->
              <div class="mt-6 grid grid-cols-2 gap-3 rounded-xl bg-[#E2F9EC]/70 p-3 sm:grid-cols-4">
                <div
                  v-for="stat in stats"
                  :key="stat.label"
                  class="flex min-w-0 flex-col gap-1 p-2"
                >
                  <span class="flex items-center gap-1.5 text-xs text-[#6F8B95]">
                    <span
                      class="material-symbols-outlined text-base"
                      :class="stat.green ? 'text-[#67C090]' : ''"
                    >
                      {{ stat.icon }}
                    </span>
                    {{ stat.label }}
                  </span>
                  <strong
                    class="text-base font-bold"
                    :class="stat.green ? 'text-[#006c45]' : 'text-[#124170]'"
                  >
                    {{ stat.value }}
                  </strong>
                  <span v-if="stat.note" class="text-[10px] text-[#6F8B95]">
                    {{ stat.note }}
                  </span>
                </div>
              </div>
            </section>

            <!-- Kartu RFID -->
            <section class="flex flex-col gap-5 rounded-2xl bg-white p-5 shadow-[0_4px_18px_rgba(18,65,112,0.05)] md:p-6">
              <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex items-start gap-3">
                  <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E8F8F0] text-[#006c45]">
                    <span class="material-symbols-outlined text-xl">contactless</span>
                  </div>
                  <div>
                    <div class="flex flex-wrap items-center gap-2">
                      <h3 class="font-semibold text-[#124170]">Kartu Pintar RFID</h3>
                      <span class="rounded-full bg-[#E8F8F0] px-2 py-1 text-[10px] font-semibold text-[#006c45]">
                        Aktif dan tersinkron
                      </span>
                    </div>
                    <p class="mt-1 text-xs leading-5 text-[#6F8B95]">
                      Informasi kartu untuk simulasi layanan mandiri perpustakaan.
                    </p>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-1 gap-4 rounded-xl bg-[#F4F8F6] p-4 md:grid-cols-3">
                <div>
                  <p class="text-xs text-[#6F8B95]">Nomor kartu</p>
                  <p class="mt-1 break-words font-mono text-sm font-bold text-[#124170]">LIB-2024-8841</p>
                  <p class="mt-1 text-[11px] text-[#006c45]">Chip terdaftar</p>
                </div>
                <div>
                  <p class="text-xs text-[#6F8B95]">Penerbitan dan masa berlaku</p>
                  <p class="mt-1 text-sm font-semibold text-[#124170]">1 September 2024</p>
                  <p class="mt-1 text-[11px] text-[#6F8B95]">Periksa masa berlaku ke petugas</p>
                </div>
                <div>
                  <p class="text-xs text-[#6F8B95]">Lokasi layanan</p>
                  <p class="mt-1 text-sm font-semibold text-[#124170]">Perpustakaan utama</p>
                  <p class="mt-1 text-[11px] text-[#006c45]">Stasiun 01 dan 02</p>
                </div>
              </div>

              <div class="flex flex-col items-start justify-between gap-4 rounded-xl bg-[#26667F] p-4 text-white sm:flex-row sm:items-center">
                <div class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-3xl">document_scanner</span>
                  <div>
                    <h4 class="text-sm font-semibold">Pindai untuk meminjam buku</h4>
                    <p class="mt-1 text-xs leading-5 text-white/80">
                      Buka halaman pemindai untuk mulai memindai kode buku.
                    </p>
                  </div>
                </div>
                <RouterLink
                  to="/scan"
                  class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-[#26667F] transition hover:bg-[#E2F9EC] sm:w-auto"
                >
                  <span class="material-symbols-outlined text-lg">qr_code_scanner</span>
                  Buka pemindai
                </RouterLink>
              </div>
            </section>

            <!-- Data pribadi dan akademik -->
            <section class="flex flex-col gap-5 rounded-2xl bg-white p-5 shadow-[0_4px_18px_rgba(18,65,112,0.05)] md:p-6">
              <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#DDF4E7] text-[#124170]">
                  <span class="material-symbols-outlined">badge</span>
                </div>
                <div>
                  <h3 class="font-semibold text-[#124170]">Data Pribadi dan Akademik</h3>
                  <p class="mt-1 text-xs text-[#6F8B95]">Informasi siswa yang tercatat pada sistem.</p>
                </div>
              </div>

              <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="flex items-start gap-3 rounded-xl bg-[#F4F8F6] p-4">
                  <span class="material-symbols-outlined mt-0.5 text-[#26667F]">school</span>
                  <div>
                    <p class="text-xs text-[#6F8B95]">Kelas dan wali kelas</p>
                    <p class="mt-1 text-sm font-semibold text-[#124170]">{{ student.className }}</p>
                    <p class="mt-1 text-xs text-[#6F8B95]">Wali kelas: {{ student.hometeacher }}</p>
                  </div>
                </div>

                <div class="flex min-w-0 items-start gap-3 rounded-xl bg-[#F4F8F6] p-4">
                  <span class="material-symbols-outlined mt-0.5 text-[#26667F]">mail</span>
                  <div class="min-w-0">
                    <p class="text-xs text-[#6F8B95]">Email sekolah</p>
                    <p class="mt-1 break-all text-sm font-semibold text-[#124170]">{{ student.email }}</p>
                    <p class="mt-1 text-xs text-[#6F8B95]">Alamat notifikasi perpustakaan</p>
                  </div>
                </div>

                <div class="flex items-start gap-3 rounded-xl bg-[#F4F8F6] p-4">
                  <span class="material-symbols-outlined mt-0.5 text-[#26667F]">fingerprint</span>
                  <div>
                    <p class="text-xs text-[#6F8B95]">Nomor induk siswa</p>
                    <p class="mt-1 font-mono text-sm font-semibold text-[#124170]">{{ student.nis }}</p>
                    <p class="mt-1 text-xs text-[#6F8B95]">Identitas siswa</p>
                  </div>
                </div>

                <div class="flex items-start gap-3 rounded-xl bg-[#F4F8F6] p-4">
                  <span class="material-symbols-outlined mt-0.5 text-[#26667F]">menu_book</span>
                  <div>
                    <p class="text-xs text-[#6F8B95]">Jurusan</p>
                    <p class="mt-1 text-sm font-semibold text-[#124170]">{{ student.major }}</p>
                    <p class="mt-1 text-xs text-[#6F8B95]">Bidang studi</p>
                  </div>
                </div>
              </div>

              <button
                type="button"
                class="inline-flex items-center gap-1 self-start text-xs font-semibold text-[#26667F] hover:underline"
                @click="editOpen = true"
              >
                Ajukan perubahan data
                <span class="material-symbols-outlined text-sm">open_in_new</span>
              </button>
            </section>

            <!-- Bookmark -->
            <section class="flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-[0_4px_18px_rgba(18,65,112,0.05)] md:p-6">
              <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div class="flex items-center gap-3">
                  <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#DDF4E7] text-[#124170]">
                    <span class="material-symbols-outlined">bookmarks</span>
                  </div>
                  <div>
                    <h3 class="font-semibold text-[#124170]">Daftar Bacaan Saya</h3>
                    <p class="mt-1 text-xs text-[#6F8B95]">Buku yang kamu simpan untuk dibaca nanti.</p>
                  </div>
                </div>

                <RouterLink
                  to="/bookmarks"
                  class="inline-flex items-center gap-1 text-sm font-semibold text-[#26667F] hover:text-[#124170]"
                >
                  Lihat bookmark
                  <span class="material-symbols-outlined text-base">arrow_forward</span>
                </RouterLink>
              </div>

              <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div
                  v-for="book in books"
                  :key="book.title"
                  class="flex items-center justify-between gap-3 rounded-xl bg-[#F4F8F6] p-3"
                >
                  <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-[#124170]">{{ book.title }}</p>
                    <p class="mt-1 truncate text-xs text-[#6F8B95]">{{ book.author }}</p>
                  </div>
                  <span
                    class="shrink-0 rounded-full px-2 py-1 text-[10px] font-semibold"
                    :class="book.available ? 'bg-[#E8F8F0] text-[#006c45]' : 'bg-[#FDE8E8] text-[#ba1a1a]'"
                  >
                    {{ book.status }}
                  </span>
                </div>
              </div>
            </section>

            <!-- Preferensi notifikasi -->
            <section class="flex flex-col gap-5 rounded-2xl bg-white p-5 shadow-[0_4px_18px_rgba(18,65,112,0.05)] md:p-6">
              <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#DDF4E7] text-[#124170]">
                  <span class="material-symbols-outlined">tune</span>
                </div>
                <div>
                  <h3 class="font-semibold text-[#124170]">Pengaturan Notifikasi</h3>
                  <p class="mt-1 text-xs text-[#6F8B95]">
                    {{ savedPreferenceCount }} dari {{ preferences.length }} preferensi diaktifkan.
                  </p>
                </div>
              </div>

              <label
                v-for="(preference, index) in preferences"
                :key="preference.title"
                class="flex cursor-pointer items-start justify-between gap-4 rounded-xl bg-[#F4F8F6] p-4 transition hover:bg-[#E2F9EC]"
              >
                <div class="flex min-w-0 gap-3">
                  <span class="material-symbols-outlined mt-0.5 text-[#26667F]">
                    {{ preference.icon }}
                  </span>
                  <div>
                    <span class="text-sm font-semibold text-[#124170]">{{ preference.title }}</span>
                    <p class="mt-1 text-xs leading-5 text-[#6F8B95]">{{ preference.description }}</p>
                  </div>
                </div>

                <span class="relative mt-1 inline-flex shrink-0">
                  <input
                    v-model="preference.enabled"
                    type="checkbox"
                    class="peer sr-only"
                    :aria-label="preference.title"
                  />
                  <span class="h-6 w-11 rounded-full bg-[#CFE5D8] transition peer-checked:bg-[#26667F]"></span>
                  <span class="pointer-events-none absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition peer-checked:translate-x-5"></span>
                </span>
              </label>

              <p class="text-xs leading-5 text-[#6F8B95]">
                Catatan: preferensi ini saat ini hanya tersimpan selama halaman terbuka dan belum dikirim ke server.
              </p>
            </section>
          </div>

          <!-- KOLOM KANAN -->
          <aside class="flex flex-col gap-6 lg:col-span-4">

            <!-- Kartu digital -->
            <section class="flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-[0_4px_18px_rgba(18,65,112,0.05)]">
              <div class="flex items-center justify-between gap-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#6F8B95]">
                  Kartu Perpustakaan Digital
                </h3>
                <span class="flex items-center gap-1 rounded-full bg-[#E8F8F0] px-2 py-1 text-[10px] font-semibold text-[#006c45]">
                  <span class="h-1.5 w-1.5 rounded-full bg-[#67C090]"></span>
                  Siap digunakan
                </span>
              </div>

              <div class="relative flex flex-col gap-5 overflow-hidden rounded-xl bg-gradient-to-br from-[#004e65] via-[#26667F] to-[#1d4978] p-5 text-white shadow-md">
                <div class="pointer-events-none absolute -bottom-10 -right-8 h-32 w-32 rounded-full bg-white/10"></div>

                <div class="relative z-10 flex items-center justify-between gap-2">
                  <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/15">
                      <span class="material-symbols-outlined">local_library</span>
                    </div>
                    <span class="text-sm font-bold">Diginesh</span>
                  </div>
                  <span class="rounded-md bg-white/15 px-2 py-1 text-[10px]">{{ student.grade }}</span>
                </div>

                <div class="relative z-10 flex items-center gap-3">
                  <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white/15 text-xl font-bold">
                    {{ student.name.charAt(0).toUpperCase() }}
                  </div>
                  <div class="min-w-0">
                    <p class="truncate font-bold">{{ student.name }}</p>
                    <p class="mt-1 text-xs text-white/80">{{ student.className }} · Kartu Siswa</p>
                    <p class="mt-1 font-mono text-[11px] text-white/70">{{ student.nis }}</p>
                  </div>
                </div>

                <div class="relative z-10 rounded-lg bg-white p-3">
                  <svg
                    class="h-12 w-full text-[#0c1f17]"
                    viewBox="0 0 240 48"
                    role="img"
                    aria-label="Ilustrasi kode batang kartu perpustakaan"
                    preserveAspectRatio="none"
                  >
                    <rect
                      v-for="bar in [
                        [0,3],[6,2],[11,5],[19,2],[24,4],[31,2],[36,6],[45,2],
                        [50,3],[56,5],[64,2],[69,4],[76,6],[85,2],[90,3],[96,4],
                        [103,2],[108,5],[116,3],[122,2],[127,6],[136,2],[141,4],
                        [148,3],[154,5],[162,2],[167,4],[174,2],[179,6],[188,3],
                        [194,2],[199,5],[207,4],[214,2],[219,5],[227,3],[233,4]
                      ]"
                      :key="bar[0]"
                      :x="bar[0]"
                      y="0"
                      :width="bar[1]"
                      height="48"
                      fill="currentColor"
                    />
                  </svg>
                  <p class="mt-2 text-center font-mono text-xs font-bold tracking-[0.2em] text-[#0c1f17]">
                    LIB-8849-G11
                  </p>
                </div>
              </div>

              <button
                type="button"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#E2F9EC] px-4 py-3 text-sm font-semibold text-[#124170] transition hover:bg-[#d1f2df]"
                @click="barcodeOpen = true"
              >
                <span class="material-symbols-outlined">fullscreen</span>
                Perbesar kode kartu
              </button>
              <p class="text-xs leading-5 text-[#6F8B95]">
                Kode batang ini merupakan ilustrasi antarmuka, bukan kode identitas yang terhubung ke sistem peminjaman.
              </p>
            </section>

            <!-- Hak peminjaman -->
            <section class="flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-[0_4px_18px_rgba(18,65,112,0.05)]">
              <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#DDF4E7] text-[#124170]">
                  <span class="material-symbols-outlined">verified_user</span>
                </div>
                <h3 class="font-semibold text-[#124170]">Hak dan Kuota Peminjaman</h3>
              </div>

              <div class="flex items-center justify-between gap-3 rounded-lg bg-[#F4F8F6] p-3">
                <span class="flex items-center gap-2 text-xs font-medium text-[#124170]">
                  <span class="material-symbols-outlined text-lg text-[#26667F]">date_range</span>
                  Masa peminjaman
                </span>
                <strong class="text-xs text-[#124170]">14 hari</strong>
              </div>

              <div class="flex items-center justify-between gap-3 rounded-lg bg-[#F4F8F6] p-3">
                <span class="flex items-center gap-2 text-xs font-medium text-[#124170]">
                  <span class="material-symbols-outlined text-lg text-[#26667F]">collections_bookmark</span>
                  Maksimal peminjaman
                </span>
                <strong class="text-xs text-[#124170]">3 buku</strong>
              </div>

              <div class="flex items-center justify-between gap-3 rounded-lg bg-[#F4F8F6] p-3">
                <span class="flex items-center gap-2 text-xs font-medium text-[#124170]">
                  <span class="material-symbols-outlined text-lg text-[#26667F]">replay</span>
                  Perpanjangan
                </span>
                <strong class="text-right text-xs text-[#124170]">1 kali / buku</strong>
              </div>

              <div class="flex items-center justify-between gap-3 rounded-lg bg-[#F4F8F6] p-3">
                <span class="flex items-center gap-2 text-xs font-medium text-[#124170]">
                  <span class="material-symbols-outlined text-lg text-[#26667F]">desktop_windows</span>
                  Akses digital
                </span>
                <strong class="rounded-full bg-[#E8F8F0] px-2 py-1 text-[10px] text-[#006c45]">Tersedia</strong>
              </div>

              <p class="rounded-lg bg-[#E2F9EC]/60 p-3 text-xs leading-5 text-[#6F8B95]">
                Butuh tambahan kuota untuk tugas sekolah? Hubungi petugas perpustakaan.
              </p>
            </section>

            <!-- Keamanan akun -->
            <section class="flex flex-col gap-3 rounded-2xl bg-white p-5 shadow-[0_4px_18px_rgba(18,65,112,0.05)]">
              <h3 class="font-semibold text-[#124170]">Keamanan Kartu dan Akun</h3>

              <button
                type="button"
                class="flex w-full items-center justify-between gap-3 rounded-lg p-3 text-left text-sm text-[#ba1a1a] transition hover:bg-[#FDE8E8]"
                @click="reportLostCard"
              >
                <span class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-lg">credit_card_off</span>
                  {{ lostCardReported ? 'Laporan kartu tercatat' : 'Laporkan kartu hilang' }}
                </span>
                <span class="material-symbols-outlined text-base">chevron_right</span>
              </button>

              <button
                type="button"
                class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#FDE8E8] text-sm font-semibold text-[#ba1a1a] transition hover:bg-[#fbdada]"
                @click="logoutNotice"
              >
                <span class="material-symbols-outlined">logout</span>
                Keluar dari sesi perpustakaan
              </button>

              <p class="pt-2 text-center text-[11px] text-[#6F8B95]">
                Diginesh · Portal Perpustakaan Siswa
              </p>
            </section>
          </aside>
        </div>
      </div>
    </main>

    <StudentFooter />

    <!-- Modal kartu digital -->
    <div
      v-if="barcodeOpen"
      class="fixed inset-0 z-[60] flex items-center justify-center bg-[#004e65]/50 p-4 backdrop-blur-sm"
      @click.self="barcodeOpen = false"
      @keydown.esc="barcodeOpen = false"
    >
      <section
        role="dialog"
        aria-modal="true"
        aria-labelledby="barcode-title"
        class="relative flex w-full max-w-md flex-col items-center gap-5 rounded-2xl bg-white p-6 shadow-2xl"
      >
        <button
          type="button"
          aria-label="Tutup kartu digital"
          class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-[#E2F9EC] text-[#124170] transition hover:bg-[#d1f2df]"
          @click="barcodeOpen = false"
        >
          <span class="material-symbols-outlined">close</span>
        </button>

        <div class="px-6 pt-2 text-center">
          <h2 id="barcode-title" class="text-xl font-bold text-[#124170]">
            Kartu Perpustakaan Digital
          </h2>
          <p class="mt-2 text-sm text-[#6F8B95]">
            Tampilan simulasi kartu untuk layanan mandiri perpustakaan.
          </p>
        </div>

        <div class="w-full rounded-xl border border-[#CFE5D8] bg-white p-4">
          <div class="mb-4 flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#26667F] text-lg font-bold text-white">
              {{ student.name.charAt(0).toUpperCase() }}
            </div>
            <div>
              <p class="font-bold text-[#124170]">{{ student.name }}</p>
              <p class="text-xs text-[#6F8B95]">{{ student.className }} · {{ student.nis }}</p>
            </div>
          </div>

          <svg
            class="h-24 w-full text-[#0c1f17]"
            viewBox="0 0 240 48"
            role="img"
            aria-label="Ilustrasi kode batang kartu"
            preserveAspectRatio="none"
          >
            <rect
              v-for="bar in [
                [0,3],[6,2],[11,5],[19,2],[24,4],[31,2],[36,6],[45,2],
                [50,3],[56,5],[64,2],[69,4],[76,6],[85,2],[90,3],[96,4],
                [103,2],[108,5],[116,3],[122,2],[127,6],[136,2],[141,4],
                [148,3],[154,5],[162,2],[167,4],[174,2],[179,6],[188,3],
                [194,2],[199,5],[207,4],[214,2],[219,5],[227,3],[233,4]
              ]"
              :key="bar[0]"
              :x="bar[0]"
              y="0"
              :width="bar[1]"
              height="48"
              fill="currentColor"
            />
          </svg>

          <p class="mt-3 text-center font-mono text-sm font-bold tracking-[0.25em] text-[#124170]">
            LIB-8849-G11
          </p>
        </div>

        <div class="flex items-center gap-2 text-center text-xs text-[#6F8B95]">
          <span class="material-symbols-outlined text-lg text-[#67C090]">verified</span>
          Kode ini belum terhubung dengan sistem peminjaman nyata.
        </div>

        <button
          type="button"
          class="w-full rounded-xl bg-[#26667F] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#124170]"
          @click="barcodeOpen = false"
        >
          Selesai
        </button>
      </section>
    </div>
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
  font-feature-settings: 'liga';
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}
</style>
