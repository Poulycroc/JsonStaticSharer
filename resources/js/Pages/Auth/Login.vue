<script setup>
import { reactive } from "vue";
// import { useRouter } from "vue-router";
import { mdiAccount, mdiAsterisk } from "@mdi/js";
import { useForm } from "@inertiajs/vue3";
import SectionFullScreen from "@/components/SectionFullScreen.vue";
import CardBox from "@/components/CardBox.vue";
import FormCheckRadio from "@/components/FormCheckRadio.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import Btn from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import LayoutGuest from "@/layouts/LayoutGuest.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <LayoutGuest>
        <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
            <CardBox :class="cardClass" is-form @submit.prevent="submit">
                <FormField label="Login" help="Please enter your login">
                    <FormControl
                        v-model="form.email"
                        :icon="mdiAccount"
                        :error-message="form.errors.email"
                        name="login"
                        autocomplete="username"
                    />
                </FormField>

                <FormField label="Password" help="Please enter your password">
                    <FormControl
                        v-model="form.password"
                        :icon="mdiAsterisk"
                        :error-message="form.errors.password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                    />
                </FormField>

                <FormCheckRadio
                    v-model="form.remember"
                    name="remember"
                    label="Remember"
                    :input-value="true"
                />

                <template #footer>
                    <BaseButtons>
                        <Btn type="submit" color="info" label="Login" />
                        <Btn
                            to="/dashboard"
                            color="info"
                            outline
                            label="Back"
                        />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionFullScreen>
    </LayoutGuest>
</template>
