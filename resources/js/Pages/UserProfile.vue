<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { Trophy, Award, Crown, Code2 } from 'lucide-vue-next';
import AchievementBadge from '@/Components/AchievementBadge.vue';
import TrophyShelf from '@/Components/TrophyShelf.vue';
import { getBadgeByScore } from '@/utils/achievements';
import gsap from 'gsap';

const props = defineProps({
    userProfile: {
        type: Object,
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
    if (!language) return '#8B5CF6';
    if (predefinedColors[language]) {
        return predefinedColors[language];
    }
    const hash = hashCode(language);
    const colorIndex = hash % colorPalette.length;
    return colorPalette[colorIndex];
};

const scoreCounter = ref(0);

const githubStats = computed(() => {
    return {
        score: props.userProfile?.score || 0,
    };
});

const topLanguageBadge = computed(() => {
    if (!props.userProfile?.top_language || !props.userProfile?.language_scores) {
        return null;
    }
    const score = props.userProfile.language_scores[props.userProfile.top_language] || 0;
    return getBadgeByScore(props.userProfile.top_language, score);
});

onMounted(() => {
    // Animar score contador
    gsap.to(scoreCounter, {
        value: githubStats.value.score,
        duration: 4,
        ease: 'power3.out',
        onUpdate: () => {
            scoreCounter.value = Math.floor(scoreCounter.value);
        },
    });
});
</script>

<template>
    <Head :title="`${userProfile.user_name}'s Profile`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-950 py-12">
            <div class="max-w-2xl mx-auto px-4">
                <div class="space-y-8">
                    <!-- AVATAR SECTION -->
                    <div class="flex justify-center mb-8">
                        <img
                            :src="userProfile.avatar_url"
                            :alt="userProfile.github_username"
                            class="w-24 h-24 rounded-full border border-slate-700 bg-slate-900"
                        />
                    </div>

                    <!-- PROFILE CARD -->
                    <div class="bg-slate-900 border border-slate-700 rounded-lg p-8 space-y-8">
                        <!-- NAME -->
                        <div>
                            <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">Player</div>
                            <div class="text-3xl font-bold text-slate-100">{{ userProfile.user_name }}</div>
                        </div>

                        <!-- DIVIDER -->
                        <div class="border-t border-slate-700"></div>

                        <!-- DIVISION -->
                        <div v-if="userProfile.division" class="flex items-center gap-4">
                            <div class="flex-1">
                                <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">Divisão</div>
                                <div class="text-lg font-semibold text-slate-100">{{ userProfile.division.name }}</div>
                            </div>
                            <component
                                :is="getDivisionIcon(userProfile.division)"
                                :size="32"
                                :style="{ color: userProfile.division.color }"
                                class="flex-shrink-0"
                            />
                        </div>

                        <!-- DIVIDER -->
                        <div class="border-t border-slate-700"></div>

                        <!-- SCORE -->
                        <div>
                            <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">Score</div>
                            <div class="text-5xl font-bold text-blue-500 font-mono">
                                {{ scoreCounter.toLocaleString('en-US', { minimumIntegerDigits: 5, useGrouping: false }) }}
                            </div>
                        </div>

                        <!-- DIVIDER -->
                        <div class="border-t border-slate-700"></div>

                        <!-- TOP LANGUAGE -->
                        <div v-if="userProfile.top_language" class="flex items-center gap-4">
                            <div class="flex-1">
                                <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">Top Language</div>
                                <div class="text-lg font-semibold text-slate-100">{{ userProfile.top_language }}</div>
                            </div>
                            <Code2
                                :size="32"
                                :style="{ color: getLanguageColor(userProfile.top_language) }"
                                class="flex-shrink-0"
                            />
                        </div>

                        <!-- ACHIEVEMENT BADGE -->
                        <div class="pt-2">
                            <div v-if="topLanguageBadge" class="min-h-24 flex items-center">
                                <AchievementBadge :badge="topLanguageBadge" />
                            </div>
                            <div v-else class="bg-slate-800 border border-slate-700 rounded-lg p-4 text-center text-sm text-slate-400">
                                <p class="mb-1">🎯 No badges unlocked yet</p>
                                <p class="text-xs">Reach 100,000 points in <span class="text-slate-300 font-semibold">{{ userProfile.top_language }}</span> to unlock your first badge</p>
                            </div>
                        </div>

                        <!-- DIVIDER -->
                        <div class="border-t border-slate-700"></div>

                        <!-- TROPHY SHELF -->
                        <div v-if="userProfile.language_scores" class="pt-4">
                            <TrophyShelf :language-scores="userProfile.language_scores" />
                        </div>

                        <!-- DIVIDER -->
                        <div class="border-t border-slate-700"></div>

                        <!-- GITHUB USERNAME -->
                        <div>
                            <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">GitHub</div>
                            <a
                                :href="`https://github.com/${userProfile.github_username}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-lg text-blue-500 hover:text-blue-400 transition font-mono"
                            >
                                @{{ userProfile.github_username }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
