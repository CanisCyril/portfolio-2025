<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { useBreakpoints, breakpointsTailwind, useDebounceFn } from '@vueuse/core'
import { ref, reactive, onMounted, watch } from 'vue'
import axios from 'axios';

import {
    ChevronUpIcon, ChevronDownIcon, ChevronLeftIcon, ChevronRightIcon,
    PlusCircleIcon, ChartPieIcon, Cog8ToothIcon, PaperAirplaneIcon,
    MoonIcon, SunIcon, ArrowLeftEndOnRectangleIcon
} from '@heroicons/vue/24/outline';

import Vue3Datatable from '@bhplugin/vue3-datatable';
import '@bhplugin/vue3-datatable/dist/style.css';
// import Pagination from '@/components/custom/Pagination.vue';

import { VueAwesomePaginate } from "vue-awesome-paginate";
import "vue-awesome-paginate/dist/style.css";

import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime';

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
    my: number;
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
const isMdUp = bp.greaterOrEqual('md')   

const STORAGE_KEY = 'theme' // 'light' | 'dark'

const isDark = ref(false)
const table = reactive({
    loading: true,
    search: null,
    totalRows: 0,
    currentPage: 1,
    pagesize: 10,
    sortColumn: 'id',
    sortDirection: 'asc',
});
const rows = ref([]);

const activeClass = 'text-zinc-200 pb-4'
const inactiveClass = 'text-gray-600 hover:text-gray-900 dark:hover:text-white pb-4 dark:text-zinc-400 transition-colors duration-300 ease-in-out'

const cols =
    ref([
        { field: "id", title: "ID", type: 'number', width: "90px", filter: false, isUnique: true },
        { field: "title", title: "Subject" },
        { field: "user.name", title: "Name", sort: false },
        { field: "assignee", title: "Assignee", sort: false },
        { field: "status", title: "Status" },
        { field: "created_at", title: "Created" }, //change to due with calcuations
    ]) || [];


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
    { key: 'my', label: 'My Tickets', count: props.counts.my, isAdmin: false },
    // { key: 'all', label: 'All', count: 0, isAdmin: true },
    { key: 'open', label: 'Open', count: props.counts.open, isAdmin: true },
    { key: 'assigned', label: 'Assigned', count: props.counts.assigned, isAdmin: true },
    { key: 'closed', label: 'Recently Closed', count: props.counts.recentlyClosed, isAdmin: true },
]

const activeTab = ref('my');
const selectedRow = ref<Ticket | null>(null)

watch(activeTab, (newKey) => {

    // activeTabTickets(newKey);
    tabSwitched();

});

function mobilePagination(pageNumber: number) {
    table.currentPage = pageNumber

    fetchTickets({
        current_page: table.currentPage,
        pagesize: 5,
        search: table.search,
        activeTab: activeTab.value,
        sort_column: table.sortColumn,
        sort_direction: table.sortDirection,
    });
}

function tabSwitched() {
    fetchTickets({
        current_page: table.currentPage,
        pagesize: isMdUp.value ? table.pagesize : 5,
        search: table.search,
        activeTab: activeTab.value,
        sort_column: table.sortColumn,
        sort_direction: table.sortDirection,
    });
}


function toggleFilterBar() {
    filterBarOpen.value = !filterBarOpen.value
}

onMounted(async () => {

    table.totalRows = props.tickets.total;

    if (page.props.permissions === undefined) {
        await router.reload({ only: ['permissions'] })
    }

    table.loading = false;

});

const fetchTickets = useDebounceFn((params?: any) => { //add params data type custom

    table.loading = true;
    // rows.value = [];

    axios.get(route('helpdesk.tickets.fetch'), {
        params: {
            page: params.current_page,
            per_page: params.pagesize,
            search: table.search,
            activeTab: activeTab.value,
            sort_column: params.sort_column,
            sort_direction: params.sort_direction,
        },
    })
        .then((response) => {
            rows.value = response.data.data;
            table.totalRows = response.data.total;
            table.loading = false;
        })
        .catch((error) => {
            table.loading = false;
        });
}, 300);

const rowClicked = (row: any) => {

    selectedRow.value = row
};


const rowClosed = () => {
    selectedRow.value = null
    router.reload({ only: ['counts'] })
};

