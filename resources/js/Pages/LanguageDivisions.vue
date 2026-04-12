<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Code2, Search } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
    languageDivisions: {
        type: Object,
        required: true,
    },
});

const searchQuery = ref('');

const predefinedColors = {
    'PHP': '#777BB4',
    'JavaScript': '#F7DF1E',
    'TypeScript': '#3178C6',
    'Python': '#3776AB',
    'Java': '#007396',
    'C++': '#00599C',
    'C#': '#239120',
    'Go': '#00ADD8',
    'Rust': '#CE422B',
    'Ruby': '#CC342D',
    'Swift': '#FA7343',
    'Kotlin': '#7F52FF',
    'Vue': '#4FC08D',
    'React': '#61DAFB',
    'HTML': '#E34C26',
    'CSS': '#563D7C',
    'SCSS': '#C6538C',
};

const colorPalette = [
    '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8',
    '#F7DC6F', '#BB8FCE', '#85C1E2', '#F8B88B', '#A8D5BA',
    '#FFB6B9', '#8EC5FC', '#FF9AA2', '#FFB7B2', '#FFDAC1',
    '#FFE5B4', '#FFD700', '#87CEEB', '#DDA0DD', '#F0FFFF',
];

const hashCode = (str) => {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        const char = str.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash;
    }
    return Math.abs(hash);
};

const getLanguageColor = (language) => {
    if (predefinedColors[language]) {
        return predefinedColors[language];
    }

    const hash = hashCode(language);
    const colorIndex = hash % colorPalette.length;
    return colorPalette[colorIndex];
};

const filteredDivisions = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.languageDivisions;
    }

    const query = searchQuery.value.toLowerCase();
    const filtered = {};

    Object.entries(props.languageDivisions).forEach(([language, profiles]) => {
        if (language.toLowerCase().includes(query)) {
            filtered[language] = profiles;
        }
    });

    return filtered;
});
</script>

<template>
    <Head title="Language Divisions" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- HEADER -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-100">
                        Language Divisions
                    </h1>
                    <p class="mt-2 text-slate-400">
                        Top developers by programming language
                    </p>
                </div>

                <!-- SEARCH BAR -->
                <div class="mb-8">
                    <div class="relative">
                        <Search :size="20" class="absolute left-3 top-3 text-slate-500" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search languages..."
                            class="w-full pl-10 pr-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-500/20"
                        />
                    </div>
                </div>

                <!-- LANGUAGES GRID -->
                <div v-if="Object.keys(filteredDivisions).length > 0" class="space-y-8">
                    <div
                        v-for="(profiles, language) in filteredDivisions"
                        :key="language"
                        class="bg-slate-900 border border-slate-700 rounded-lg overflow-hidden shadow-lg"
                    >
                        <!-- LANGUAGE HEADER -->
                        <div
                            :style="{ backgroundColor: getLanguageColor(language) + '20', borderBottomColor: getLanguageColor(language) }"
                            class="px-6 py-4 border-b border-slate-700"
                        >
                            <div class="flex items-center gap-3">
                                <Code2 :size="28" :style="{ color: getLanguageColor(language) }" />
                                <div>
                                    <h2 class="text-2xl font-bold text-white">{{ language }}</h2>
                                    <p class="text-sm text-slate-300">
                                        {{ profiles.length }} developer<span v-if="profiles.length !== 1">s</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- PLAYERS TABLE -->
                        <div class="overflow-x-auto">
                            <table v-if="profiles.length > 0" class="w-full">
                                <thead>
                                    <tr class="border-b border-slate-700 bg-slate-800">
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-300">#</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-300">Player</th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-300">Language Score</th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-300">Total Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(profile, index) in profiles"
                                        :key="profile.id"
                                        class="border-b border-slate-700 hover:bg-slate-800 transition"
                                    >
                                        <td class="px-6 py-4 text-sm text-slate-300 font-semibold">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img
                                                    v-if="profile.avatar_url"
                                                    :src="profile.avatar_url"
                                                    :alt="profile.github_username"
                                                    class="w-8 h-8 rounded-full"
                                                />
                                                <div>
                                                    <p class="text-sm font-medium text-slate-100">
                                                        {{ profile.user_name }}
                                                    </p>
                                                    <p v-if="profile.github_username" class="text-xs text-slate-400">
                                                        @{{ profile.github_username }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-semibold" :style="{ color: getLanguageColor(language) }">
                                                {{ profile.score.toLocaleString() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-semibold text-cyan-400">
                                                {{ profile.total_score.toLocaleString() }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- EMPTY STATE -->
                            <div v-else class="px-6 py-12 text-center">
                                <p class="text-slate-400">No players in this language category yet</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NO LANGUAGES STATE -->
                <div v-else class="text-center py-16">
                    <p v-if="searchQuery" class="text-slate-400">No languages match your search.</p>
                    <p v-else class="text-slate-400">No language data available yet.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
table {
    border-collapse: collapse;
}

tr:hover {
    background-color: rgba(15, 23, 42, 0.5);
}
</style>
