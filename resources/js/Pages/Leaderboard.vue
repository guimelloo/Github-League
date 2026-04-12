<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { Trophy, Medal, Award, X } from 'lucide-vue-next';

const props = defineProps({
    users: Array,
    highestScoringUsers: Array,
});

const page = usePage();
const showPositionModal = ref(false);
const currentUserPosition = ref(null);

// Processar dados
const highlights = computed(() => {
    return (props.highestScoringUsers || []).map((user) => ({
        id: user.id,
        name: user.name,
        score: user.github_profile?.score || 0,
        avatar_url: user.github_profile?.avatar_url,
        github_username: user.github_profile?.github_username,
    }));
});

const rankings = computed(() => {
    return (props.users || []).map((user, index) => ({
        id: user.id,
        name: user.name,
        score: user.github_profile?.score || 0,
        avatar_url: user.github_profile?.avatar_url,
        github_username: user.github_profile?.github_username,
        position: index + 1,
    }));
});

const getTitleColor = (position) => {
    if (position === 1) return 'text-yellow-500';
    if (position === 2) return 'text-slate-400';
    if (position === 3) return 'text-orange-500';
    return 'text-slate-100';
};

const getMedalColor = (position) => {
    if (position === 1) return 'text-yellow-500';
    if (position === 2) return 'text-slate-400';
    if (position === 3) return 'text-orange-500';
    return 'text-blue-500';
};

// Detectar quando o usuário atual está no top 3
watch(() => highlights.value, (newHighlights) => {
    const currentUserId = page.props.auth.user?.id;
    const userPosition = newHighlights.findIndex(u => u.id === currentUserId);

    if (userPosition !== -1) {
        currentUserPosition.value = userPosition + 1;
        showPositionModal.value = true;
    }
}, { immediate: true });
</script>

