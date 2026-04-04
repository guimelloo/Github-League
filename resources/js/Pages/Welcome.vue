<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Gamepad2, Sword, Star, Scroll, Zap, BookOpen, Rocket, Palette, Flame, Play } from 'lucide-vue-next';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="GitHub League" />
    <div class="min-h-screen bg-black text-white overflow-hidden flex items-center justify-center">
        <!-- Gradient Background -->
        <div class="fixed inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-black via-blue-950 to-black"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 w-full max-w-2xl px-6">
            <div class="text-center">
                <!-- Logo/Title -->
                <div class="mb-8">
                    <h1 class="text-7xl font-black bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500 mb-2">
                        GITHUB
                    </h1>
                    <h1 class="text-7xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-cyan-400 mb-6">
                        LEAGUE
                    </h1>
                </div>

                <!-- Tagline -->
                <div class="mb-12">
                    <p class="text-xl text-cyan-300 font-light mb-4">
                        Master Your Skills. Play. Compete. Win.
                    </p>
                    <p class="text-base text-gray-400 max-w-md mx-auto leading-relaxed">
                        Join the ultimate coding arena. Build real projects, compete with developers worldwide, and unlock your true potential.
                    </p>
                </div>

                <!-- Divider -->
                <div class="mb-12 flex items-center gap-4">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent to-cyan-500/50"></div>
                    <Sword :size="24" class="text-cyan-500" />
                    <div class="flex-1 h-px bg-gradient-to-l from-transparent to-cyan-500/50"></div>
                </div>

                <!-- Call to Action -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <!-- New Game Button -->
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg font-bold text-lg hover:from-cyan-400 hover:to-blue-500 transition transform hover:scale-105 shadow-lg shadow-cyan-500/30 text-black flex items-center gap-2 justify-center"
                    >
                        <Gamepad2 :size="20" />
                        NEW GAME
                    </Link>

                    <!-- Continue Button -->
                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="px-8 py-4 border-2 border-cyan-500 rounded-lg font-bold text-lg text-cyan-400 hover:bg-cyan-500/10 transition transform hover:scale-105 flex items-center gap-2 justify-center"
                    >
                        <Play :size="20" />
                        CONTINUE
                    </Link>
                </div>

                <!-- Footer Info -->
                <div class="mt-16 text-sm text-gray-600">
                    <p>Join thousands of developers competing in the ultimate coding challenge</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
</style>
