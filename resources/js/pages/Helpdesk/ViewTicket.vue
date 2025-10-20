<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'

import {
    ArrowLeftIcon, ShieldCheckIcon, WifiIcon, TagIcon,
    CalendarIcon, ClockIcon, PaperClipIcon, ArrowDownTrayIcon
} from '@heroicons/vue/24/outline'



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
    <div class="min-h-screen bg-neutral-200 dark:bg-zinc-900">
        <nav
            class="flex flex-row justify-between items-center mb-4 py-2 px-4 bg-neutral-50 rounded-sm min-h-[50px] dark:bg-zinc-950">
            <div class="flex flex-row items-center">
                <span class="hover:bg-zinc-200 dark:hover:bg-zinc-700 p-1 rounded-md">
                    <ArrowLeftIcon class="size-6 cursor-pointer" />
                </span>
                <div class="divider divider-horizontal"></div>
                <span class="text-zinc-500">Ticket / #123</span>
            </div>
            <div>
                <!-- <ul class="flex flex-row gap-4">
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                </ul> -->
            </div>
        </nav>
        <main class="container mx-auto max-w-6xl text-zinc-900 dark:text-zinc-100">
            <div
                class="md:min-h-[700px] flex flex-col-reverse md:grid md:[grid-template-columns:70%_30%] gap-4 p-4 md:mt-12">
                <div class="flex flex-col gap-8 p-4">
                    <div class="card w-96 bg-base-100 shadow-sm w-full dark:bg-zinc-950">
                        <div class="card-body">
                            <h1 class="text-2xl font-bold leading-tight">Issue with Internet Connectivity</h1>
                            <p class="text-sm text-zinc-500 px-1">Created 1 day ago</p>
                            <!-- <div class="divider"></div> -->
                            <p class="text-md pt-4">I'm experiencing intermittent connectivity issues with my internet
                                connection. The
                                connection drops randomly throughout the day, making it difficult to work online. I've
                                tried
                                restarting my router and checking for loose cables, but the problem persists. Can
                                someone please
                                assist me in resolving this issue?</p>
                        </div>
                    </div>
                    <div class="card w-96 bg-base-100 shadow-sm w-full dark:bg-zinc-950 mb-8">
                        <div class="card-body flex gap-4">
                            <h1 class="text-xl font-bold leading-tight">Activity</h1>
                            <p class="text-sm text-zinc-500 px-1">Conversations and updates</p>
                            <!-- <div class="divider"></div> -->
                            <div class="flex flex-col gap-4 mt-2">
                                <div class="p-4 border rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-medium">Support Agent</span>
                                        <span class="text-xs text-gray-500">2024-10-01 15:00</span>
                                    </div>
                                    <p class="text-sm dark:text-zinc-50">
                                        Hello, thank you for reaching out. We're sorry to hear about the issues
                                        you're
                                        facing with your internet connectivity. Our team is currently investigating
                                        the
                                        problem and will get back to you shortly with a solution.
                                    </p>
                                </div>
                                <div class="p-4 border rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-medium">User</span>
                                        <span class="text-xs text-gray-500">2024-10-01 16:30</span>
                                    </div>
                                    <p class="text-sm dark:text-zinc-50">
                                        Thank you for the prompt response. I appreciate your assistance and look
                                        forward
                                        to hearing back from your team soon.
                                    </p>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <p class="text-sm text-zinc-500 px-1">Add a comment</p>
                            <div class="px-1">
                                <textarea placeholder="Type here..." class="textarea textarea-md border-1 w-full"
                                    rows="8"></textarea>
                            </div>
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Pick a file</legend>
                                <input type="file" class="file-input" />
                                <label class="label">Max size 2MB</label>
                            </fieldset>
                            <div class="w-full">
                                <button class="btn btn-neutral float-right">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-8 p-4">
                    <div class="card w-96 bg-base-100 shadow-sm w-full dark:bg-zinc-950">
                        <div class="card-body">
                            <h1 class="text-xl font-bold leading-tight">Ticket Details</h1>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <ShieldCheckIcon class="size-5" />
                                    <span>Status</span>
                                </div>
                                <span class="badge badge-success p-1 w-full max-w-[80px]">Open</span>
                            </div>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <WifiIcon class="size-5" />
                                    <span>Priority</span>
                                </div>
                                <span class="badge badge-warning p-1 w-full max-w-[80px]">Med</span>
                            </div>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <TagIcon class="size-5" />
                                    <span>Category</span>
                                </div>
                                <span class="badge badge-ghost p-1 w-full max-w-[80px]">Internet</span>
                            </div>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <CalendarIcon class="size-5" />
                                    <span>Created At</span>
                                </div>
                                <span class="p-1 w-full max-w-[80px]">12/12/2025 13:30</span>
                            </div>
                            <div class="flex flex-row justify-between gap-4 mt-4 text-zinc-500">
                                <div class="flex flex-row gap-1 items-center">
                                    <ClockIcon class="size-5" />
                                    <span>Updated At</span>
                                </div>
                                <span class="p-1 w-full max-w-[80px]">12/12/2025 13:30</span>
                            </div>
                        </div>
                    </div>
                    <div class="card w-96 bg-base-100 shadow-sm w-full dark:bg-zinc-950">
                        <div class="card-body">
                            <h1 class="text-xl font-bold leading-tight">Assigned to</h1>
                            <div class="flex flex-row items-center gap-4 mt-4">
                                <div class="avatar">
                                    <div class="w-12 rounded-full">
                                        <img src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <h1 class="text-md font-bold leading-tight">John Smith</h1>
                                    <p class="text-sm text-zinc-500">Software Engineer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card w-96 bg-base-100 shadow-sm w-full dark:bg-zinc-950">
                        <div class="card-body">
                            <h1 class="text-xl font-bold leading-tight">Attachments</h1>
                            <div class="flex flex-row justify-between items-center border gap-4 mt-4 p-4 rounded-lg">
                                <!-- <span>icon</span> -->
                                <div class="flex flex-row items-center gap-2">
                                    <PaperClipIcon class="size-5" />
                                    <div class="flex flex-col">
                                        <span>Screenshot error.png</span>
                                        <span class="text-xs text-zinc-500">250KB</span>
                                    </div>
                                </div>
                                <span class="hover:bg-zinc-200 hover:dark:bg-zinc-800 p-2 rounded-md cursor-pointer">
                                    <ArrowDownTrayIcon class="size-5" />
                                </span>
                                <!-- <span class="text-sm cursor-pointer badge badge-neutral p-2">Download</span> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>