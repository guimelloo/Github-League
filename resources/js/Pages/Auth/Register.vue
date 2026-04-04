<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { User, Mail, Lock, UserPlus } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Join the League" />

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white">
                Create Your Account
            </h2>
            <p class="text-gray-400 mt-2">Start your gaming adventure now</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="name" value="Username" />
                <div class="relative mt-2">
                    <User :size="18" class="absolute left-3 top-3 text-cyan-400" />
                    <TextInput
                        id="name"
                        type="text"
                        class="pl-10"
                        v-model="form.name"
                        placeholder="Your awesome username"
                        required
                        autofocus
                        autocomplete="name"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

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
                        autocomplete="new-password"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <div class="relative mt-2">
                    <Lock :size="18" class="absolute left-3 top-3 text-cyan-400" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="pl-10"
                        v-model="form.password_confirmation"
                        placeholder="••••••••"
                        required
                        autocomplete="new-password"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex flex-col gap-3 pt-4">
                <PrimaryButton
                    class="w-full justify-center gap-2"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <UserPlus :size="18" />
                    {{ form.processing ? 'Creating account...' : 'Create Account' }}
                </PrimaryButton>
            </div>

            <div class="text-center text-sm text-gray-400">
                Already have an account?
                <Link
                    :href="route('login')"
                    class="text-cyan-400 hover:text-cyan-300 font-semibold transition"
                >
                    Login
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
