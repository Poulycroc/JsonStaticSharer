<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Projects"
        main
      />
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { mdiChartTimelineVariant } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";

defineProps({
  projects: {
    type: Array,
    required: true,
    default: () => []
  }
});
</script>
