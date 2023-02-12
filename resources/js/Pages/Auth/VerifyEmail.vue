<script setup>
import { computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);
</script>

<template>
    <h1>verify email</h1>
</template>
