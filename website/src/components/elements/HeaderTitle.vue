<script setup>
</script>

<template>
    <div class="text-center mb-16">
        <h1 class="text-4xl lg:text-5xl font-bold text-slate-800 mb-6">
            <slot name="title" />
        </h1>
        <p class="text-xl text-slate-600 max-w-2xl mx-auto">
            <slot name="subtitle" />
        </p>
    </div>
</template>