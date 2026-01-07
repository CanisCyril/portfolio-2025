<template>
    <div>
        <nav
            class="flex flex-row justify-between items-center mb-4 py-2 px-4 bg-neutral-50 dark:bg-zinc-950 rounded-sm min-h-[50px]">
            <div class="flex flex-row items-center">
                <Link :href="href" class="hover:bg-zinc-200 dark:hover:bg-zinc-700 p-1 rounded-md">
                <ArrowLeftIcon class="size-6 cursor-pointer" />
                </Link>
                <div class="divider divider-horizontal"></div>
                <span class="text-zinc-500">{{ title }} </span>
            </div>
        </nav>
    </div>
</template>

<script lang="ts">
import { Link } from '@inertiajs/vue3'
import {
    ArrowLeftIcon
} from '@heroicons/vue/24/outline'

export default {
    components: { Link, ArrowLeftIcon },

    props: {
        title: { type: String, required: true },
        href: { type: String, required: true },
    }
}

</script>