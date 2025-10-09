<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import { ref, onMounted } from 'vue'
import {
    ChevronUpIcon, ChevronDownIcon, ChevronLeftIcon, ChevronRightIcon,
    PlusCircleIcon, ChartPieIcon, Cog8ToothIcon, PaperAirplaneIcon,
    MoonIcon, SunIcon
} from '@heroicons/vue/24/outline'

import { useBreakpoints, breakpointsTailwind } from '@vueuse/core'
import { ChartPie, PlusCircle } from 'lucide-vue-next';
const bp = useBreakpoints(breakpointsTailwind)
const isMdUp = bp.greaterOrEqual('md')   // true when Tailwind's md: rules apply

const STORAGE_KEY = 'theme' // 'light' | 'dark'

const isDark = ref(false)

let filterBarOpen = ref(true)
const tickets = ref([{
    id: 1,
    name: 'John Doe',
    subject: 'Issue with login',
    job: 'Front-end Developer',
    status: 'Completed',
    ticket: '#12345',
    due: 'Due in 2 days'
},
{
    id: 2,
    name: 'Jane Smith',
    subject: 'Database error',
    job: 'Back-end Developer',
    status: 'In Progress',
    ticket: '#12346',
    due: 'Due in 5 days'
},
{
    id: 3,
    name: 'Sam Wilson',
    subject: 'UI Bug on Dashboard',
    job: 'Full-stack Developer',
    status: 'Pending',
    ticket: '#12347',
    due: 'Due in 1 week'
},
{
    id: 4,
    name: 'Alice Johnson',
    subject: 'Design Review',
    job: 'UI/UX Designer',
    status: 'Completed',
    ticket: '#12348',
    due: 'Due in 3 days'
},
{
    id: 5,
    name: 'Bob Brown',
    subject: 'Server Downtime',
    job: 'DevOps Engineer',
    status: 'In Progress',
    ticket: '#12349',
    due: 'Due in 4 days'
},
{
    id: 6,
    name: 'Charlie Davis',
    subject: 'Testing New Features',
    job: 'QA Engineer',
    status: 'Pending',
    ticket: '#12350',
    due: 'Due in 2 weeks'
},
{
    id: 7,
    name: 'Eve Martinez',
    subject: 'Product Launch',
    job: 'Product Manager',
    status: 'Completed',
    ticket: '#12351',
    due: 'Due in 1 day'
}
])

document.documentElement.classList.toggle(  "dark",  localStorage.theme === "dark" ||    (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches),);// Whenever the user explicitly chooses light modelocalStorage.theme = "light";// Whenever the user explicitly chooses dark modelocalStorage.theme = "dark";// Whenever the user explicitly chooses to respect the OS preferencelocalStorage.removeItem("theme");

function applyTheme(dark) {
    isDark.value = dark
    const el = document.documentElement
    el.classList.toggle('dark', dark)
    el.classList.add('theme-transition')
    localStorage.setItem(STORAGE_KEY, dark ? 'dark' : 'light')
}

function toggleTheme() {
    applyTheme(!isDark.value)
}

const selectedTicket = ref(null);

const tabs = [
    { key: 'my', label: 'My Tickets' },
    { key: 'all', label: 'All' },
    { key: 'open', label: 'Open' },
    { key: 'assigned', label: 'Assigned' },
    { key: 'closed', label: 'Recently Closed' },
]

const activeKey = ref('my');

const activeClass = 'badge bg-gray-100 dark:bg-gray-200 text-black p-4 w-full'
const inactiveClass = 'text-gray-600 hover:text-gray-900 p-4 dark:text-base-100 w-full'

function onRowClick(ticket) {
    selectedTicket.value = ticket;
    console.log('Row clicked:', selectedTicket.value);
}

const statusColor = (status = '') => {
    switch (status.trim().toLowerCase()) {
        case 'completed':
            return 'badge-success'
        case 'in progress':
            return 'badge-info'      // was warning
        case 'pending':
            return 'badge-warning'   // was error
        case 'overdue':
            return 'badge-error'
        default:
            return 'badge-neutral'   // or 'badge-secondary' if you prefer
    }
}

function toggleFilterBar() {
    filterBarOpen.value = !filterBarOpen.value
}

onMounted(() => {
    // load saved preference or default to light
    // const saved = localStorage.getItem(STORAGE_KEY)
    // setTimeout(() => {
    //     applyTheme(saved ? saved === 'dark' : false)

    // }, 300)
})

</script>

