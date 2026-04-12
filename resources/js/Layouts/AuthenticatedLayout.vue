<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { Menu, X, Search } from 'lucide-vue-next';

const showingNavigationDropdown = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const showSearchResults = ref(false);

const performSearch = async (query) => {
    if (!query.trim()) {
        searchResults.value = [];
        showSearchResults.value = false;
        return;
    }

    try {
        const response = await fetch(route('users.search', { query }));
        searchResults.value = await response.json();
        showSearchResults.value = true;
    } catch (error) {
        console.error('Search error:', error);
    }
};

const handleSearch = async (e) => {
    const query = e.target.value;
    searchQuery.value = query;
    if (query.length > 1) {
        await performSearch(query);
    }
};

const selectUser = (user) => {
    window.location.href = route('user.profile', { githubUsername: user.github_username });
    searchQuery.value = '';
    searchResults.value = [];
    showSearchResults.value = false;
};

const closeSearch = () => {
    showSearchResults.value = false;
};
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 border-b border-slate-700 bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link :href="route('leaderboard')" class="flex items-center gap-2">
                            <div class="text-xl font-bold text-slate-100">
                                GitHub League
                            </div>
                        </Link>
                    </div>

                    <!-- Desktop Navigation + Search -->
                    <div class="hidden sm:flex items-center gap-4 flex-1 mx-8">
                        <div class="flex items-center gap-8 flex-1">
                            <NavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                            >
                                Dashboard
                            </NavLink>
                            <NavLink
                                :href="route('divisions')"
                                :active="route().current('divisions')"
                            >
                                Divisions
                            </NavLink>
                            <NavLink
                                :href="route('language-divisions')"
                                :active="route().current('language-divisions')"
                            >
                                Languages
                            </NavLink>
                            <NavLink
                                :href="route('leaderboard')"
                                :active="route().current('leaderboard')"
                            >
                                Leaderboard
                            </NavLink>
                        </div>

                        <!-- Search Box -->
                        <div class="relative w-48">
                            <div class="relative">
                                <Search :size="18" class="absolute left-3 top-2.5 text-slate-500" />
                                <input
                                    v-model="searchQuery"
                                    @input="handleSearch"
                                    @blur="setTimeout(closeSearch, 200)"
                                    type="text"
                                    placeholder="Search users..."
                                    class="w-full pl-9 pr-3 py-2 bg-slate-800 border border-slate-700 rounded text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500"
                                />
                            </div>

                            <!-- Search Results Dropdown -->
                            <div v-if="showSearchResults && searchResults.length > 0" class="absolute top-full left-0 right-0 mt-2 bg-slate-800 border border-slate-700 rounded shadow-lg max-h-64 overflow-y-auto z-50">
                                <div
                                    v-for="user in searchResults"
                                    :key="user.id"
                                    @click="selectUser(user)"
                                    class="px-4 py-2 hover:bg-slate-700 cursor-pointer flex items-center gap-2 border-b border-slate-700 last:border-b-0"
                                >
                                    <img
                                        :src="user.avatar_url"
                                        :alt="user.github_username"
                                        class="w-6 h-6 rounded-full"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-slate-100 font-medium truncate">{{ user.user_name }}</p>
                                        <p class="text-xs text-slate-400 truncate">@{{ user.github_username }}</p>
                                    </div>
                                    <span class="text-xs text-slate-300">{{ user.score }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="hidden sm:flex items-center ml-auto">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="inline-flex items-center gap-2 px-3 py-2 rounded-md border border-slate-600 hover:border-slate-500 transition text-slate-300 hover:text-slate-100">
                                    {{ $page.props.auth.user.name }}
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="sm:hidden text-slate-400 hover:text-slate-100">
                        <Menu v-if="!showingNavigationDropdown" :size="24" />
                        <X v-else :size="24" />
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div v-if="showingNavigationDropdown" class="sm:hidden border-t border-slate-700">
                <div class="px-4 py-3 space-y-3">
                    <!-- Mobile Search -->
                    <div class="relative mb-3">
                        <div class="relative">
                            <Search :size="18" class="absolute left-3 top-2.5 text-slate-500" />
                            <input
                                v-model="searchQuery"
                                @input="handleSearch"
                                @blur="setTimeout(closeSearch, 200)"
                                type="text"
                                placeholder="Search users..."
                                class="w-full pl-9 pr-3 py-2 bg-slate-800 border border-slate-700 rounded text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500"
                            />
                        </div>

                        <!-- Mobile Search Results -->
                        <div v-if="showSearchResults && searchResults.length > 0" class="absolute top-full left-0 right-0 mt-2 bg-slate-800 border border-slate-700 rounded shadow-lg max-h-48 overflow-y-auto z-50">
                            <div
                                v-for="user in searchResults"
                                :key="user.id"
                                @click="selectUser(user)"
                                class="px-3 py-2 hover:bg-slate-700 cursor-pointer flex items-center gap-2 border-b border-slate-700 last:border-b-0"
                            >
                                <img
                                    :src="user.avatar_url"
                                    :alt="user.github_username"
                                    class="w-5 h-5 rounded-full"
                                />
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-slate-100 font-medium truncate">{{ user.user_name }}</p>
                                    <p class="text-xs text-slate-400 truncate">@{{ user.github_username }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('divisions')" :active="route().current('divisions')">
                        Divisions
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('language-divisions')" :active="route().current('language-divisions')">
                        Languages
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('leaderboard')" :active="route().current('leaderboard')">
                        Leaderboard
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
        <header v-if="$slots.header" class="mt-16 border-b border-slate-700 bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-slate-100">
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
