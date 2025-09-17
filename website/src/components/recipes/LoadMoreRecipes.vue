<script setup>
import { ref, computed } from "vue";
import RecipeCard from "@components/recipes/RecipeCard.vue";
import LoadingIcon from "@components/icons/LoadingIcon.vue";
import {CLIENT_CORE_API_TOKEN} from "astro:env/client";

const isFirstLoad = ref(true);
const loadedItems = ref([])
const pageItems = ref({})
const isLoading = ref(false)

const props = defineProps({
    nextPageUrl: String
});

const hasNextPage = computed(() => {
    return pageItems.value?.links?.next || (isFirstLoad.value && props.nextPageUrl);
});

const nextUrl = computed(() => {
    return pageItems.value?.links?.next || props.nextPageUrl;
});

const loadMore = async () => {
    if (isLoading.value || !nextUrl.value) return;

    isLoading.value = true;

    try {
        const response = await fetch(nextUrl.value, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${CLIENT_CORE_API_TOKEN}`
            },
        });

        if (!response.ok) console.error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        pageItems.value = data;

        if (data.data?.length > 0) loadedItems.value = [...loadedItems.value, ...data.data];

        isFirstLoad.value = false;
    } catch (err) {
        console.error('Error loading more items:', err);
    } finally {
        isLoading.value = false;
    }
}
</script>

<template>
    <div v-if="loadedItems.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        <RecipeCard v-for="item in loadedItems" :key="item.id" :name="item.attributes.name" :image="item.attributes.image" :excerpt="item.attributes.excerpt">
            <template v-slot:image>
                <img :src="item.attributes.image" :alt="item.attributes.name" class="recipe-card-image" width="400" height="300" />
            </template>
        </RecipeCard>
    </div>

    <div v-if="hasNextPage" class="text-center">
        <button @click="loadMore" :disabled="isLoading" class="btn-primary">
            <span v-if="isLoading" class="flex items-center justify-center">
                <LoadingIcon /> Loading...
            </span>

            <span v-else>Show More</span>
        </button>
    </div>
</template>