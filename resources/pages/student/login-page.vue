<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const studentId = ref('')
const password = ref('')
const remember = ref(false)
const showPassword = ref(false)
const isSubmitting = ref(false)
const errorMessage = ref('')
const fieldErrors = ref({})

async function login() {
  if (isSubmitting.value) {
    return
  }

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content

  if (!csrfToken) {
    errorMessage.value = 'Halaman ini kedaluwarsa. Muat ulang sebelum masuk.'
    return
  }

  isSubmitting.value = true
  errorMessage.value = ''
  fieldErrors.value = {}

  try {
    const response = await fetch('/login', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({
        nis: studentId.value,
        password: password.value,
        remember: remember.value,
      }),
    })
    const result = await response.json()

    if (!response.ok) {
      fieldErrors.value = result.errors ?? {}
      errorMessage.value = result.message ?? 'Tidak dapat masuk. Periksa kembali data Anda.'
      return
    }

    if (typeof result.redirect !== 'string') {
      throw new Error('The login response is missing its redirect destination.')
    }

    await router.replace(result.redirect)
  } catch (error) {
    console.error('Unable to sign in to the student portal.', error)
    errorMessage.value = 'Tidak dapat menghubungi server. Periksa koneksi lalu coba lagi.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <main class="student-theme min-h-screen bg-[#F4F6F8] text-[#20384D]">
    <div class="mx-auto grid min-h-screen max-w-[1440px] grid-cols-1 lg:grid-cols-[1.05fr_0.95fr]">
      <section class="relative flex min-h-[300px] flex-col justify-between overflow-hidden bg-[#18394F] px-6 py-7 text-white sm:px-10 sm:py-9 lg:min-h-screen lg:px-14 lg:py-12">
        <div class="pointer-events-none absolute -right-20 top-24 h-72 w-72 rounded-full bg-[#315D78]/50 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-28 -left-20 h-80 w-80 rounded-full bg-[#006c45]/35 blur-3xl"></div>

        <div class="relative flex items-center gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10">
            <img src="/images/image-logo-dark.svg" alt="Diginesh" class="h-9 w-9 object-contain" />
          </div>
          <div>
            <p class="text-xl font-bold tracking-tight">Diginesh</p>
            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-white/65">Perpustakaan sekolah</p>
          </div>
        </div>

        <div class="relative my-12 max-w-xl lg:my-0">
          <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-[#A8C8E5]">Ruang untuk terus bertumbuh</p>
          <h1 class="max-w-lg text-3xl font-semibold leading-tight tracking-tight sm:text-4xl lg:text-5xl">
            Temukan bacaan berikutnya.
          </h1>
          <p class="mt-5 max-w-md text-sm leading-6 text-white/70 sm:text-base">
            Masuk dengan nomor induk siswa untuk melihat koleksi dan layanan perpustakaan.
          </p>
        </div>

        <p class="relative hidden text-xs text-white/55 lg:block">
          Portal Perpustakaan Siswa
        </p>
      </section>

      <section class="flex items-center justify-center px-5 py-12 sm:px-8 lg:px-12">
        <div class="w-full max-w-md">
          <div class="mb-8">
            <p class="text-sm font-semibold text-[#315D78]">Selamat datang</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-[#20384D]">Masuk ke akun siswa</h2>
            <p class="mt-3 text-sm leading-6 text-[#637587]">
              Gunakan NIS dan kata sandi yang diberikan oleh sekolah.
            </p>
          </div>

          <form class="flex flex-col gap-5" @submit.prevent="login">
            <div v-if="errorMessage" role="alert" class="rounded-xl border border-[#F2C9C9] bg-[#FDE8E8] px-4 py-3 text-sm text-[#9A3030]">
              {{ errorMessage }}
            </div>

            <label for="student-id" class="flex flex-col gap-2 text-sm font-semibold text-[#20384D]">
              Nomor induk siswa
              <input
                id="student-id"
                v-model.trim="studentId"
                name="nis"
                type="text"
                autocomplete="username"
                required
                autofocus
                :aria-invalid="Boolean(fieldErrors.nis)"
                aria-describedby="student-id-error"
                placeholder="Masukkan NIS"
                class="h-12 rounded-xl border border-[#D4DEE5] bg-white px-4 text-sm font-normal outline-none transition placeholder:text-[#94A2AE] focus:border-[#315D78] focus:ring-2 focus:ring-[#315D78]/15"
              />
              <span v-if="fieldErrors.nis?.[0]" id="student-id-error" class="text-xs font-normal text-[#9A3030]">
                {{ fieldErrors.nis[0] }}
              </span>
            </label>

            <label for="student-password" class="flex flex-col gap-2 text-sm font-semibold text-[#20384D]">
              Kata sandi
              <span class="relative">
                <input
                  id="student-password"
                  v-model="password"
                  name="password"
                  :type="showPassword ? 'text' : 'password'"
                  autocomplete="current-password"
                  required
                  :aria-invalid="Boolean(fieldErrors.password)"
                  aria-describedby="student-password-error"
                  placeholder="Masukkan kata sandi"
                  class="h-12 w-full rounded-xl border border-[#D4DEE5] bg-white px-4 pr-12 text-sm font-normal outline-none transition placeholder:text-[#94A2AE] focus:border-[#315D78] focus:ring-2 focus:ring-[#315D78]/15"
                />
                <button
                  type="button"
                  class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-[#637587] transition hover:text-[#20384D]"
                  :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                  @click="showPassword = !showPassword"
                >
                  <span class="material-symbols-outlined text-xl">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                </button>
              </span>
              <span v-if="fieldErrors.password?.[0]" id="student-password-error" class="text-xs font-normal text-[#9A3030]">
                {{ fieldErrors.password[0] }}
              </span>
            </label>

            <label class="flex w-fit items-center gap-2 text-sm text-[#637587]">
              <input
                v-model="remember"
                type="checkbox"
                class="h-4 w-4 rounded border-[#AAB8C2] accent-[#315D78]"
              />
              Ingat saya di perangkat ini
            </label>

            <button
              type="submit"
              :disabled="isSubmitting"
              class="flex h-12 items-center justify-center gap-2 rounded-xl bg-[#315D78] px-4 text-sm font-semibold text-white transition hover:bg-[#20384D] disabled:cursor-not-allowed disabled:opacity-65"
            >
              <span v-if="isSubmitting" class="material-symbols-outlined animate-spin text-lg">progress_activity</span>
              {{ isSubmitting ? 'Memeriksa akun...' : 'Masuk' }}
            </button>
          </form>

          <p class="mt-8 border-t border-[#D4DEE5] pt-5 text-center text-xs leading-5 text-[#637587]">
            Belum memiliki atau lupa kata sandi? Hubungi petugas perpustakaan sekolah.
          </p>
        </div>
      </section>
    </div>
  </main>
</template>
