<template>
  <footer class="w-full bg-white shadow-[0_-4px_18px_rgba(18,65,112,0.03)]">
    <div class="mx-auto grid max-w-[1200px] grid-cols-1 gap-8 px-4 py-10 md:grid-cols-4 md:px-5">

      <!-- Brand -->
      <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2">
          <img
            src="/images/image-logo.svg"
            alt="Diginesh"
            class="h-8 w-8 object-contain"
          />

          <span class="text-base font-semibold tracking-tight text-[#20384D]">
            Diginesh
          </span>
        </div>

        <p class="text-xs leading-relaxed text-[#637587]">
          Sistem perpustakaan digital untuk memudahkan siswa mencari, meminjam, dan mengelola buku.
        </p>
      </div>

      <!-- Navigasi Cepat -->
      <div class="flex flex-col gap-2">
        <span class="text-sm font-semibold text-[#20384D]">
          Navigasi Cepat
        </span>

        <RouterLink
          to="/my-library"
          class="text-xs text-[#637587] hover:text-[#20384D]"
        >
          My Library
        </RouterLink>

        <RouterLink
          to="/bookmarks"
          class="text-xs text-[#637587] hover:text-[#20384D]"
        >
          Bookmarks
        </RouterLink>

        <RouterLink
          to="/scan"
          class="text-xs text-[#637587] hover:text-[#20384D]"
        >
          Scan
        </RouterLink>
      </div>

      <!-- Jam Operasional -->
      <div class="flex flex-col gap-2">
        <span class="text-sm font-semibold text-[#20384D]">
          Jam Operasional
        </span>

        <span class="text-xs text-[#293A49]">
          Senin – Kamis: 07:30 – 17:30
        </span>

        <span class="text-xs text-[#293A49]">
          Jumat: 07:30 – 16:00
        </span>

        <span class="text-xs text-[#637587]">
          Sabtu & Minggu: Tutup
        </span>
      </div>

      <!-- Layanan Bantuan -->
      <div class="flex flex-col gap-2">
        <span class="text-sm font-semibold text-[#20384D]">
          Layanan Bantuan Siswa
        </span>

        <span class="text-xs text-[#293A49]">
          Lokasi: Perpustakaan Ganesha Stembayo
        </span>

        <span class="text-xs text-[#293A49]">
          Email: library@diginesh.id
        </span>

        <span class="text-xs text-[#637587]">
          Phone: +62 812-3456-7890
        </span>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="w-full bg-[#EAF0F4] py-3">
      <div class="mx-auto flex max-w-[1200px] flex-col items-center justify-between gap-2 px-4 sm:flex-row md:px-5">

        <span class="text-[11px] text-[#637587]">
          © 2026 Sistem Perpustakaan Diginesh. Hak cipta dilindungi.
        </span>

        <div class="flex gap-4">
          <a
            href="#"
            class="text-[11px] text-[#637587] hover:text-[#20384D]"
          >
            Kebijakan Privasi
          </a>

          <a
            href="#"
            class="text-[11px] text-[#637587] hover:text-[#20384D]"
          >
            Aksesibilitas
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>
