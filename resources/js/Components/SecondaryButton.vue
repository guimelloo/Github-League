<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-lg border-2 border-cyan-500/50 bg-slate-800/50 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-cyan-300 shadow-sm transition duration-150 ease-in-out hover:border-cyan-500 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-0 disabled:opacity-25"
    >
        <slot />
    </button>
</template>
