<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Mail, Lock, LogIn } from 'lucide-vue-next';

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

        <div v-if="status" class="mb-4 p-4 bg-cyan-500/10 border border-cyan-500 rounded-lg text-sm text-cyan-300">
            {{ status }}
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white">
                Welcome Back!
            </h2>
            <p class="text-gray-400 mt-2">Continue your gaming journey</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="email" value="Email" />
                <div class="relative mt-2">
                    <Mail :size="18" class="absolute left-3 top-3 text-cyan-400" />
                    <TextInput
                        id="email"
                        type="email"
                        class="pl-10"
                        v-model="form.email"
                        placeholder="your@email.com"
                        required
                        autofocus
                        autocomplete="username"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" />
                <div class="relative mt-2">
                    <Lock :size="18" class="absolute left-3 top-3 text-cyan-400" />
                    <TextInput
                        id="password"
                        type="password"
                        class="pl-10"
                        v-model="form.password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block">
                <label class="flex items-center gap-2 text-cyan-300 hover:text-cyan-200 transition cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="text-sm">Remember me</span>
                </label>
            </div>

            <div class="flex flex-col gap-3 pt-4">
                <PrimaryButton
                    class="w-full justify-center gap-2"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <LogIn :size="18" />
                    {{ form.processing ? 'Logging in...' : 'Continue' }}
                </PrimaryButton>
            </div>

            <div class="flex flex-col gap-3 text-sm">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-center text-cyan-400 hover:text-cyan-300 transition"
                >
                    Forgot your password?
                </Link>

                <div class="text-center text-gray-400">
                    No account yet?
                    <Link
                        :href="route('register')"
                        class="text-cyan-400 hover:text-cyan-300 font-semibold transition"
                    >
                        Create one
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
