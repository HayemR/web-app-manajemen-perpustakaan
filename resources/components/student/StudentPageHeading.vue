<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
  eyebrow: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div class="flex flex-col gap-1.5">
      <p
        v-if="eyebrow"
        class="text-xs font-semibold uppercase tracking-[0.12em] text-[#315D78]"
      >
        {{ eyebrow }}
      </p>
      <h1 class="text-2xl font-semibold tracking-tight text-[#20384D] md:text-[30px]">
        {{ title }}
      </h1>
      <p class="max-w-2xl text-sm leading-6 text-[#637587]">
        {{ description }}
      </p>
    </div>

    <div v-if="$slots.actions" class="shrink-0">
      <slot name="actions" />
    </div>
  </section>
</template>
