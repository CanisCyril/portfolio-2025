<template>
    <div>
        <component
            :is="link.url ? 'Link' : 'a'"
            v-for="link in links"
            :href="link.url"
            class="px-3  mx-1 my-4 btn btn-success "
            v-html="link.label"
            :class="{'text-gray-500': !link.url, 'font-bold' : link.active}"
        />
    </div>
</template>

<script lang="ts">
import { Link } from '@inertiajs/vue3'
export default {
    components: { Link }, 
    
    props: {
        links: Array
    }
}

</script>