<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Continue" />

        <div v-if="status" class="mb-4 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg text-sm text-blue-400">
            {{ status }}
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-100">
                Welcome Back
            </h2>
            <p class="text-slate-400 mt-2">Continue your coding journey</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    placeholder="your@email.com"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block">
                <label class="flex items-center gap-2 text-slate-300 hover:text-slate-200 transition cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="text-sm">Remember me</span>
                </label>
            </div>

            <div class="flex flex-col gap-3 pt-4">
                <PrimaryButton
                    class="w-full justify-center"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Logging in...' : 'Sign In' }}
                </PrimaryButton>
            </div>

            <div class="flex flex-col gap-3 text-sm">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-center text-blue-500 hover:text-blue-400 transition"
                >
                    Forgot your password?
                </Link>

                <div class="text-center text-slate-400">
                    Don't have an account?
                    <Link
                        :href="route('register')"
                        class="text-blue-500 hover:text-blue-400 font-semibold transition"
                    >
                        Sign up
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