<template>
    <Head title="Leaderboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-950 py-12">
            <div class="max-w-6xl mx-auto px-4">
                <!-- PAGE TITLE -->
                <div class="mb-12">
                    <h1 class="text-4xl font-bold text-slate-100 mb-2">Leaderboard</h1>
                    <p class="text-slate-400">Top developers by score</p>
                </div>

                <!-- TOP 3 HIGHLIGHTS -->
                <div v-if="highlights.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div
                        v-for="(player, idx) in highlights"
                        :key="player.id"
                        class="bg-slate-900 border border-slate-700 rounded-lg p-6 text-center hover:border-slate-600 transition"
                    >
                        <!-- MEDAL -->
                        <div :class="`mx-auto w-12 h-12 rounded-full flex items-center justify-center bg-slate-800 mb-4 ${getMedalColor(idx + 1)}`">
                            <Trophy v-if="idx + 1 === 1" :size="24" />
                            <Medal v-else-if="idx + 1 === 2" :size="24" />
                            <Award v-else :size="24" />
                        </div>

                        <!-- POSITION -->
                        <div :class="`text-3xl font-bold mb-4 ${getTitleColor(idx + 1)}`">
                            #{{ idx + 1 }}
                        </div>

                        <!-- AVATAR -->
                        <div class="mb-4 flex justify-center">
                            <a
                                :href="`https://github.com/${player.github_username}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="hover:opacity-80 transition"
                            >
                                <img
                                    :src="player.avatar_url"
                                    :alt="player.name"
                                    class="w-16 h-16 rounded-full border border-slate-700"
                                />
                            </a>
                        </div>

                        <!-- NAME -->
                        <div class="mb-1">
                            <a
                                :href="`https://github.com/${player.github_username}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-slate-100 font-bold hover:text-blue-400 transition"
                            >
                                {{ player.name }}
                            </a>
                        </div>

                        <!-- GITHUB -->
                        <div class="text-sm text-slate-400 mb-4">
                            <a
                                :href="`https://github.com/${player.github_username}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="hover:text-blue-400 transition"
                            >
                                @{{ player.github_username }}
                            </a>
                        </div>

                        <!-- SCORE -->
                        <div class="border-t border-slate-700 pt-4">
                            <div class="text-xs uppercase text-slate-500 font-medium mb-2">Score</div>
                            <div class="text-3xl font-bold text-blue-500 font-mono">
                                {{ player.score.toLocaleString('en-US', { minimumIntegerDigits: 5, useGrouping: false }) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RANKINGS TABLE -->
                <div v-if="rankings.length > 0" class="bg-slate-900 border border-slate-700 rounded-lg overflow-hidden">
                    <!-- TABLE HEADER -->
                    <div class="grid grid-cols-12 gap-4 bg-slate-800 px-6 py-4 font-mono font-bold text-slate-400 text-xs uppercase tracking-widest border-b border-slate-700">
                        <div class="col-span-1">Rank</div>
                        <div class="col-span-2">Avatar</div>
                        <div class="col-span-5">Player</div>
                        <div class="col-span-4 text-right">Score</div>
                    </div>

                    <!-- TABLE ROWS -->
                    <div>
                        <div
                            v-for="player in rankings"
                            :key="player.id"
                            class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-800 last:border-b-0 items-center hover:bg-slate-800/50 transition"
                        >
                            <!-- RANK -->
                            <div class="col-span-1 font-bold text-slate-200">
                                #{{ player.position }}
                            </div>

                            <!-- AVATAR -->
                            <div class="col-span-2 flex justify-start">
                                <a
                                    :href="`https://github.com/${player.github_username}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="hover:opacity-80 transition"
                                >
                                    <img
                                        :src="player.avatar_url"
                                        :alt="player.name"
                                        class="w-10 h-10 rounded-full border border-slate-600"
                                    />
                                </a>
                            </div>

                            <!-- NAME & USERNAME -->
                            <div class="col-span-5">
                                <a
                                    :href="`https://github.com/${player.github_username}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="block hover:no-underline group"
                                >
                                    <div class="text-slate-100 font-bold group-hover:text-blue-400 transition">
                                        {{ player.name }}
                                    </div>
                                    <div class="text-slate-500 font-mono text-sm group-hover:text-slate-400 transition">
                                        @{{ player.github_username }}
                                    </div>
                                </a>
                            </div>

                            <!-- SCORE -->
                            <div class="col-span-4 text-right">
                                <div class="text-xl font-bold text-blue-500 font-mono">
                                    {{ player.score.toLocaleString('en-US', { minimumIntegerDigits: 5, useGrouping: false }) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- POSITION MODAL -->
                <Transition name="fade">
                    <div v-if="showPositionModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <div class="bg-slate-900 border border-blue-500/50 rounded-lg p-8 max-w-md mx-4 relative">
                            <!-- CLOSE BUTTON -->
                            <button
                                @click="showPositionModal = false"
                                class="absolute top-4 right-4 text-slate-400 hover:text-slate-200 transition"
                            >
                                <X :size="24" />
                            </button>

                            <!-- CONTENT -->
                            <div class="text-center">
                                <div class="mb-4 flex justify-center">
                                    <Trophy v-if="currentUserPosition === 1" :size="48" class="text-yellow-500" />
                                    <Medal v-else-if="currentUserPosition === 2" :size="48" class="text-slate-400" />
                                    <Award v-else :size="48" class="text-orange-500" />
                                </div>
                                <h2 class="text-2xl font-bold text-slate-100 mb-2">
                                    You're in the Top 3! 🎉
                                </h2>
                                <p class="text-slate-300 mb-6">
                                    You're currently in <span class="font-bold text-blue-500">#{{ currentUserPosition }}</span> position in the leaderboard!
                                </p>
                                <div class="bg-gradient-to-r from-blue-500/20 to-cyan-500/20 border border-blue-500/50 rounded-lg p-4 mb-6">
                                    <p class="text-sm text-slate-300">Keep improving your score to climb the rankings!</p>
                                </div>
                                <button
                                    @click="showPositionModal = false"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition"
                                >
                                    Got it!
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
