<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// ===============================
// LOGIN STATE
// ===============================
const librarianId = ref('s.jenkins@greenwood.edu')
const password = ref('')
const rememberTerminal = ref(true)

const showPassword = ref(false)
const isLoading = ref(false)
const loginSuccess = ref(false)

// ===============================
// LOGIN
// ===============================
function handleLogin() {
  // Cegah klik berkali-kali
  if (isLoading.value) return

  // Validasi sederhana
  if (!librarianId.value || !password.value) {
    alert('Librarian ID dan password wajib diisi.')
    return
  }

  isLoading.value = true
  loginSuccess.value = false

  // Simulasi proses login
  setTimeout(() => {
    isLoading.value = false
    loginSuccess.value = true

    // Setelah login berhasil
    setTimeout(() => {
      router.push('/admin/dashboard')
    }, 500)
  }, 800)
}

// ===============================
// FORGOT PASSWORD
// ===============================
function forgotPassword() {
  alert(
    'Link reset password akan dikirim ke administrator perpustakaan.'
  )
}

// ===============================
// SSO
// ===============================
function triggerSSO() {
  alert('Mengalihkan ke sistem SSO sekolah...')
}
</script>

<template>
  <div
    class="min-h-screen bg-[#DDF4E7] font-['Plus_Jakarta_Sans',sans-serif] text-[#0c1f17] antialiased"
  >
    <div class="grid min-h-screen grid-cols-1 lg:grid-cols-12">

      <!-- ================================================= -->
      <!-- LEFT : HERO -->
      <!-- ================================================= -->
      <section
        class="relative flex min-h-[620px] flex-col justify-between overflow-hidden bg-[#124170] p-8 text-white sm:p-12 lg:col-span-6 lg:min-h-screen lg:p-16 xl:col-span-7"
      >

        <!-- Background decoration -->
        <div
          class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-[#26667F]/30 blur-3xl"
        ></div>

        <div
          class="pointer-events-none absolute -bottom-24 right-0 h-80 w-80 rounded-full bg-[#006c45]/40 blur-3xl"
        ></div>

        <div
          class="pointer-events-none absolute left-1/3 top-1/2 h-64 w-64 rounded-full bg-[#67C090]/10 blur-2xl"
        ></div>

        <!-- TOP BRAND -->
        <div
          class="relative z-10 flex items-center justify-between gap-4"
        >
          <div class="flex items-center gap-3">

            <div
              class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#26667F] text-white shadow-sm"
            >
              <span
                class="material-symbols-outlined text-2xl"
                style="font-variation-settings: 'FILL' 1"
              >
                auto_stories
              </span>
            </div>

            <div>
              <div class="flex flex-wrap items-center gap-2">

                <span
                  class="text-2xl font-bold tracking-tight text-white"
                >
                  Diginesh
                </span>

                <span
                  class="rounded-full bg-[#E2F9EC]/15 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[#9BF5C1]"
                >
                  Staff Desk
                </span>

              </div>

              <p
                class="mt-0.5 text-[10px] font-semibold uppercase tracking-wider text-[#A8C8E5]"
              >
                Library Management Suite
              </p>
            </div>

          </div>

          <div
            class="hidden items-center gap-2 rounded-full bg-[#002B51]/50 px-3 py-1.5 text-[10px] font-semibold text-[#E2F9EC] backdrop-blur-sm sm:flex"
          >
            <span
              class="h-2 w-2 animate-pulse rounded-full bg-[#67C090]"
            ></span>

            Campus Terminal Active
          </div>
        </div>

        <!-- HERO CONTENT -->
        <div
          class="relative z-10 my-10 max-w-2xl space-y-8 lg:my-14"
        >

          <div class="space-y-4">

            <div
              class="inline-flex items-center gap-2 rounded-full bg-[#26667F]/40 px-3 py-1 text-xs font-semibold text-[#BEE9FF]"
            >
              <span class="material-symbols-outlined text-base">
                verified
              </span>

              Academic Circulation Desk
            </div>

            <h1
              class="text-4xl font-bold leading-tight tracking-tight text-white lg:text-[40px] lg:leading-[48px]"
            >
              Kelola perpustakaan sekolah dengan lebih mudah.
            </h1>

            <p
              class="max-w-xl text-sm leading-relaxed text-[#A8C8E5] sm:text-base"
            >
              Kelola inventaris buku, peminjaman, pengembalian,
              data siswa, dan aktivitas perpustakaan dalam satu
              workspace administrasi.
            </p>

          </div>

          <!-- SYSTEM CARD -->
          <div
            class="relative overflow-hidden rounded-2xl bg-[#002B51]/60 p-6 shadow-2xl backdrop-blur-md"
          >

            <div
              class="mb-4 flex items-center justify-between gap-3"
            >

              <div class="flex items-center gap-2">

                <span
                  class="material-symbols-outlined text-xl text-[#9BF5C1]"
                >
                  account_tree
                </span>

                <span
                  class="text-[11px] font-bold uppercase tracking-wider text-white"
                >
                  Circulation Desk
                </span>

              </div>

              <span
                class="rounded bg-[#004A2D]/80 px-2 py-0.5 font-mono text-[10px] font-bold text-[#9BF5C1]"
              >
                RFID & BARCODE
              </span>

            </div>

            <!-- LIBRARY FLOW -->
            <div
              class="grid grid-cols-3 items-center gap-3"
            >

              <!-- BOOK -->
              <div
                class="flex flex-col items-center justify-center rounded-xl bg-[#26667F]/40 p-4"
              >
                <span
                  class="material-symbols-outlined text-3xl text-[#9BF5C1]"
                >
                  menu_book
                </span>

                <span
                  class="mt-2 text-[10px] font-semibold text-[#D3E3FF]"
                >
                  Books
                </span>
              </div>

              <!-- SCAN -->
              <div class="flex flex-col items-center">

                <span
                  class="material-symbols-outlined text-[#67C090]"
                >
                  arrow_forward
                </span>

                <div
                  class="my-2 flex h-14 w-20 items-center justify-center rounded-xl bg-[#002B51] shadow-inner"
                >
                  <span
                    class="material-symbols-outlined text-3xl text-[#9BF5C1]"
                  >
                    qr_code_scanner
                  </span>
                </div>

                <span
                  class="text-[10px] font-semibold text-[#D3E3FF]"
                >
                  Scan
                </span>

                <span
                  class="material-symbols-outlined text-[#67C090]"
                >
                  arrow_forward
                </span>

              </div>

              <!-- STUDENT -->
              <div
                class="flex flex-col items-center justify-center rounded-xl bg-[#26667F]/40 p-4"
              >

                <span
                  class="material-symbols-outlined text-3xl text-[#9BF5C1]"
                >
                  person
                </span>

                <span
                  class="mt-2 text-[10px] font-semibold text-[#D3E3FF]"
                >
                  Student
                </span>

              </div>

            </div>

            <!-- TELEMETRY -->
            <div
              class="mt-5 grid grid-cols-3 gap-3 border-t border-white/10 pt-4"
            >

              <div class="flex items-center gap-1.5">

                <span
                  class="material-symbols-outlined text-sm text-[#9BF5C1]"
                >
                  bolt
                </span>

                <span
                  class="truncate text-[10px] text-[#A8C8E5]"
                >
                  Real-time sync
                </span>

              </div>

              <div class="flex items-center gap-1.5">

                <span
                  class="material-symbols-outlined text-sm text-[#BEE9FF]"
                >
                  menu_book
                </span>

                <span
                  class="truncate text-[10px] text-[#A8C8E5]"
                >
                  Library Inventory
                </span>

              </div>

              <div class="flex items-center gap-1.5">

                <span
                  class="material-symbols-outlined text-sm text-[#9BF5C1]"
                >
                  fingerprint
                </span>

                <span
                  class="truncate text-[10px] text-[#A8C8E5]"
                >
                  Tap Card Ready
                </span>

              </div>

            </div>

          </div>

          <!-- FEATURES -->
          <div class="flex flex-wrap gap-2.5">

            <div
              class="flex items-center gap-2 rounded-xl bg-white/10 px-3.5 py-2 text-xs font-semibold backdrop-blur-sm"
            >
              <span
                class="material-symbols-outlined text-base text-[#9BF5C1]"
              >
                barcode_scanner
              </span>

              ISBN Lookup
            </div>

            <div
              class="flex items-center gap-2 rounded-xl bg-white/10 px-3.5 py-2 text-xs font-semibold backdrop-blur-sm"
            >
              <span
                class="material-symbols-outlined text-base text-[#BEE9FF]"
              >
                assignment_turned_in
              </span>

              Borrowing
            </div>

            <div
              class="flex items-center gap-2 rounded-xl bg-white/10 px-3.5 py-2 text-xs font-semibold backdrop-blur-sm"
            >
              <span
                class="material-symbols-outlined text-base text-[#9BF5C1]"
              >
                group
              </span>

              Student Records
            </div>

          </div>

        </div>

        <!-- BOTTOM STATUS -->
        <div
          class="relative z-10 flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-6 text-[10px] text-[#A8C8E5]"
        >

          <div class="flex items-center gap-4">

            <span
              class="flex items-center gap-1.5 text-[#9BF5C1]"
            >
              <span
                class="h-2 w-2 rounded-full bg-[#67C090]"
              ></span>

              System Operational
            </span>

            <span
              class="hidden text-white/30 sm:inline"
            >
              •
            </span>

            <span class="flex items-center gap-1">

              <span
                class="material-symbols-outlined text-sm"
              >
                lock
              </span>

              Secure Session

            </span>

          </div>

          <span
            class="font-mono text-[10px] text-[#A8C8E5]/80"
          >
            © 2026 Diginesh
          </span>

        </div>

      </section>

      <!-- ================================================= -->
      <!-- RIGHT : LOGIN -->
      <!-- ================================================= -->
      <section
        class="relative flex min-h-[620px] flex-col items-center justify-center bg-[#DDF4E7] p-6 sm:p-10 lg:col-span-6 lg:min-h-screen lg:p-14 xl:col-span-5"
      >

        <!-- SCHOOL IDENTITY -->
        <div
          class="mb-6 flex w-full max-w-md items-center justify-between gap-3"
        >

          <div class="flex items-center gap-2">

            <div
              class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#E2F9EC] text-[#26667F]"
            >
              <span class="material-symbols-outlined text-lg">
                school
              </span>
            </div>

            <span
              class="text-[10px] font-bold uppercase tracking-wider text-[#26667F]"
            >
              Library Administration
            </span>

          </div>

          <span
            class="font-mono text-[10px] text-[#6F8B95]"
          >
            PORTAL ID #419
          </span>

        </div>

        <!-- LOGIN CARD -->
        <div
          class="relative w-full max-w-md rounded-2xl bg-white p-8 shadow-sm sm:p-10"
        >

          <!-- CARD HEADER -->
          <div class="mb-7">

            <div
              class="mb-3 inline-flex rounded-xl bg-[#E2F9EC] p-2.5 text-[#26667F]"
            >
              <span class="material-symbols-outlined text-2xl">
                shield_person
              </span>
            </div>

            <h2
              class="text-2xl font-bold tracking-tight text-[#124170]"
            >
              Welcome back, Admin
            </h2>

            <p
              class="mt-1.5 text-sm leading-relaxed text-[#6F8B95]"
            >
              Masukkan akun staff untuk mengakses dashboard
              administrasi perpustakaan.
            </p>

          </div>

          <!-- LOGIN FORM -->
          <form
            class="space-y-5"
            @submit.prevent="handleLogin"
          >

            <!-- LIBRARIAN ID -->
            <div class="space-y-1.5">

              <label
                for="librarian-id"
                class="block text-xs font-semibold text-[#124170]"
              >
                Librarian ID atau Email
              </label>

              <div class="relative flex items-center">

                <span
                  class="material-symbols-outlined pointer-events-none absolute left-3.5 text-xl text-[#6F8B95]"
                >
                  badge
                </span>

                <input
                  id="librarian-id"
                  v-model="librarianId"
                  autocomplete="username"
                  type="text"
                  placeholder="Masukkan ID atau email"
                  required
                  class="h-11 w-full rounded-xl bg-[#E2F9EC] pl-11 pr-4 text-sm text-[#124170] outline-none transition placeholder:text-[#6F8B95] focus:bg-white focus:ring-2 focus:ring-[#26667F]/20"
                />

              </div>

            </div>

            <!-- PASSWORD -->
            <div class="space-y-1.5">

              <div class="flex items-center justify-between">

                <label
                  for="password-input"
                  class="block text-xs font-semibold text-[#124170]"
                >
                  Password
                </label>

                <button
                  type="button"
                  @click="forgotPassword"
                  class="text-[11px] font-semibold text-[#26667F] underline-offset-2 transition hover:text-[#124170] hover:underline"
                >
                  Forgot password?
                </button>

              </div>

              <div class="relative flex items-center">

                <span
                  class="material-symbols-outlined pointer-events-none absolute left-3.5 text-xl text-[#6F8B95]"
                >
                  lock
                </span>

                <input
                  id="password-input"
                  v-model="password"
                  autocomplete="current-password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Masukkan password"
                  required
                  class="h-11 w-full rounded-xl bg-[#E2F9EC] pl-11 pr-11 text-sm text-[#124170] outline-none transition placeholder:text-[#6F8B95] focus:bg-white focus:ring-2 focus:ring-[#26667F]/20"
                />

                <button
                  type="button"
                  aria-label="Toggle password visibility"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 flex h-8 w-8 items-center justify-center rounded-md text-[#6F8B95] transition hover:bg-white hover:text-[#124170]"
                >
                  <span class="material-symbols-outlined text-xl">
                    {{
                      showPassword
                        ? 'visibility_off'
                        : 'visibility'
                    }}
                  </span>
                </button>

              </div>

            </div>

            <!-- REMEMBER -->
            <div
              class="flex items-center justify-between pt-1"
            >

              <label
                class="flex cursor-pointer select-none items-center gap-2.5"
              >

                <input
                  v-model="rememberTerminal"
                  type="checkbox"
                  class="h-4 w-4 cursor-pointer accent-[#26667F]"
                />

                <span
                  class="text-xs font-medium text-[#6F8B95]"
                >
                  Remember this terminal
                </span>

              </label>

              <span
                class="rounded bg-[#E2F9EC] px-2 py-0.5 font-mono text-[10px] font-semibold text-[#26667F]"
              >
                Kiosk #L12
              </span>

            </div>

            <!-- LOGIN BUTTON -->
            <div class="pt-2">

              <button
                :disabled="isLoading"
                type="submit"
                class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#26667F] text-sm font-semibold text-white shadow-sm transition-all hover:bg-[#004e65] hover:shadow-md active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-70"
              >

                <!-- LOADING -->
                <span
                  v-if="isLoading"
                  class="material-symbols-outlined animate-spin text-lg"
                >
                  sync
                </span>

                <!-- SUCCESS -->
                <span
                  v-else-if="loginSuccess"
                  class="material-symbols-outlined text-lg text-[#9BF5C1]"
                >
                  check_circle
                </span>

                <span v-if="isLoading">
                  Authenticating...
                </span>

                <span v-else-if="loginSuccess">
                  Authenticated!
                </span>

                <template v-else>

                  <span>
                    Sign In to Staff Desk
                  </span>

                  <span class="material-symbols-outlined text-lg">
                    arrow_forward
                  </span>

                </template>

              </button>

            </div>

          </form>

          <!-- DIVIDER -->
          <div
            class="relative my-6 flex items-center justify-center"
          >

            <div
              class="h-px w-full bg-[#C2E2D4]"
            ></div>

            <span
              class="absolute bg-white px-3 text-[10px] font-semibold uppercase tracking-wider text-[#6F8B95]"
            >
              Or single sign-on
            </span>

          </div>

          <!-- SSO -->
          <button
            type="button"
            @click="triggerSSO"
            class="flex h-11 w-full items-center justify-center gap-2.5 rounded-xl bg-[#E2F9EC] text-xs font-semibold text-[#124170] transition hover:bg-[#D1E8DB]"
          >

            <span
              class="material-symbols-outlined text-xl text-[#26667F]"
            >
              account_balance
            </span>

            <span>
              School Google / Microsoft Workspace
            </span>

          </button>

          <!-- HELP NOTICE -->
          <div
            class="mt-6 flex items-start gap-3 rounded-xl bg-[#E2F9EC] p-3.5"
          >

            <span
              class="material-symbols-outlined mt-0.5 text-lg text-[#26667F]"
            >
              info
            </span>

            <p
              class="text-xs leading-snug text-[#6F8B95]"
            >
              Butuh bantuan akses atau re-enrollment kartu?
              Hubungi
              <span class="font-semibold text-[#124170]">
                Library Administrator
              </span>
              atau IT Support.
            </p>

          </div>

          <!-- SECURITY -->
          <div class="mt-6 text-center">

            <span
              class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-[#6F8B95]"
            >

              <span
                class="material-symbols-outlined text-sm text-[#26667F]"
              >
                verified_user
              </span>

              Protected Library Staff Authentication

            </span>

          </div>

        </div>

        <!-- HELPER LINKS -->
        <div
          class="mt-6 flex w-full max-w-md items-center justify-between gap-3 text-[11px] text-[#6F8B95]"
        >

          <button
            class="flex items-center gap-1 transition hover:text-[#124170]"
          >
            <span
              class="material-symbols-outlined text-sm"
            >
              help
            </span>

            System Manual
          </button>

          <button
            class="flex items-center gap-1 transition hover:text-[#124170]"
          >
            <span
              class="material-symbols-outlined text-sm"
            >
              schedule
            </span>

            Duty Shift
          </button>

          <button
            class="flex items-center gap-1 transition hover:text-[#124170]"
          >
            <span
              class="material-symbols-outlined text-sm"
            >
              contact_support
            </span>

            IT Helpdesk
          </button>

        </div>

      </section>

    </div>
  </div>
</template>

<style scoped>
button {
  cursor: pointer;
}

button:disabled {
  cursor: not-allowed;
}
</style>