// THEME TOGGLER

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
                <div class="cursor-pointer tooltip md:tooltip-right" data-tip="Logout" aria-label="Logout">
                    <Link href="/helpdesk/demo-login">
                        <ArrowLeftEndOnRectangleIcon class="size-6" />
                    </Link>
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
            ? 'p-4 w-full md:basis-1/5 md:border-r'
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
                        <input type="input" v-model="table.search" @input="mobilePagination(table.currentPage)" placeholder="Search tickets"
                            class="placeholder:italic" />
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
            <div v-for="ticket in rows" :key="ticket.id" @click="selectedRow = ticket"
                class="md:hidden card w-[80%] mx-8 bg-gray-100 dark:bg-zinc-800 card-sm shadow-sm m-4">
                <div class="card-body">
                    <p class="text-sm">{{ ticket.id }}</p>

                    <div class="flex justify-between">
                        <div>
                            <h2 class="card-title">{{ ticket.title }}</h2>
                        </div>

                        <div :class="[statusColor(ticket.status), 'badge min-w-24 p-2 truncate']">{{ ticket.status }}
                        </div>
                    </div>
                    <div class="flex justify-between w-full">
                        <p>{{ ticket.user.name }}</p>
                        <p class="text-xsm ml-auto pr-2 text-right whitespace-nowrap">{{
                            dayjs(ticket.created_at).fromNow() }}</p>
                    </div>
                </div>
            </div>
            <vue-awesome-paginate v-if="table.totalRows" class="my-12" :total-items="table.totalRows" :items-per-page="5" :max-pages-shown="5" v-model="table.currentPage"
                @click="mobilePagination" />
            <h1 v-else class="mt-8 text-[1.5rem]" >No Tickets</h1>

        </div>

        <!-- Other screens ticket -->

        <div v-if="isMdUp" id="table" class="hidden w-full lg:w-[75%] lg:mx-auto md:min-h-dvh md:flex flex-col px-4">
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
                    <input type="search" v-model="table.search" placeholder="Search tickets"
                        class="placeholder:italic" />
                </label>
                <p class="text-zinc-500 p-2 text-sm capitalize">{{ activeTab }} Tickets</p>
            </div>
            <div class="h-[90%]">
                <vue3-datatable :rows="rows" :columns="cols" :totalRows="table.totalRows" :loading="table.loading"
                    headerClass="dark:bg-zinc-900 dark:text-blue-900" skin="dark:text-white"
                    rowClass="dark:text-zinc-200 dark:odd:bg-neutral-900 dark:even:bg-zinc-800 dark:hover:bg-zinc-700 cursor-pointer transition-colors duration-300 ease-in cursor-pointer"
                    :search="table.search" :isServerMode="true" :sortable="true" @change="fetchTickets"
                    @rowClick="rowClicked">
                    <template #assignee="data">
                        <span>
                            {{ data.value.assignee?.name ?? 'Not Assigned' }}
                        </span>
                    </template>
                    <template #status="data">
                        <span class="h-auto min-w-16 px-2 badge capitalize" :class="statusColor(data.value.status)">
                            {{ data.value.status }}
                        </span>
                    </template>
                    <template #created_at="data">
                        <span>
                            {{ dayjs(data.value.created_at).format('MMM D, YYYY h:mm A') }}
                        </span>
                    </template>
                </vue3-datatable>
            </div>
        </div>
    </main>
    <ViewTicket v-if="selectedRow" v-model:ticket="selectedRow" :auth="page.props.auth" :permissions="permissions"
        @close="rowClosed" />
</template>

<style>
/* Dark Mode */


html.dark .bh-pagination-info {
    color: #fff;
}

/* html.dark th .bh-sort svg polygon:first-child {
    fill: #4f46e5 !important;
} */

/* Bottom arrow */
th .bh-sort svg polygon {
    fill: #374151 !important;
}

html.dark .bh-table-responsive table tbody tr {
    /* --tw-border-opacity: 0; */
    border-color: oklch(12% 0.006 285.885);
}

html.dark .bh-table-responsive {
    border-radius: 0 !important;
    background-color: transparent;
}

html.dark th {
    background: oklch(19% 0.006 285.885);
}

.bh-absolute {
    background-color: transparent;
}

html.dark .bh-skeleton-box {
    background-color: #09090b !important;
    /* zinc-950 */
    color: #e5e7eb !important;
}

/* (optional) inner “bars” of the skeleton */
/* .dark .bh-skeleton-box * {
  background-color: #686885 !important;  zinc-800
} */

html.dark .bh-h-11 {
    background-color: #121218 !important;
}

/* Pagination */

html.dark .bh-active {
    background-color: #52525b !important;
    border-color: #52525b !important;
}

.bh-active {
    background-color: #3f3f46 !important;
    border-color: #3f3f46 !important;
}

.bh-page-item:hover {
    background-color: #71717a !important;
    border-color: #71717a !important;
}

/* Mobile Pagination */
  .pagination-container {
    display: flex;

    column-gap: 10px;
  }

  .paginate-buttons {
    height: 40px;

    width: 40px;

    border-radius: 20px;

    cursor: pointer;

    background-color: rgb(242, 242, 242);

    border: 1px solid rgb(217, 217, 217);

    color: black;
  }

  .paginate-buttons:hover {
    background-color: #d8d8d8;
  }

  .active-page {
    background-color: #3498db;

    border: 1px solid #3498db;

    color: white;
  }

  .active-page:hover {
    background-color: #2988c8;
  }


  html.dark .paginate-buttons {
    /* height: 40px;

    width: 40px;

    border-radius: 20px;

    cursor: pointer;

    background-color: rgb(242, 242, 242);

    border: 1px solid rgb(217, 217, 217); */

    background-color: black;
    color: white;
  }

  html.dark .paginate-buttons {
    /* height: 40px;

    width: 40px;

    border-radius: 20px;

    cursor: pointer;

    background-color: rgb(242, 242, 242);

    border: 1px solid rgb(217, 217, 217); */

    background-color: black;
    color: white;
  }

  /* Mobile Pagination */

  html.dark .active-page {
    border-color: white;
    background-color: rgb(44, 41, 41);
  }

</style>