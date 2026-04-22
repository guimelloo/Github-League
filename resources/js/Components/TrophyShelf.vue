<template>
    <div class="space-y-8">
        <!-- TROPHY SHELF HEADER -->
        <div class="text-center">
            <h3 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-400 uppercase tracking-wider drop-shadow-lg">
                Achievement Gallery
            </h3>
            <div class="h-1 w-20 mx-auto mt-2 bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-400 rounded-full blur-sm"></div>
        </div>

        <!-- BADGES GRID -->
        <div v-if="languageItems.length > 0" class="space-y-6">
            <div v-for="language in languageItems" :key="language.name" class="space-y-3">
                <!-- LANGUAGE HEADER -->
                <div class="flex items-center gap-3 px-4">
                    <div 
                        class="w-3 h-3 rounded-full"
                        :style="{ backgroundColor: getLanguageColor(language.name) }"
                    ></div>
                    <span class="text-sm font-bold text-slate-300 uppercase tracking-widest">{{ language.name }}</span>
                    <span class="text-xs text-slate-500">{{ language.score.toLocaleString() }} pts</span>
                </div>

                <!-- BADGES ROW (Grid) -->
                <div class="flex flex-wrap gap-6 pl-4 justify-start">
                    <GamifiedBadge 
                        v-for="badge in language.badges" 
                        :key="badge.tier"
                        :badge="badge"
                    />
                </div>
            </div>
        </div>

        <!-- EMPTY STATE -->
        <div v-else class="py-12 px-6 rounded-lg border-2 border-dashed border-slate-700 bg-gradient-to-br from-slate-900/50 to-slate-800/50 text-center space-y-4">
            <p class="text-sm font-semibold text-slate-400">
                No achievements yet
            </p>
            <p class="text-xs text-slate-500">
                Reach 100,000 points to unlock your first badge
            </p>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';
import GamifiedBadge from './GamifiedBadge.vue';
import { getAllBadgesByLanguage, getLanguageColor } from '@/utils/achievements';

const props = defineProps({
    languageScores: {
        type: Object,
        required: true,
    },
});

const languageItems = computed(() => {
    if (!props.languageScores || Object.keys(props.languageScores).length === 0) {
        return [];
    }
    
    const filtered = Object.entries(props.languageScores)
        .filter(([_, score]) => score >= 100000);
    
    return filtered
        .map(([language, score]) => ({
            name: language,
            score: score,
            badges: getAllBadgesByLanguage(language, score),
        }))
        .sort((a, b) => b.score - a.score);
});
</script>

<style scoped>
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-5px);
    }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
