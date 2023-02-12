<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        :title="project.name"
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
  project: {
    type: Object,
    required: true,
    default: () => {}
  }
});
</script>
