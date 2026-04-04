<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md border border-slate-600 bg-slate-900 px-4 py-2 text-sm font-medium text-slate-200 transition duration-150 ease-in-out hover:bg-slate-800 hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-950 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <slot />
    </button>
</template>
