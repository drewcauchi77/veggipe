<script setup>
defineProps({
    image: String,
    name: String,
    excerpt: String,
});
</script>

<template>
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden">
        <div class="relative overflow-hidden">
            <slot name="image" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>

        <div class="p-6">
            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-emerald-600 transition-colors duration-300">
                {{ name }}
            </h3>

            <p class="text-slate-600 mb-4 leading-relaxed">
                {{ excerpt }}
            </p>

<!--            <div class="flex items-center justify-between">-->
<!--                <button class="text-emerald-600 font-semibold hover:text-emerald-700 transition-colors duration-300">-->
<!--                    View Recipe →-->
<!--                </button>-->
<!--            </div>-->
        </div>
    </div>
</template>