<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { Trophy, Award, Crown, Code2 } from 'lucide-vue-next';
import gsap from 'gsap';

const props = defineProps({
    githubProfile: {
        type: Object,
        default: null,
    },
    githubProfileData: {
        type: Object,
        default: null,
    },
});

const redirectToGithub = () => {
    window.location.href = route('github.redirect');
};

const isGithubConnected = (profile) => {
    return profile && profile.github_username;
};

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
        score: props.githubProfile?.score || 0,
    };
});

onMounted(() => {
    if (isGithubConnected(props.githubProfile)) {
        // Animar score contador
        gsap.to(scoreCounter, {
            value: githubStats.value.score,
            duration: 4,
            ease: 'power3.out',
            onUpdate: () => {
                scoreCounter.value = Math.floor(scoreCounter.value);
            },
        });

        // Pixel falling effect
        setTimeout(() => {
            createPixelEffect();
        }, 300);
    }
});

const createPixelEffect = () => {
    // Subtle animation on load - optional
    // Can be removed or kept minimal
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-950 py-12">
            <div class="max-w-2xl mx-auto px-4">
                <div v-if="isGithubConnected(props.githubProfile)" class="space-y-8">
                    <!-- AVATAR SECTION -->
                    <div class="flex justify-center mb-8">
                        <img
                            :src="props.githubProfile.avatar_url"
                            :alt="props.githubProfile.github_username"
                            class="w-24 h-24 rounded-full border border-slate-700 bg-slate-900"
                        />
                    </div>

                    <!-- PROFILE CARD -->
                    <div class="bg-slate-900 border border-slate-700 rounded-lg p-8 space-y-8">
                        <!-- NAME -->
                        <div>
                            <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">Player</div>
                            <div class="text-3xl font-bold text-slate-100">{{ $page.props.auth.user.name }}</div>
                        </div>

                        <!-- DIVIDER -->
                        <div class="border-t border-slate-700"></div>

                        <!-- DIVISION -->
                        <div v-if="props.githubProfile.division" class="flex items-center gap-4">
                            <div class="flex-1">
                                <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">Divisão</div>
                                <div class="text-lg font-semibold text-slate-100">{{ props.githubProfile.division.name }}</div>
                            </div>
                            <component
                                :is="getDivisionIcon(props.githubProfile.division)"
                                :size="32"
                                :style="{ color: props.githubProfile.division.color }"
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
                        <div v-if="props.githubProfile.top_language" class="flex items-center gap-4">
                            <div class="flex-1">
                                <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">Top Language</div>
                                <div class="text-lg font-semibold text-slate-100">{{ props.githubProfile.top_language }}</div>
                            </div>
                            <Code2
                                :size="32"
                                :style="{ color: getLanguageColor(props.githubProfile.top_language) }"
                                class="flex-shrink-0"
                            />
                        </div>

                        <!-- DIVIDER -->
                        <div class="border-t border-slate-700"></div>

                        <!-- GITHUB USERNAME -->
                        <div>
                            <div class="text-sm uppercase tracking-widest text-slate-500 font-medium mb-2">GitHub</div>
                            <a
                                :href="`https://github.com/${props.githubProfile.github_username}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-lg text-blue-500 hover:text-blue-400 transition font-mono"
                            >
                                @{{ props.githubProfile.github_username }}
                            </a>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="text-center pt-4">
                        <Link :href="route('leaderboard')" class="text-slate-400 hover:text-slate-300 transition text-sm">
                            View Leaderboard →
                        </Link>
                    </div>
                </div>

                <!-- NOT CONNECTED STATE -->
                <div v-else class="text-center space-y-8 py-16">
                    <div class="space-y-4 mb-8">
                        <h2 class="text-4xl font-bold text-slate-100">Not connected</h2>
                        <p class="text-slate-400 text-lg">Connect your GitHub account to get started</p>
                    </div>

                    <button
                        @click="redirectToGithub"
                        class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium"
                    >
                        Connect GitHub
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
