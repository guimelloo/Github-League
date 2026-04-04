<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-blue-600 text-start text-base font-medium text-slate-100 bg-slate-800 focus:outline-none focus:text-slate-100 focus:bg-slate-800 focus:border-blue-600 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-400 hover:text-slate-300 hover:bg-slate-800/50 hover:border-slate-400 focus:outline-none focus:text-slate-300 focus:bg-slate-800/50 focus:border-slate-400 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
