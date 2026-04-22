<template>
    <div class="relative group w-full flex justify-center">
        <!-- GLOW HALO -->
        <div class="absolute inset-0 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"
            :style="{ backgroundColor: badge.color }">
        </div>

        <!-- MAIN BADGE -->
        <div
            class="relative w-32 h-32 flex flex-col items-center justify-center rounded-full border-4 transition-all duration-300 shadow-2xl hover:scale-110"
            :class="getTierClass(badge.tier)"
            :style="getMainBadgeStyle()"
        >
            <!-- INNER RING -->
            <div class="absolute inset-2 border-2 rounded-full opacity-30 group-hover:opacity-60 transition-all"
                :style="{ borderColor: badge.color }">
            </div>

            <!-- ICON -->
            <span class="text-5xl transition-all font-black"
                :class="{ 'animate-spin-slow': badge.isUnlocked, 'grayscale': !badge.isUnlocked }"
                :style="{ color: badge.color }">
                {{ badge.icon }}
            </span>

            <!-- TIER LABEL -->
            <div class="text-xs font-bold uppercase tracking-wider mt-1" :style="{ color: badge.color }">
                {{ badge.tier }}
            </div>

            <!-- TIER NUMBER INDICATOR -->
            <div v-if="badge.isUnlocked" class="absolute -top-4 -right-4 w-10 h-10 rounded-full flex items-center justify-center text-sm font-black text-white"
                :class="getTierBgClass(badge.tier)"
                :style="{ backgroundColor: badge.color }">
                {{ getTierNumber(badge.tier) }}
            </div>
        </div>

        <!-- TOOLTIP -->
        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-4 z-50 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity text-center">
            <div class="px-3 py-2 rounded text-xs font-bold space-y-1"
                :style="getTooltipStyle()">
                <div>{{ badge.tier.toUpperCase() }} OF {{ badge.language }}</div>
                <div class="text-xs opacity-90">{{ badge.score.toLocaleString() }} points</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    badge: {
        type: Object,
        required: true,
    },
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

const getTierBgClass = (tier) => {
    const tiers = {
        'warrior': 'bg-orange-500',
        'master': 'bg-indigo-500',
        'legend': 'bg-pink-500',
        'god': 'bg-yellow-400',
    };
    return tiers[tier] || 'bg-gray-500';
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

const getMainBadgeStyle = () => {
    const color = props.badge.color;
    const glow = '60';
    const inset = '20';
    return {
        borderColor: color,
        backgroundColor: props.badge.isUnlocked ? color + '15' : '#64748B20',
        boxShadow: `0 0 30px ${color}${glow}, inset 0 0 20px ${color}${inset}`
    };
};

const getTooltipStyle = () => {
    const color = props.badge.color;
    return {
        backgroundColor: color + '30',
        border: `3px solid ${color}`,
        boxShadow: `0 0 25px ${color}70`
    };
};

const getTierBadgeStyle = () => {
    const color = props.badge.color;
    return {
        backgroundColor: color,
        boxShadow: `0 0 15px ${color}`
    };
};

const getArrowStyle = () => {
    const color = props.badge.color;
    return {
        borderLeft: '8px solid transparent',
        borderRight: '8px solid transparent',
        borderTop: `8px solid ${color}`
    };
};
</script>

<style scoped>
@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin-slow {
    animation: spin-slow 8s linear infinite;
}

@keyframes pulse-glow {
    0%, 100% {
        opacity: 0.4;
    }
    50% {
        opacity: 1;
    }
}

.animate-pulse {
    animation: pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
