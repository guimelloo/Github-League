<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
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

const isGithubConnected = (profile) => {
    return profile && profile.github_username;
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
    const chars = ['█', '▓', '▒', '░', '▀', '▄'];
    for (let i = 0; i < 25; i++) {
        const pixel = document.createElement('div');
        pixel.textContent = chars[Math.floor(Math.random() * chars.length)];
        pixel.style.position = 'fixed';
        pixel.style.pointerEvents = 'none';
        pixel.style.left = Math.random() * window.innerWidth + 'px';
        pixel.style.top = '-20px';
        pixel.style.color = '#0088CC';
        pixel.style.fontSize = '30px';
        pixel.style.fontFamily = 'monospace';
        pixel.style.fontWeight = 'bold';
        pixel.style.zIndex = '9999';
        pixel.style.textShadow = '0 0 20px #0088CC';
        pixel.style.opacity = '0.7';

        document.body.appendChild(pixel);

        gsap.to(pixel, {
            y: window.innerHeight + 50,
            opacity: 0,
            duration: Math.random() * 2 + 2,
            ease: 'power2.in',
            onComplete: () => pixel.remove(),
        });
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-black flex items-center justify-center overflow-hidden relative">
            <!-- Grid Background -->
            <div class="fixed inset-0 opacity-5 pointer-events-none" style="background-image: linear-gradient(0deg, transparent 24%, rgba(0, 136, 204, 0.1) 25%, rgba(0, 136, 204, 0.1) 26%, transparent 27%, transparent 74%, rgba(0, 136, 204, 0.1) 75%, rgba(0, 136, 204, 0.1) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(0, 136, 204, 0.1) 25%, rgba(0, 136, 204, 0.1) 26%, transparent 27%, transparent 74%, rgba(0, 136, 204, 0.1) 75%, rgba(0, 136, 204, 0.1) 76%, transparent 77%, transparent); background-size: 60px 60px;"></div>

            <div class="relative z-10 max-w-3xl w-full mx-auto px-4">
                <div v-if="isGithubConnected(props.githubProfile)" class="space-y-12">
                    <!-- AVATAR SECTION -->
                    <div class="flex justify-center">
                        <div class="relative">
                            <div class="absolute inset-0 border-4 border-cyan-400" style="box-shadow: 0 0 30px rgba(0, 136, 204, 0.6);"></div>
                            <img
                                :src="props.githubProfile.avatar_url"
                                :alt="props.githubProfile.github_username"
                                class="relative w-32 h-32 bg-black"
                                style="border: 4px solid #0088CC; box-shadow: 0 0 20px rgba(0, 136, 204, 0.5);"
                            />
                        </div>
                    </div>

                    <!-- MAIN ARCADE MENU -->
                    <div class="border-4 border-cyan-400" style="box-shadow: 0 0 30px rgba(0, 136, 204, 0.8), inset 0 0 20px rgba(0, 136, 204, 0.1);">
                        <!-- TOP BORDER EFFECT -->
                        <div class="h-2 bg-cyan-400" style="box-shadow: 0 0 20px #0088CC;"></div>

                        <!-- CONTENT -->
                        <div class="bg-black px-8 py-16 space-y-8">
                            <!-- PLAYER NAME -->
                            <div class="flex items-center space-x-6 group hover:translate-x-2 transition-transform duration-300">
                                <span class="text-cyan-400 text-4xl font-black">▶</span>
                                <div>
                                    <div class="text-xs uppercase tracking-widest text-cyan-300/70 font-mono">PLAYER:</div>
                                    <div class="text-4xl font-black text-cyan-400 font-mono" style="text-shadow: 0 0 20px rgba(0, 136, 204, 0.8);">{{ $page.props.auth.user.name }}</div>
                                </div>
                            </div>

                            <!-- DIVIDER -->
                            <div class="border-b-2 border-cyan-400/30"></div>

                            <!-- SCORE -->
                            <div class="flex items-center space-x-6 group hover:translate-x-2 transition-transform duration-300">
                                <span class="text-cyan-400 text-4xl font-black">▶</span>
                                <div>
                                    <div class="text-xs uppercase tracking-widest text-cyan-300/70 font-mono">SCORE:</div>
                                    <div class="text-5xl font-black text-cyan-400 font-mono" style="text-shadow: 0 0 30px rgba(0, 136, 204, 1); letter-spacing: 0.1em;">{{ scoreCounter.toLocaleString('en-US', { minimumIntegerDigits: 5, useGrouping: false }) }}</div>
                                </div>
                            </div>

                            <!-- DIVIDER -->
                            <div class="border-b-2 border-cyan-400/30"></div>

                            <!-- CONNECTED -->
                            <div class="flex items-center space-x-6 group hover:translate-x-2 transition-transform duration-300">
                                <span class="text-cyan-400 text-4xl font-black">▶</span>
                                <div>
                                    <div class="text-xs uppercase tracking-widest text-cyan-300/70 font-mono">CONNECTED:</div>
                                    <div class="text-3xl font-black text-cyan-400 font-mono" style="text-shadow: 0 0 20px rgba(0, 136, 204, 0.8);">@{{ props.githubProfile.github_username }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM BORDER EFFECT -->
                        <div class="h-2 bg-cyan-400" style="box-shadow: 0 0 20px #0088CC;"></div>
                    </div>

                    <!-- BOTTOM TEXT -->
                    <div class="text-center text-cyan-400 font-mono text-xl tracking-widest animate-pulse" style="text-shadow: 0 0 30px rgba(0, 136, 204, 0.6);">
                        ★ ░▒▓ INSERT COIN ▓▒░ ★
                    </div>
                </div>

                <!-- NOT CONNECTED STATE -->
                <div v-else class="text-center space-y-8">
                    <div class="text-6xl font-black text-cyan-400 font-mono" style="text-shadow: 0 0 40px rgba(0, 136, 204, 1);">GAME OVER</div>
                    <p class="text-cyan-300 text-lg font-mono">PLEASE CONNECT YOUR GITHUB</p>
                    <Link href="/auth/github" class="inline-block px-8 py-4 border-2 border-cyan-400 text-cyan-400 font-mono font-bold hover:bg-cyan-400 hover:text-black transition-all duration-300" style="text-shadow: 0 0 10px rgba(0, 136, 204, 0.5); box-shadow: 0 0 20px rgba(0, 136, 204, 0.3);">
                        ▶ START GAME
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
