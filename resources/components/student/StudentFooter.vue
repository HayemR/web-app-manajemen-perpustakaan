<template>
  <footer class="w-full bg-white shadow-[0_-4px_18px_rgba(18,65,112,0.03)]">
    <div class="mx-auto grid max-w-[1200px] grid-cols-1 gap-8 px-4 py-10 md:grid-cols-4 md:px-5">

      <!-- Brand -->
      <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#26667F] text-white">
            <span class="material-symbols-outlined text-lg">
              local_library
            </span>
          </div>

          <span class="text-base font-semibold tracking-tight text-[#124170]">
            Diginesh
          </span>
        </div>

        <p class="text-xs leading-relaxed text-[#6F8B95]">
          Sistem perpustakaan digital untuk memudahkan siswa mencari, meminjam, dan mengelola buku.
        </p>
      </div>

      <!-- Navigasi Cepat -->
      <div class="flex flex-col gap-2">
        <span class="text-sm font-semibold text-[#124170]">
          Navigasi Cepat
        </span>

        <RouterLink
          to="/my-library"
          class="text-xs text-[#6F8B95] hover:text-[#124170]"
        >
          My Library
        </RouterLink>

        <RouterLink
          to="/bookmarks"
          class="text-xs text-[#6F8B95] hover:text-[#124170]"
        >
          Bookmarks
        </RouterLink>

        <RouterLink
          to="/scan"
          class="text-xs text-[#6F8B95] hover:text-[#124170]"
        >
          Scan
        </RouterLink>
      </div>

      <!-- Jam Operasional -->
      <div class="flex flex-col gap-2">
        <span class="text-sm font-semibold text-[#124170]">
          Jam Operasional
        </span>

        <span class="text-xs text-[#0c1f17]">
          Senin – Kamis: 07:30 – 17:30
        </span>

        <span class="text-xs text-[#0c1f17]">
          Jumat: 07:30 – 16:00
        </span>

        <span class="text-xs text-[#6F8B95]">
          Sabtu & Minggu: Tutup
        </span>
      </div>

      <!-- Layanan Bantuan -->
      <div class="flex flex-col gap-2">
        <span class="text-sm font-semibold text-[#124170]">
          Layanan Bantuan Siswa
        </span>

        <span class="text-xs text-[#0c1f17]">
          Lokasi: Perpustakaan Ganesha Stembayo
        </span>

        <span class="text-xs text-[#0c1f17]">
          Email: library@diginesh.id
        </span>

        <span class="text-xs text-[#6F8B95]">
          Phone: +62 812-3456-7890
        </span>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="w-full bg-[#E2F9EC] py-3">
      <div class="mx-auto flex max-w-[1200px] flex-col items-center justify-between gap-2 px-4 sm:flex-row md:px-5">

        <span class="text-[11px] text-[#6F8B95]">
          © 2026 Sistem Perpustakaan Diginesh. Hak cipta dilindungi.
        </span>

        <div class="flex gap-4">
          <a
            href="#"
            class="text-[11px] text-[#6F8B95] hover:text-[#124170]"
          >
            Kebijakan Privasi
          </a>

          <a
            href="#"
            class="text-[11px] text-[#6F8B95] hover:text-[#124170]"
          >
            Aksesibilitas
          </a>
        </div>
      </div>
    </div>
  </footer>
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
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}
</style>
