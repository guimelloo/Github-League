<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Trophy, Medal, Award } from 'lucide-vue-next';

const props = defineProps({
    users: Array,
    highestScoringUsers: Array,
});

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
    if (position === 1) return 'text-yellow-300';
    if (position === 2) return 'text-gray-300';
    if (position === 3) return 'text-orange-400';
    return 'text-cyan-400';
};

const getMedalColor = (position) => {
    if (position === 1) return 'text-yellow-400';
    if (position === 2) return 'text-gray-400';
    if (position === 3) return 'text-orange-400';
    return 'text-cyan-400';
};
</script>

<template>
    <Head title="Leaderboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-black overflow-hidden relative">
            <!-- Grid Background -->
            <div class="fixed inset-0 opacity-5 pointer-events-none" style="background-image: linear-gradient(0deg, transparent 24%, rgba(0, 136, 204, 0.1) 25%, rgba(0, 136, 204, 0.1) 26%, transparent 27%, transparent 74%, rgba(0, 136, 204, 0.1) 75%, rgba(0, 136, 204, 0.1) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(0, 136, 204, 0.1) 25%, rgba(0, 136, 204, 0.1) 26%, transparent 27%, transparent 74%, rgba(0, 136, 204, 0.1) 75%, rgba(0, 136, 204, 0.1) 76%, transparent 77%, transparent); background-size: 60px 60px;"></div>

            <div class="relative z-10 max-w-6xl mx-auto px-4 py-12">
                <!-- PAGE TITLE -->
                <div class="mb-16 text-center">
                    <div class="text-7xl font-black text-cyan-400 font-mono mb-4" style="text-shadow: 0 0 50px rgba(0, 136, 204, 0.9); letter-spacing: 0.05em;">
                        ◆═══ LEADERBOARD ═══◆
                    </div>
                    <div class="text-cyan-300 font-mono text-lg tracking-widest">
                        ▶ TOP DEVELOPERS ON THE LEAGUE ◀
                    </div>
                    <div class="mt-4 h-1 w-40 bg-gradient-to-r from-transparent via-cyan-400 to-transparent mx-auto" style="box-shadow: 0 0 20px rgba(0, 136, 204, 0.8);"></div>
                </div>

                <!-- TOP 3 HIGHLIGHTS -->
                <div v-if="highlights.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                    <div v-for="(player, idx) in highlights" :key="player.id" class="relative group">
                        <!-- PODIUM CARD -->
                        <div
                            class="border-4 border-cyan-400 bg-black p-10 text-center relative overflow-hidden transition-all duration-300 hover:scale-105"
                            :style="{
                                boxShadow: `0 0 ${25 + idx * 8}px rgba(0, 136, 204, ${0.7 + idx * 0.2}), inset 0 0 20px rgba(0, 136, 204, 0.1)`
                            }"
                        >
                            <!-- TOP & BOTTOM BORDER EFFECTS -->
                            <div class="absolute top-0 left-0 right-0 h-3 bg-gradient-to-r from-transparent via-cyan-400 to-transparent" style="box-shadow: 0 0 25px #0088CC;"></div>
                            <div class="absolute bottom-0 left-0 right-0 h-3 bg-gradient-to-r from-transparent via-cyan-400 to-transparent" style="box-shadow: 0 0 25px #0088CC;"></div>

                            <!-- MEDAL -->
                            <div :class="`text-6xl font-black mb-6 ${getMedalColor(idx + 1)}`" style="text-shadow: 0 0 25px currentColor;">
                                <Trophy v-if="idx + 1 === 1" :size="64" stroke-width="1.5" />
                                <Medal v-else-if="idx + 1 === 2" :size="64" stroke-width="1.5" />
                                <Award v-else :size="64" stroke-width="1.5" />
                            </div>

                            <!-- POSITION -->
                            <div :class="`text-7xl font-black mb-4 ${getTitleColor(idx + 1)}`" style="text-shadow: 0 0 30px currentColor; letter-spacing: 0.1em;">
                                #{{ idx + 1 }}
                            </div>

                            <!-- AVATAR -->
                            <div class="mb-4 flex justify-center">
                                <a
                                    :href="`https://github.com/${player.github_username}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="hover:scale-110 transition-transform duration-300 block"
                                >
                                    <img
                                        :src="player.avatar_url"
                                        :alt="player.name"
                                        class="w-20 h-20 cursor-pointer"
                                        style="border: 3px solid #0088CC; box-shadow: 0 0 15px rgba(0, 136, 204, 0.6);"
                                    />
                                </a>
                            </div>

                            <!-- NAME -->
                            <div class="text-2xl font-black font-mono mb-1">
                                <a
                                    :href="`https://github.com/${player.github_username}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-cyan-400 hover:text-cyan-200 transition-colors duration-200 cursor-pointer hover:underline"
                                    style="text-shadow: 0 0 15px rgba(0, 136, 204, 0.8);"
                                >
                                    {{ player.name }}
                                </a>
                            </div>

                            <!-- GITHUB -->
                            <div class="text-sm font-mono mb-4">
                                <a
                                    :href="`https://github.com/${player.github_username}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-cyan-300 hover:text-cyan-100 transition-colors duration-200 cursor-pointer"
                                >
                                    @{{ player.github_username }}
                                </a>
                            </div>

                            <!-- SCORE -->
                            <div class="font-mono">
                                <div class="text-xs uppercase text-cyan-300/70 mb-1">SCORE</div>
                                <div class="text-4xl font-black text-cyan-400" style="text-shadow: 0 0 20px rgba(0, 136, 204, 1);">
                                    {{ player.score.toLocaleString('en-US', { minimumIntegerDigits: 5, useGrouping: false }) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RANKINGS TABLE -->
                <div v-if="rankings.length > 0" class="border-4 border-cyan-400" style="box-shadow: 0 0 40px rgba(0, 136, 204, 0.9), inset 0 0 20px rgba(0, 136, 204, 0.1);">
                    <!-- TOP BORDER -->
                    <div class="h-3 bg-gradient-to-r from-transparent via-cyan-400 to-transparent" style="box-shadow: 0 0 25px #0088CC;"></div>

                    <!-- TABLE HEADER -->
                    <div class="bg-black px-6 py-8 border-b-2 border-cyan-400/50">
                        <div class="grid grid-cols-12 gap-4 font-mono font-black text-cyan-400 text-xs uppercase tracking-widest" style="text-shadow: 0 0 10px rgba(0, 136, 204, 0.6);">
                            <div class="col-span-1">RANK</div>
                            <div class="col-span-2">AVATAR</div>
                            <div class="col-span-5">PLAYER</div>
                            <div class="col-span-4 text-right">SCORE</div>
                        </div>
                    </div>

                    <!-- TABLE ROWS -->
                    <div class="bg-black">
                        <div
                            v-for="(player, index) in rankings"
                            :key="player.id"
                            class="px-6 py-5 border-b border-cyan-400/20 transition-all duration-200 group"
                            :class="{
                                'bg-gradient-to-r from-cyan-400/10 via-transparent to-cyan-400/10 hover:from-cyan-400/15 hover:via-cyan-400/5 hover:to-cyan-400/15': player.position <= 3,
                                'hover:bg-cyan-400/5': player.position > 3
                            }"
                        >
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- RANK -->
                                <div class="col-span-1 text-cyan-400 font-mono font-black text-lg group-hover:text-cyan-300 transition-colors duration-200" style="text-shadow: 0 0 10px rgba(0, 136, 204, 0.6);">
                                    #{{ player.position }}
                                </div>

                                <!-- AVATAR -->
                                <div class="col-span-2 flex justify-start">
                                    <a
                                        :href="`https://github.com/${player.github_username}`"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="hover:scale-110 transition-transform duration-300"
                                    >
                                        <img
                                            :src="player.avatar_url"
                                            :alt="player.name"
                                            class="w-12 h-12 cursor-pointer"
                                            style="border: 2px solid #0088CC; box-shadow: 0 0 10px rgba(0, 136, 204, 0.5);"
                                        />
                                    </a>
                                </div>

                                <!-- NAME & USERNAME -->
                                <div class="col-span-5">
                                    <a
                                        :href="`https://github.com/${player.github_username}`"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="block hover:no-underline"
                                    >
                                        <div class="text-cyan-400 font-mono font-bold group-hover:text-cyan-300 transition-colors duration-200 hover:text-cyan-200 cursor-pointer">
                                            {{ player.name }}
                                        </div>
                                        <div class="text-cyan-300/60 font-mono text-sm group-hover:text-cyan-300/80 transition-colors duration-200 hover:text-cyan-200 cursor-pointer">
                                            @{{ player.github_username }}
                                        </div>
                                    </a>
                                </div>

                                <!-- SCORE -->
                                <div class="col-span-4 text-right">
                                    <div class="text-cyan-400 font-mono font-black text-2xl" style="text-shadow: 0 0 15px rgba(0, 136, 204, 0.8);">
                                        {{ player.score.toLocaleString('en-US', { minimumIntegerDigits: 5, useGrouping: false }) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTTOM BORDER -->
                    <div class="h-3 bg-gradient-to-r from-transparent via-cyan-400 to-transparent" style="box-shadow: 0 0 25px #0088CC;"></div>
                </div>

                <!-- BOTTOM TEXT -->
                <div class="mt-16 text-center text-cyan-400 font-mono text-xl tracking-widest animate-pulse" style="text-shadow: 0 0 30px rgba(0, 136, 204, 0.8);">
                    ★ ░▒▓ COMPETE • CLIMB • CONQUER ▓▒░ ★
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
