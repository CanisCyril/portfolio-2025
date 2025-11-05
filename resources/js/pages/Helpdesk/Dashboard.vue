<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';

import { ref, onMounted, watch } from 'vue'
import {
    ChevronUpIcon, ChevronDownIcon, ChevronLeftIcon, ChevronRightIcon,
    PlusCircleIcon, ChartPieIcon, Cog8ToothIcon, PaperAirplaneIcon,
    MoonIcon, SunIcon
} from '@heroicons/vue/24/outline'

import { useBreakpoints, breakpointsTailwind } from '@vueuse/core'
import Pagination from '@/components/custom/Pagination.vue';

import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(relativeTime)

// Components

import ViewTicket from '@/components/custom/helpdesk/ViewTicketComponent.vue'

// Utils
import { statusColor } from '@/utils/helpdesk/statusColor'


type Permissions = {
    adminAccess: boolean
    adminOptions: boolean
}

type PageProps = {
    flash?: { success?: string }
    auth?: { user?: any }
}

// move types to different file

type Ticket = {
    id: number
    user_id: number
    title: string
    status: string
    assignee?: { id: number; name: string } | null
    created_at: string,
    user: object,
    priority: object,
}

// Subset of Laravel LengthAwarePaginator
type Paginator<T> = {
    data: T[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    links: { url: string | null; label: string; active: boolean }[]
}

type Counts = {
    open: number;
    assigned: number;
    recentlyClosed: number;
};

const props = defineProps<{
    permissions: Permissions,
    tickets: Paginator<Ticket>,
    counts: Counts,
}>()

const page = usePage<PageProps>();
const bp = useBreakpoints(breakpointsTailwind)
const isMdUp = bp.greaterOrEqual('md')   // true when Tailwind's md: rules apply

const STORAGE_KEY = 'theme' // 'light' | 'dark'

const isDark = ref(false)


const activeClass = 'text-zinc-200 pb-4'
const inactiveClass = 'text-gray-600 hover:text-gray-900 dark:hover:text-white pb-4 dark:text-zinc-400 transition-colors duration-300 ease-in-out'


// ** Flash Message Handling **

const success = ref(page.props.flash?.success ?? null)
const dismiss = () => { success.value = null }

watch(
    () => page.props.flash?.success,
    (val) => success.value = val ?? null,
    { immediate: true }
)

// ** End Flash Message Handling **

let filterBarOpen = ref(true)

const tabs = [
    { key: 'my', label: 'My Tickets', count: 0, isAdmin: false },
    { key: 'all', label: 'All', count: 0, isAdmin: true },
    { key: 'open', label: 'Open', count: props.counts.open, isAdmin: true },
    { key: 'assigned', label: 'Assigned', count: props.counts.assigned, isAdmin: true },
    { key: 'closed', label: 'Recently Closed', count: props.counts.recentlyClosed, isAdmin: true },
]

const activeTab = ref('my');
const selectedRow = ref<Ticket | null>(null)

watch(activeTab, (newKey) => {

    activeTabTickets(newKey);
});


function toggleFilterBar() {
    filterBarOpen.value = !filterBarOpen.value
}

onMounted(async () => {

    // let activeTabSaved = router.restore('active-key')
    // co§nsole.log('DATA', activeTabSaved)


    // if(data) router.restore('my-key')

    if (page.props.permissions === undefined) {
        await router.reload({ only: ['permissions'] })
    }

    console.log('tickets', props.tickets.data)

});

const activeTabTickets = (activeTab: any) => {

    router.get(route('helpdesk'), { activeTab }, {
        only: ['tickets', 'counts'],
        preserveState: true,
        preserveScroll: true,
        replace: true,
        // ...(values.file ? { forceFormData: true } : {}),
        onSuccess: () => {
            // reset vee-validate via resetForm() if you’re using the <Form v-slot>
        },
    })
};

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

const stored = localStorage.getItem('theme');
const prefers = matchMedia('(prefers-color-scheme: dark)').matches;
const dark = stored ? stored === 'dark' : prefers;
const el = document.documentElement;
el.classList.toggle('dark', dark);
el.style.colorScheme = dark ? 'dark' : 'light';

if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.classList.add('dark');
    document.documentElement.style.colorScheme = 'dark';
    isDark.value = true

}



