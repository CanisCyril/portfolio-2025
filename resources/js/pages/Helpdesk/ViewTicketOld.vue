<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'

import { TicketIcon } from '@heroicons/vue/24/solid'



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



onMounted(() => {

})

</script>

<template>

    <Head>
        <title>Helpdesk - View Ticket</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>

    <main class="container pt-4 mx-auto max-w-4xl bg-[#f9fafb]">

        <div class="flex flex-row items-center ">
            <TicketIcon class="size-8 ml-4" />
            <h2 class="card-title antialiased p-4 mb-1">Ticket #3213</h2>
        </div>

        <div class="flex flex-col md:flex-row-reverse md:items-start gap-8 ">
            <div class="card card-lg shadow-sm md:mt-8 md:w-[25%]">
                <div class="card-body p-0 py-4 md:p-4 flex">

                    <div class="grid grid-cols-2 gap-8">
                        <div class="flex flex-col items-center gap-2">
                            <label class="text-sm">Priority</label>
                            <span class="badge badge-warning py-1 px-2 w-full max-w-[80px]">Medium</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <label class="text-sm">Status</label>
                            <span class="badge badge-success py-1 px-2 w-full max-w-[80px]">Assigned</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <label class="text-sm">Category</label>
                            <span class="badge badge-neutral py-1 px-2 w-full max-w-[80px]">Internet</span>
                        </div>
                        <!-- <select class="select select-neutral">
                            <option disabled selected>Server location</option>
                            <option>North America</option>
                            <option>EU west</option>
                            <option>South East Asia</option>
                        </select> -->
                    </div>
                    <div class="grid grid-cols-2 md:flex md:flex-col gap-8 my-3 text-sm border-t-2 pt-4">
                        <div class="flex flex-col items-center md:items-start gap-2">
                            <label>Created</label>
                            <div class="badge badge-ghost p-2">2024-10-01 14:23</div>
                        </div>
                        <div class="flex flex-col items-center md:items-start gap-2">
                            <label>Last Updated</label>
                            <span class="badge badge-ghost p-2">2024-10-02 09:15</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-lg shadow-sm md:mt-8 md:w-[75%]">
                <div class="card-body p-4 flex gap-8">
                    <div class="flex flex-col gap-2 md:max-w-2xl">
                        <h3 class="text-lg font-semibold">Issue with Internet Connectivity</h3>
                        <p class="text-sm md:text-base">I'm experiencing frequent disconnections and slow speeds on my
                            office Wi-Fi network. This issue has been ongoing for the past week and is affecting my
                            productivity. I've tried restarting my router and checking for interference, but the problem
                            persists. Please assist in resolving this matter as soon as possible.
                        </p>
                    </div>

                    <div class="border-t-2 pt-4 mt-4 md:max-w-2xl">
                        <h6 class="font-semibold">Comments</h6>
                        <div class="flex flex-col gap-4 mt-2">
                            <div class="p-4 border rounded-lg bg-gray-50">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-medium">Support Agent</span>
                                    <span class="text-xs text-gray-500">2024-10-01 15:00</span>
                                </div>
                                <p class="text-sm">
                                    Hello, thank you for reaching out. We're sorry to hear about the issues you're
                                    facing with your internet connectivity. Our team is currently investigating the
                                    problem and will get back to you shortly with a solution.
                                </p>
                            </div>
                            <div class="p-4 border rounded-lg bg-gray-50">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-medium">User</span>
                                    <span class="text-xs text-gray-500">2024-10-01 16:30</span>
                                </div>
                                <p class="text-sm">
                                    Thank you for the prompt response. I appreciate your assistance and look forward
                                    to hearing back from your team soon.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <textarea placeholder="Enter comments..." class="textarea textarea-lg border-1 w-full min-h-[120px]"></textarea>
                    </div>



                    <!-- <div class="flex flex-col gap-8">
                        <div>
                            <h3 class="text-md font-bold mb-2">Attachments</h3>
                            <div class="flex gap-4">
                                <img class="w-32 h-32 object-cover rounded" src="https://via.placeholder.com/150"
                                    alt="Attachment 1">
                                <img class="w-32 h-32 object-cover rounded" src="https://via.placeholder.com/150"
                                    alt="Attachment 2">
                            </div>
                        </div>
                    </div> -->
                </div>
            </div> -->
        </div>
    </main>
</template>