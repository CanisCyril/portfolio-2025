<script setup lang="ts">

// ADD COMPOSABLES ? needed from this ?

import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    title: any,
    description: any,
    preview: any,
    tags: any,
    link: any,
}>();
</script>

<template>
    <div class="card bg-base-100 card-lg shadow-md inset-shadow-xs">
        <div class="card-body flex flex-col gap-3">
            <h2 class="card-title dark:text-black">{{ title }}</h2>
            <p class="text-sm md:text-base dark:text-black">{{ description }}</p>
            <img class="flex justify-cennter p-4" :src="preview" alt="placeholder img">
            <div class="flex flex-row basis-1/2 gap-2 flex-wrap font-bold my-2">
                <span v-for="(tag, index) in tags" :key="index" class="badge bg-gray-200 p-4">{{ tag }}</span>
            </div>

            <Link :href="route(props.link)">
                <button class="btn btn-neutral w-20">
                <!-- add link icon -->
                    Live
                </button>
            </Link>
        </div>
    </div>
</template>