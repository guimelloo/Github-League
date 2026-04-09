<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { Trophy, Award, Crown } from 'lucide-vue-next';
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
