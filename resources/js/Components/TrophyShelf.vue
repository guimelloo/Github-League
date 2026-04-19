<template>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-200 uppercase tracking-widest">Trophy Shelf</h3>

        <div v-if="languageItems.length > 0" class="space-y-4">
            <div v-for="(language, index) in languageItems" :key="index" class="space-y-2">
                <!-- Language Header -->
                <div class="flex items-center gap-3">
                    <div 
                        class="w-4 h-4 rounded-full" 
                        :style="{ backgroundColor: getLanguageColor(language.name) }"
                    ></div>
                    <span class="font-semibold text-slate-300">{{ language.name }}</span>
                    <span class="text-sm text-slate-500">{{ language.score.toLocaleString() }} pts</span>
                </div>

                <!-- Badges Row -->
                <div class="flex flex-wrap gap-3 pl-7">
                    <AchievementBadge 
                        v-for="badge in language.badges" 
                        :key="badge.tier"
                        :badge="badge"
                    />
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="px-4 py-6 rounded-lg border border-slate-700 bg-slate-900/50">
            <p class="text-slate-400 text-center">
                🏆 No badges unlocked yet
            </p>
            <p class="text-slate-500 text-center text-sm mt-2">
                Reach 100,000 points in a language to unlock your first badge
            </p>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed, onMounted } from 'vue';
import AchievementBadge from './AchievementBadge.vue';
import { getAllBadgesByLanguage, getLanguageColor } from '@/utils/achievements';

const props = defineProps({
    languageScores: {
        type: Object,
        required: true,
    },
});

onMounted(() => {
    if (props.languageScores && Object.keys(props.languageScores).length > 0) {
        const filtered100k = Object.entries(props.languageScores).filter(([_, score]) => score >= 100000);
        console.log('🏆 TrophyShelf Debug:', {
            totalLanguages: Object.keys(props.languageScores).length,
            languages: Object.keys(props.languageScores),
            languages100k: filtered100k.length,
            details: filtered100k.map(([lang, score]) => `${lang}: ${score.toLocaleString()}`)
        });
    }
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
