<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'

import { TicketIcon } from '@heroicons/vue/24/solid'

const form = useForm({
    title: '',
    description: '',
    category_id: '',
    priority_id: null,
    attachment: null,
})

const priorities = ref([
    { id: 1, name: 'Low' },
    { id: 2, name: 'Medium' },
    { id: 3, name: 'High' },
    { id: 4, name: 'Business Critical' },
]);

const categories = ref([
    { id: 1, name: 'Internet' },
    { id: 2, name: 'Email' },
    { id: 3, name: 'Software' },
    { id: 4, name: 'Hardware' },
    { id: 4, name: 'Other' },
]);

function submit() {
    form.post(route('helpdesk.tickets.store'), {
        replace: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
        }
    });
}

onMounted(() => {

})

</script>

<template>

    <Head>
        <title>Helpdesk - Create Ticket</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>

    <main class="container mx-auto max-w-4xl">
        <div class="card card-lg shadow-sm mt-8">
            <div class="card-body p-0">
                <div class="flex flex-row items-center border-b-1 ">
                    <TicketIcon class="size-8 ml-4" />
                    <h2 class="card-title p-4 mb-1">Create Ticket</h2>
                </div>
                <div class="flex flex-col gap-8 p-4">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Title</legend>
                        <input v-model="form.title" type="text" class="input border-1 w-full" placeholder="Type here" />
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Description</legend>
                        <textarea v-model="form.description" class="textarea border-1 h-42 w-full"
                            placeholder="Describe the issue"></textarea>
                    </fieldset>
                    <div class="flex md:flex-row flex-col gap-4 w-full">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Category</legend>
                            <select v-model="form.category_id" class="select border-1 w-full">
                                <option disabled selected>Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </fieldset>
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Priority</legend>
                            <select v-model="form.priority_id" class="select border-1 w-full">
                                <option disabled selected>Priority</option>
                                <option v-for="priority in priorities" :key="priority.id" :value="priority.id">
                                    {{ priority.name }}
                                </option>
                            </select>
                        </fieldset>
                    </div>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Pick a file</legend>
                        <input @change="form.attachment = $event.target.files?.[0] ?? null" type="file"
                            class="file-input" />
                        <label class="label p-1">Optional</label>
                        <label class="label">Max size 2MB</label>
                    </fieldset>
                    <div class="card-actions justify-end">
                        <button class="btn btn-neutral mt-6" @click="submit">Submit Ticket</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>