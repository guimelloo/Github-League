<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Trophy, Award, Crown } from 'lucide-vue-next';

defineProps({
    divisions: {
        type: Array,
        required: true,
    },
});

const getDivisionIcon = (division) => {
    if (!division) return null;
    const iconMap = {
        'bronze': Trophy,
        'silver': Award,
        'gold': Crown,
    };
    return iconMap[division.icon] || Trophy;
};
</script>

<template>
    <Head title="Divisions" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- HEADER -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-100">
                        Divisions
                    </h1>
                    <p class="mt-2 text-slate-400">
                        View all divisions and their top players
                    </p>
                </div>

                <!-- DIVISIONS GRID/CARDS -->
                <div class="space-y-8">
                    <div v-for="division in divisions" :key="division.id" class="bg-slate-900 border border-slate-700 rounded-lg overflow-hidden shadow-lg">
                        <!-- DIVISION HEADER -->
                        <div
                            :style="{ backgroundColor: division.color || '#1e293b' }"
                            class="px-6 py-4 border-b border-slate-700"
                        >
                            <div class="flex items-center gap-3">
                                <component
                                    :is="getDivisionIcon(division)"
                                    :size="32"
                                    class="flex-shrink-0"
                                />
                                <div>
                                    <h2 class="text-2xl font-bold text-white">{{ division.name }}</h2>
                                    <p v-if="division.min_score && division.max_score" class="text-sm text-slate-200">
                                        {{ division.min_score.toLocaleString() }} - {{ division.max_score.toLocaleString() }} points
                                    </p>
                                    <p v-else-if="division.min_score" class="text-sm text-slate-200">
                                        {{ division.min_score.toLocaleString() }}+ points
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- PLAYERS TABLE -->
                        <div class="overflow-x-auto">
                            <table v-if="division.profiles && division.profiles.length > 0" class="w-full">
                                <thead>
                                    <tr class="border-b border-slate-700 bg-slate-800">
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-300">#</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-300">Player</th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-300">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(profile, index) in division.profiles"
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
                                                        {{ profile.user?.name || profile.github_username }}
                                                    </p>
                                                    <p v-if="profile.github_username" class="text-xs text-slate-400">
                                                        @{{ profile.github_username }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-semibold text-cyan-400">
                                                {{ profile.score.toLocaleString() }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- EMPTY STATE -->
                            <div v-else class="px-6 py-12 text-center">
                                <p class="text-slate-400">No players in this division yet</p>
                            </div>
                        </div>
                    </div>
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
