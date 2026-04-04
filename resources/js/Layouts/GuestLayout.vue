<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100 overflow-hidden flex flex-col items-center justify-center">
        <!-- Content -->
        <div class="w-full max-w-md px-6">
            <!-- Logo -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-block">
                    <div class="text-3xl font-bold text-slate-100">
                        GitHub League
                    </div>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-slate-900 border border-slate-700 rounded-lg p-8 hover:border-slate-600 transition">
                <slot />
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-6 text-sm text-slate-500">
                <p>The ultimate coding arena</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
    * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
</style>
