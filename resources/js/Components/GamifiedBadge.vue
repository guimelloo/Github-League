<template>
    <div class="relative group">
        <!-- MAIN BADGE -->
        <div
            class="flex items-center justify-center transition-all duration-300"
            :class="badge.isUnlocked ? 'cursor-pointer hover:scale-125' : 'opacity-40 cursor-not-allowed hover:opacity-50'"
            @mouseenter="animatedState.isHovered = true"
            @mouseleave="animatedState.isHovered = false"
        >
            <!-- GLOW EFFECT (Unlocked Only) -->
            <div v-if="badge.isUnlocked" class="absolute inset-0 rounded-full blur-lg opacity-0 group-hover:opacity-75 transition-opacity duration-300"
                :style="{ backgroundColor: badge.color }">
            </div>

            <!-- BADGE CONTAINER -->
            <div
                class="relative w-20 h-20 flex flex-col items-center justify-center rounded-full border-4 transition-all duration-300"
                :class="[
                    badge.isUnlocked 
                        ? 'shadow-lg group-hover:shadow-2xl' 
                        : 'shadow-sm',
                    getTierClass(badge.tier)
                ]"
                :style="{
                    borderColor: badge.color,
                    backgroundColor: badge.isUnlocked ? `${badge.color}20` : '#64748B30',
                    boxShadow: badge.isUnlocked && animatedState.isHovered
                        ? `0 0 20px ${badge.color}80, inset 0 0 20px ${badge.color}40`
                        : `0 0 10px ${badge.color}40`
                }"
            >
                <!-- ICON -->
                <span class="text-3xl transition-all duration-300 font-black"
                    :class="[
                        badge.isUnlocked ? 'animate-pulse-slow' : 'grayscale',
                        animatedState.isHovered && badge.isUnlocked ? 'scale-120' : 'scale-100'
                    ]"
                    :style="{ color: badge.color }"
                >
                    {{ badge.icon }}
                </span>

                <!-- TIER LABEL (Small) -->
                <div class="text-xs font-bold mt-0.5" :style="{ color: badge.color }">
                    {{ getTierLabel(badge.tier) }}
                </div>

                <!-- TIER INDICATOR (Mini badge in corner) -->
                <div v-if="badge.isUnlocked" class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                    :class="getTierIndicatorClass(badge.tier)"
                    :style="{ backgroundColor: badge.color }"
                >
                    {{ getTierNumber(badge.tier) }}
                </div>
            </div>
        </div>

        <!-- TOOLTIP ON HOVER (Gamified Style) -->
        <div v-if="badge.isUnlocked" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-3 z-50 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200">
            <div class="relative px-3 py-2 rounded-lg backdrop-blur-md"
                :style="{
                    backgroundColor: `${badge.color}20`,
                    border: `2px solid ${badge.color}`,
                    boxShadow: `0 0 15px ${badge.color}60`
                }">
                <!-- Arrow -->
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0"
                    :style="{
                        borderLeft: '6px solid transparent',
                        borderRight: '6px solid transparent',
                        borderTop: `6px solid ${badge.color}`
                    }">
                </div>
                <div class="text-xs font-bold text-center whitespace-nowrap" :style="{ color: badge.color }">
                    {{ badge.score.toLocaleString() }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, reactive, onMounted } from 'vue';

const props = defineProps({
    badge: {
        type: Object,
        required: true,
    },
});

const animatedState = reactive({
    isHovered: false,
});

const getTierClass = (tier) => {
    const tiers = {
        'warrior': 'border-orange-400',
        'master': 'border-indigo-400',
        'legend': 'border-pink-400',
        'god': 'border-yellow-300',
    };
    return tiers[tier] || 'border-gray-400';
};

const getTierNumber = (tier) => {
    const tiers = {
        'warrior': '1',
        'master': '2',
        'legend': '3',
        'god': '4',
    };
    return tiers[tier] || '?';
};

const getTierIndicatorClass = (tier) => {
    const classes = {
        'warrior': 'text-orange-100',
        'master': 'text-indigo-100',
        'legend': 'text-pink-100',
        'god': 'text-yellow-100',
    };
    return classes[tier] || 'text-gray-100';
};

const getTierLabel = (tier) => {
    const labels = {
        'warrior': 'W',
        'master': 'M',
        'legend': 'L',
        'god': 'G',
    };
    return labels[tier] || '?';
};

onMounted(() => {
    if (props.badge.isUnlocked) {
        console.log(`✨ Badge unlocked: ${props.badge.tier} ${props.badge.icon}`);
    }
});
</script>

<style scoped>
@keyframes pulse-slow {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.animate-pulse-slow {
    animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 10px currentColor;
    }
    50% {
        box-shadow: 0 0 20px currentColor, inset 0 0 10px currentColor;
    }
}

.animate-glow {
    animation: glow 2s ease-in-out infinite;
}
</style>