</script>

<template>

    <Head>
        <title>Helpdesk</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>

    <main v-if="!selectedRow" class="flex flex-col md:flex-row ">
        <!-- maybe make fixed height while scrolling -->
        <nav id="nav"
            class="flex md:flex-col items-center justify-between p-4 w-full min-h-[48px] md:min-h-dvh md:w-auto 
            border-b-2 md:border-b-0 md:border-r-2 text-black bg-zinc-50  dark:bg-zinc-900 dark:text-white theme-color-smooth">
            <div class="flex flex-row md:flex-col gap-4 md:mt-4">
                <ChevronUpIcon v-if="!filterBarOpen && !isMdUp" class="size-6 cursor-pointer"
                    @click="toggleFilterBar" />
                <ChevronDownIcon v-if="filterBarOpen && !isMdUp" class="size-6 cursor-pointer"
                    @click="toggleFilterBar" />
                <ChevronLeftIcon v-if="filterBarOpen && isMdUp" class="size-6 cursor-pointer"
                    @click="toggleFilterBar" />
                <ChevronRightIcon v-if="!filterBarOpen && isMdUp" class="size-6 cursor-pointer"
                    @click="toggleFilterBar" />
                <div class="tooltip md:tooltip-right md:mt-4" data-tip="Create Ticket" aria-label="Create Ticket">
                    <Link :href="route('helpdesk.create')">
                    <PlusCircleIcon class="size-6 cursor-pointer  hover:text-green-600" />
                    </Link>
                </div>
                <div v-if="props.permissions.adminAccess == true" class="tooltip md:tooltip-right"
                    data-tip="View Reports" aria-label="View Reports">
                    <Link :href="route('helpdesk.report')">
                    <ChartPieIcon class="size-6 cursor-pointer" />
                    </Link>
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
        <div id="filters" class="flex flex-col
         transition-[max-height,padding] md:transition-[flex-basis,padding,border-width]
         duration-500 ease-in-out
         md:min-h-dvh md:min-w-0 dark:bg-zinc-800" :class="filterBarOpen
            ? 'max-h-[80svh] p-4 w-full md:basis-1/5 md:border-r'
            : 'max-h-0 p-0 md:basis-0 md:border-0'">
            <div class="w-full flex flex-col md:min-w-0 [contain:paint] dark:text-white
                transition-[opacity,filter] duration-300" :class="filterBarOpen
                    ? 'opacity-100 blur-0 delay-[500ms] pointer-events-auto'
                    : 'opacity-0 blur-sm delay-0 pointer-events-none'" :aria-hidden="!filterBarOpen">
                <h1 class="font-bold leading-tight text-zinc-500 mt-4">Helpdesk</h1>
                <div v-if="!isMdUp" class="p-1 mt-3">
                    <label class="input border-1 w-full border-zinc-400 bg-zinc-50 dark:bg-zinc-800">
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
                        class="flex flex-row md:flex-col gap-4 whitespace-nowrap [&>li]:min-w-fit [&>li:hover]:cursor-pointer">
                        <li v-for="tab in tabs" :key="tab.key"
                            :class="activeTab === tab.key ? activeClass : inactiveClass" @click="activeTab = tab.key">
                            <div v-if="props.permissions.adminAccess == true || props.permissions.adminAccess == false && tab.isAdmin == false"
                                class="flex flex-row items-center gap-2 px-2 py-3 rounded-lg transition-colors  ease-in"
                                :class="activeTab === tab.key ? 'bg-zinc-700 dark:bg-neutral-900 duration-300' : 'duration-0'">
                                <span class="badge px-3 py-2 bg-zinc-200 dark:bg-zinc-600 text-black dark:text-white"
                                    :class="activeTab === tab.key ? '' : ''">{{ tab.count }}</span>
                                <p>{{ tab.label }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- {{ page.props.auth?.user.id }} -->
        <!-- Small Screen Tickets -->
        <div v-if="!isMdUp" class="flex flex-col items-center mt-4 w-full">
            <div v-if="success" role="alert"
                class="alert alert-success alert-horizontal sm:alert-horizontal text-black m-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ success }}</span>
                <div>
                    <button class="btn btn-soft btn-sm" @click="dismiss">Dismiss</button>
                </div>
            </div>
            <div v-for="ticket in props.tickets.data" :key="ticket.id" @click="selectedRow = ticket"
                class="md:hidden card w-[80%] mx-8 bg-gray-100 dark:bg-zinc-800 card-sm shadow-sm m-4">
                <div class="card-body">
                    <p class="text-sm">{{ ticket.id }}</p>

                    <div class="flex justify-between">
                        <div>
                            <h2 class="card-title">{{ ticket.title }}</h2>
                        </div>

                        <div class="h-auto w-24 p-2 badge" :class="statusColor(ticket.status)">{{ ticket.status }}</div>
                    </div>
                    <div class="flex justify-between w-full">
                        <p>{{ ticket.user.name }}</p>
                        <p class="text-xsm ml-auto pr-2 text-right whitespace-nowrap">{{
                            dayjs(ticket.created_at).fromNow() }}</p>
                    </div>
                </div>
            </div>
            <Pagination :links="props.tickets.links" />

        </div>

        <!-- Other screens ticket -->

        <div v-if="isMdUp" id="table" class="hidden flex-1 md:min-h-dvh md:flex flex-col px-4 mx-auto">
            <div v-if="success" role="alert"
                class="alert alert-success alert-vertical sm:alert-horizontal text-black mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ success }}</span>
                <div>
                    <button class="btn btn-soft btn-sm" @click="dismiss">Dismiss</button>
                </div>
            </div>
            <!-- <h1 class="mt-2 text-xl font-bold leading-text">Tickets</h1> -->
            <div class="mt-4 max-w-xs">
                <label class="input border-1 w-full border-zinc-400 bg-zinc-50 dark:bg-zinc-800">
                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                            stroke="currentColor">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                    <input type="search" placeholder="Search tickets" class="placeholder:italic" />
                </label>
                <p class="text-zinc-500 p-2 text-sm">Add active tab here maybee</p>
            </div>
            <div class="h-[90%]">
                <table class="table border-b-1 dark:text-white">
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
                        <tr v-for="ticket in props.tickets.data" :key="ticket.id"
                            class="dark:even:bg-zinc-900 dark:odd:bg-zinc-800 dark:hover:bg-zinc-700 hover:bg-zinc-300 cursor-pointer transition-colors duration-600 ease-in-out max-h-8"
                            @click="selectedRow = ticket">
                            <th>{{ ticket.id }}</th>
                            <td>{{ ticket.title }}</td>
                            <td>{{ ticket.user.name }}</td>
                            <td>
                                <span class="h-auto min-w-16 px-2 badge" :class="statusColor(ticket.status)">
                                    {{ ticket.status }}
                                </span>
                            </td>
                            <td>{{ dayjs(ticket.created_at).fromNow() }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="shrink-0 align-top">
                    <Pagination class="float-right" :links="props.tickets.links" />
                </div>
            </div>

        </div>
    </main>
    <ViewTicket v-if="selectedRow" :ticket="selectedRow" :auth="page.props.auth" @close="selectedRow = null" />
</template>

<style>
/* #role_modal.modal {
    background-color: #27272a !important;
} */
</style>