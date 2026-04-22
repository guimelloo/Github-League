<template>
    <div
        class="relative group inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 transition-all"
        :class="badge.isUnlocked ? 'cursor-pointer hover:scale-105' : 'opacity-40 cursor-not-allowed'"
        :style="{
            borderColor: badge.color,
            backgroundColor: badge.isUnlocked ? `${badge.color}15` : '#64748B20'
        }"
    >
        <span class="text-2xl" :class="!badge.isUnlocked && 'grayscale'">{{ badge.icon }}</span>
        <div class="flex flex-col">
            <span class="font-bold text-sm leading-none" :style="{ color: badge.color }">
                {{ badge.title }}
            </span>
            <span class="text-xs opacity-75">
                {{ badge.description }}
            </span>
        </div>

        <!-- TOOLTIP -->
        <div v-if="badge.isUnlocked" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-slate-900 text-white text-xs rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-slate-700 z-10">
            <div class="font-semibold">{{ badge.score.toLocaleString() }} points</div>
            <div class="text-slate-300">{{ badge.minScore.toLocaleString() }} required</div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
    badge: {
        type: Object,
        required: true,
    },
});
</script>