<template>

    <Head>
        <title>Helpdesk</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>

    <main class="flex flex-col md:flex-row items-center">
        <!-- Needs to be 1fixed height while scrolling -->
        <nav id="nav"
            class="flex md:flex-col items-center justify-between p-4 w-full min-h-[48px] md:min-h-dvh md:w-auto 
            border-b-2 md:border-b-0 md:border-r-2 text-black bg-base-100  dark:bg-zinc-900 dark:text-white theme-color-smooth">
            <div class="flex flex-row md:flex-col gap-4 md:mt-4">
                <ChevronUpIcon v-if="!filterBarOpen && !isMdUp" class="size-6 cursor-pointer"
                    @click="toggleFilterBar" />
                <ChevronDownIcon v-if="filterBarOpen && !isMdUp" class="size-6 cursor-pointer"
                    @click="toggleFilterBar" />
                <ChevronLeftIcon v-if="filterBarOpen && isMdUp" class="size-8 cursor-pointer"
                    @click="toggleFilterBar" />
                <ChevronRightIcon v-if="!filterBarOpen && isMdUp" class="size-8 cursor-pointer"
                    @click="toggleFilterBar" />
                <div class="tooltip md:tooltip-right md:mt-4" data-tip="Create Ticket" aria-label="Create Ticket">
                    <Link :href="route('helpdesk.create')">
                        <PlusCircleIcon class="size-8 cursor-pointer  hover:text-green-600" />
                    </Link>
                </div>
                <div class="tooltip md:tooltip-right" data-tip="View Reports" aria-label="View Reports">
                    <ChartPieIcon class="size-8 cursor-pointer" />
                </div>


                <!-- <h2 class="text-white md:hidden">Ticket System</h2> -->
            </div>
            <div class="flex items-center md:flex-col gap-4 [&>a:hover]:cursor-pointer [&>a:hover]:text-gray-500 text-black dark:text-white"
                data-tip="Settings" aria-label="Settings">
                <div v-if="!isDark" class="cursor-pointer tooltip tooltip-right" data-tip="Dark Mode"
                    aria-label="Dark Mode" @click="toggleTheme">
                    <MoonIcon class="size-6 
                        [&>path]:transition-[fill,stroke] [&>path]:duration-500 [&>path]:ease-out
                        [&>path]:fill-transparent
                        hover:[&>path]:fill-black hover:[&>path]:stroke-black" />
                </div>
                <div v-if="isDark"
                    class="cursor-pointer tooltip tooltip-right hover:text-yellow-300 transition-colors duration-500 ease-in-out"
                    data-tip="Light Mode" aria-label="Light Mode" @click="toggleTheme">
                    <SunIcon class="size-6" />
                </div>
                <div class="cursor-pointer tooltip md:tooltip-right" data-tip="Settings" aria-label="Settings">
                    <Cog8ToothIcon class="size-6" />
                </div>
                <!-- <div class="avatar m-2">
                    <div class="w-12 rounded-full">
                        <img src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" />
                    </div>
                </div> -->
            </div>
        </nav>
        <div id="filters" class="flex flex-col items-center
         transition-[max-height,padding] md:transition-[flex-basis,padding,border-width]
         duration-500 ease-in-out
         md:min-h-dvh md:min-w-0 dark:bg-zinc-800 md:dark:border-r-1 md:dark:border-zinc-700" :class="filterBarOpen
            ? 'max-h-[80svh] p-4 md:basis-1/5 md:border-r'
            : 'max-h-0 p-0 md:basis-0 md:border-0'">

            <div class="w-full flex flex-col md:min-w-0 [contain:paint] dark:text-black
           transition-[opacity,filter] duration-300" :class="filterBarOpen
            ? 'opacity-100 blur-0 delay-[500ms] pointer-events-auto'
            : 'opacity-0 blur-sm delay-0 pointer-events-none'" :aria-hidden="!filterBarOpen">
                <div class="p-1 mt-3">
                    <label class="input border-1 w-full">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input type="search" placeholder="Search tickets" class="placeholder:italic" />
                    </label>
                </div>

                <div class="overflow-x-scroll max-w-[96dvw] mt-4">
                    <!-- Add aninmation -->
                    <ul
                        class="flex flex-row md:flex-col gap-8 whitespace-nowrap [&>li]:min-w-fit [&>li:hover]:cursor-pointer [&>li:hover]:text-gray-500">
                        <li v-for="tab in tabs" :key="tab.key"
                            :class="activeKey === tab.key ? activeClass : inactiveClass" @click="activeKey = tab.key">
                            {{ tab.label }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <!-- Small Screen Tickets -->

        <div v-if="!isMdUp" v-for="ticket in tickets" :key="ticket.id"
            class="md:hidden card w-[80%] mx-8 bg-base-100 card-sm shadow-sm m-4">
            <div class="card-body">
                <p class="text-sm">{{ ticket.ticket }}</p>

                <div class="flex justify-between">
                    <div>
                        <h2 class="card-title">{{ ticket.subject }}</h2>
                    </div>

                    <div class="h-auto w-24 p-2 badge" :class="statusColor(ticket.status)">{{ ticket.status }}</div>
                </div>
                <div class="flex justify-between w-full">
                    <p>{{ ticket.name }}</p>
                    <p class="text-xsm ml-auto text-right whitespace-nowrap">{{ ticket.due }}</p>
                </div>
            </div>
        </div>

        <!-- Other screens ticket -->

        <div v-if="isMdUp" id="table" class="hidden flex-1 md:min-h-dvh md:flex flex-col ">
            <table class="table flex-1 border-b-1 dark:text-white">
                <!-- head -->
                <thead>
                    <tr class="dark:text-white">
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Due</th>
                    </tr>
                </thead>
                <tbody class="border-1">
                    <tr v-for="ticket in tickets" :key="ticket.id"
                        class="dark:even:bg-zinc-900 dark:odd:bg-zinc-800 dark:hover:bg-slate-600 cursor-pointer"
                        @click="onRowClick(ticket)">
                        <th>{{ ticket.ticket }}</th>
                        <td>{{ ticket.subject }}</td>
                        <td>{{ ticket.name }}</td>
                        <td>
                            <span class="h-auto w-28 p-2 badge" :class="statusColor(ticket.status)">
                                {{ ticket.status }}
                            </span>
                        </td>
                        <td>{{ ticket.due }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="shrink-0">
                <button class="btn btn-neutral dark:border-1 dark:border-white m-4 float-right">
                    <PaperAirplaneIcon class="size-6 mr-2" />
                    Next Page
                </button>
            </div>
        </div>
    </main>

</template>

<style></style>