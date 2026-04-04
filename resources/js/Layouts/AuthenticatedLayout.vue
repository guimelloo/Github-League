<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { Menu, X } from 'lucide-vue-next';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-black text-white">
        <!-- Gradient Background -->
        <div class="fixed inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-black via-blue-950 to-black"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        </div>

        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 border-b border-cyan-500/20 bg-slate-900/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link :href="route('leaderboard')" class="flex items-center gap-2">
                            <div class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">
                                GITHUBLEAGUE
                            </div>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden sm:flex items-center gap-8">
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                            class="text-gray-300 hover:text-cyan-300 transition"
                        >
                            Dashboard
                        </NavLink>
                    </div>

                    <!-- User Dropdown -->
                    <div class="hidden sm:flex items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-cyan-500/30 hover:border-cyan-500 transition text-gray-300 hover:text-cyan-300">
                                    {{ $page.props.auth.user.name }}
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')" class="text-gray-300 hover:text-cyan-300">
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button" class="text-gray-300 hover:text-cyan-300">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="sm:hidden text-cyan-400">
                        <Menu v-if="!showingNavigationDropdown" :size="24" />
                        <X v-else :size="24" />
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div v-if="showingNavigationDropdown" class="sm:hidden border-t border-cyan-500/20">
                <div class="px-4 py-3 space-y-2">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('profile.edit')">
                        Profile
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                        Log Out
                    </ResponsiveNavLink>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        <header v-if="$slots.header" class="mt-16 border-b border-cyan-500/20 bg-slate-900/50 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
                    <slot name="header" />
                </h2>
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative z-10 mt-16">
            <slot />
        </main>
    </div>
</template>

<style scoped>
    * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
</style>
