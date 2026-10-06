<template>
    <aside
      class="fixed left-0 top-0 z-40 flex h-screen w-64 flex-col bg-[#124170] text-white shadow-xl"
    >
  
      <!-- BRAND -->
      <div class="flex h-16 items-center gap-3 bg-[#002b51] px-5">
  
        <div
          class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#9bf5c1]"
        >
          <span class="material-symbols-outlined text-[21px] text-[#00311d]">
            local_library
          </span>
        </div>
  
        <div>
          <h1 class="text-[15px] font-extrabold leading-tight">
            Libby Admin
          </h1>
  
          <p class="mt-0.5 text-[10px] text-white/60">
            Library Ops Hub
          </p>
        </div>
  
      </div>
  
      <!-- NAVIGATION -->
      <nav class="flex-1 px-3 py-5">
  
        <!-- SECTION TITLE -->
        <p
          class="mb-2 px-3 text-[9px] font-bold uppercase tracking-[0.15em] text-white/45"
        >
          Circulation Desk
        </p>
  
        <!-- DASHBOARD -->
        <router-link
          to="/admin/dashboard"
          class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-semibold transition"
          active-class="bg-white/15 text-white"
        >
          <span class="material-symbols-outlined text-[19px]">
            dashboard
          </span>
  
          <span>Dashboard</span>
        </router-link>
  
        <!-- BORROW APPROVAL -->
        <router-link
            to="/admin/borrow-approval"
            class="mb-1 flex items-center justify-between rounded-lg px-3 py-2.5 text-[13px] font-semibold text-white/75 transition hover:bg-white/10 hover:text-white"
            active-class="bg-white/15 text-white"
>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[19px]">
                    approval
                    </span>

                    <span>Borrow Approval</span>
                </div>

            <span
                class="flex h-5 min-w-5 items-center justify-center rounded-full bg-[#9bf5c1] px-1.5 text-[9px] font-extrabold text-[#00311d]"
            >
                7
            </span>
        </router-link>
  
        <!-- ADD BOOK -->
        <router-link
          to="/admin/add-book"
          class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-semibold text-white/75 transition hover:bg-white/10 hover:text-white"
        >
          <span class="material-symbols-outlined text-[19px]">
            add_box
          </span>
  
          <span>Add Book</span>
  
          <span
            class="ml-auto rounded bg-white/10 px-1.5 py-0.5 text-[8px] font-bold text-white/60"
          >
            SCAN
          </span>
        </router-link>
  
        <!-- RETURN BOOK -->
        <router-link
          to="/admin/return-book"
          class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-semibold text-white/75 transition hover:bg-white/10 hover:text-white"
        >
          <span class="material-symbols-outlined text-[19px]">
            assignment_return
          </span>
  
          <span>Return Book</span>
  
          <span
            class="ml-auto rounded bg-white/10 px-1.5 py-0.5 text-[8px] font-bold text-white/60"
          >
            SCAN
          </span>
        </router-link>
  
        <!-- BORROWERS -->
        <router-link
          to="/admin/borrowers"
          class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-semibold text-white/75 transition hover:bg-white/10 hover:text-white"
        >
          <span class="material-symbols-outlined text-[19px]">
            group
          </span>
  
          <span>Borrowers</span>
        </router-link>
  
      </nav>
  
      <!-- PROFILE -->
      <div class="border-t border-white/10 p-3">
  
        <div
          class="flex items-center gap-3 rounded-lg px-2 py-2.5"
        >
  
          <div
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#9bf5c1] text-[11px] font-extrabold text-[#00311d]"
          >
            SJ
          </div>
  
          <div class="min-w-0 flex-1">
            <p class="truncate text-[12px] font-bold">
              Sarah Jenkins
            </p>
  
            <p class="truncate text-[10px] text-white/50">
              Head Librarian
            </p>
          </div>
  
          <button
            class="flex h-7 w-7 items-center justify-center rounded-md text-white/60 hover:bg-white/10 hover:text-white"
          >
            <span class="material-symbols-outlined text-[17px]">
              settings
            </span>
          </button>
  
          <button
            class="flex h-7 w-7 items-center justify-center rounded-md text-white/60 hover:bg-white/10 hover:text-white"
          >
            <span class="material-symbols-outlined text-[17px]">
              logout
            </span>
          </button>
  
        </div>
  
      </div>
  
    </aside>
  </template>