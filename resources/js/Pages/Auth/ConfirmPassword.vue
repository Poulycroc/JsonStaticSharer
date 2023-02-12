<script setup>
import { useForm } from "@inertiajs/vue3";
const form = useForm({ password: "" });
const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <h1>forgot password</h1>
</template>
