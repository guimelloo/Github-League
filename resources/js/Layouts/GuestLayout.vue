<script setup>
import { Link } from '@inertiajs/vue3';
import { Sword } from 'lucide-vue-next';
</script>

<template>
    <div class="min-h-screen bg-black text-white overflow-hidden flex flex-col items-center justify-center">
        <!-- Gradient Background -->
        <div class="fixed inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-black via-blue-950 to-black"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 w-full max-w-md px-6">
            <!-- Logo -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-block">
                    <div class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">
                        GITHUB LEAGUE
                    </div>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-slate-900/50 backdrop-blur-md border-2 border-cyan-500/30 rounded-lg p-8 hover:border-cyan-500 transition">
                <slot />
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-6 text-sm text-gray-500">
                <p>Join the ultimate coding arena</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
    * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
</style>
