<script setup>
import { useStyleStore } from "@/stores/style.js";

const styleStore = useStyleStore();
</script>

<template>
  <div :class="{ dark: styleStore.darkMode }">
    <div class="bg-gray-50 dark:bg-slate-800 dark:text-slate-100">
      <h1>coucou</h1>
      <slot />
    </div>
  </div>
</template>
