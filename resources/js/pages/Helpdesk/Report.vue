<script setup lang="ts">
// import { Head, useForm } from '@inertiajs/vue3';
import ApexChart from 'vue3-apexcharts'
import { ref, watch, onMounted } from 'vue'
import { usePreferredDark } from '@vueuse/core'

import BackNav from '@/components/custom/helpdesk/BackNavComponent.vue'

import {
    ShieldExclamationIcon, CheckCircleIcon, ArrowTrendingUpIcon, BellAlertIcon
} from '@heroicons/vue/24/outline'

// const mode = useColorMode()        // persists to localStorage by default <-- look in to this more

const isDark = usePreferredDark()
const areaRef = ref(null)
const pieRef = ref(null)
const columnRef = ref(null)

type Counts = {
    open: number;
    resolved: number;
};

const props = defineProps<{
    counts: Counts,
    areaReport: any,
}>()

watch(isDark, (dark) => {
    areaRef.value?.updateOptions(
        { chart: { foreColor: dark ? '#fff' : '#000' } });
    pieRef.value?.updateOptions(
        { chart: { foreColor: dark ? '#fff' : '#000' } })
    columnRef.value?.updateOptions(
        { chart: { foreColor: dark ? '#fff' : '#000' } })
});

const columnSeries = [{
    name: 'Net Profit',
    data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
}];

const columnOptions = {
    chart: {
        type: 'bar',
        foreColor: isDark.value ? '#fff' : '#000000',

    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            borderRadius: 5,
            borderRadiusApplication: 'end'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    },
    yaxis: {
        title: {
            text: '$ (thousands)'
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "$ " + val + " thousands"
            }
        }
    }
};

// Watchers

const pieSeries = [44, 55, 13, 33];
const pieOptions = {
    chart: {
        width: 380,
        type: 'donut',
        foreColor: isDark.value ? '#fff' : '#000000',

    },
    dataLabels: {
        enabled: true
    },

    labels: ['John', 'Jim', 'Support', 'Dev'],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                // width: 200
            },
            legend: {
                // show: false


                position: 'bottom',
            }
        }
    }],
    legend: {
        position: 'right',
        fontSize: '16px',
        markers: {
            offsetX: -6
        }
    },
};

const areaOptions = {
    chart: {
        height: 250,
        type: 'area',
        foreColor: isDark.value ? '#fff' : '#000000',
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        fontSize: '16px',
        markers: {
            offsetX: -2,
        }
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        type: 'datetime',
        categories: []
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    },
}

const areaSeries = [
    { name: 'Unresolved', data: [] },
    { name: 'Resolved', data: [] },
];


onMounted(async () => {

    console.log('areaReport', props.areaReport.period);
    areaOptions.xaxis.categories = props.areaReport.period;
    areaSeries[0].data = props.areaReport.resolvedTicketCount;
    areaSeries[1].data = props.areaReport.openTicketCount;

    // console.log('period', props.areaReport.period.target);
    // console.log('open', props.openTicketCount);
    // console.log('closed', props.resolvedTicketCount);
});



</script>

<template>

    <Head>
        <title>Helpdesk - Reports</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>
    <div class="min-h-screen bg-neutral-200 dark:bg-zinc-900 ">
        <BackNav :href="route('helpdesk')" :title="'Reports'" />
        <main class="flex flex-col gap-4 pt-8 mx-auto md:max-w-6xl lg:md-w-7xl p-4 sm:p-8">
            <h1 class="text-2xl font-bold leading-tight">Dashboard</h1>
            <h6 class="text-md font-bold text-zinc-500 mt-2 leading-tight antialiased">Reports</h6>
            <div id="filters">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn m-1  ">Range: 6M</div>
                    <ul tabindex="-1" class="dropdown-content menu  rounded-box z-1 w-52 p-2 shadow-sm">
                        <li><a>Item 1</a></li>
                        <li><a>Item 2</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn m-1">Team: All</div>
                    <ul tabindex="-1" class="dropdown-content menu rounded-box z-1 w-52 p-2 shadow-sm">
                        <li><a>Item 1</a></li>
                        <li><a>Item 2</a></li>
                    </ul>
                </div>
            </div>
            <div class="grid gap-1 sm:gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 items-center">
                <div class="card bg-white dark:bg-zinc-950 card-lg shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-md text-zinc-500">Tickets Open</h6>
                        <div class="flex flex-row items-center justify-content">
                            <p class="font-bold">{{ props.counts.open }}</p>
                            <div class="bg-zinc-200 dark:bg-zinc-800 p-2 rounded-md">
                                <ArrowTrendingUpIcon class="size-6" />
                            </div>
                        </div>
                        <p class="text-sm text-zinc-600">-10 vs resolved</p>
                    </div>
                </div>
                <div class="card bg-white dark:bg-zinc-950 card-lg shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-md text-zinc-500">Tickets Resolved</h6>
                        <div class="flex flex-row items-center justify-content">
                            <p class="font-bold">{{ props.counts.resolved }}</p>
                            <div class="bg-zinc-200 dark:bg-zinc-800 p-2 rounded-md">
                                <CheckCircleIcon class="size-6" />
                            </div>
                        </div>
                        <p class="text-sm text-zinc-600">-10 vs resolved</p>
                    </div>
                </div>
                <div class="card bg-white dark:bg-zinc-950 card-lg shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-md text-zinc-500">CSAT</h6>
                        <div class="flex flex-row items-center justify-content">
                            <p class="font-bold">331</p>
                            <div class="bg-zinc-200 dark:bg-zinc-800 p-2 rounded-md">
                                <BellAlertIcon class="size-6" />
                            </div>
                        </div>
                        <p class="text-sm text-zinc-600">-10 vs resolved</p>
                    </div>
                </div>
                <div class="card bg-white dark:bg-zinc-950 card-lg shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-md text-zinc-500">SLA Breaches</h6>
                        <div class="flex flex-row items-center justify-content">
                            <p class="font-bold">12</p>
                            <div class="bg-zinc-200 dark:bg-zinc-800 p-2 rounded-md">
                                <ShieldExclamationIcon class="size-6" />
                            </div>

                        </div>
                        <p class="text-sm text-zinc-600">-10 vs resolved</p>
                    </div>
                </div>
            </div>
            <div class="grid gap-4 grid-cols-1 lg:grid-cols-2">
                <div class="card bg-white dark:bg-zinc-950 card-lg shadow-sm mt-4 h-96">
                    <div class="card-body p-2 md:p-4">
                        <h6 class="text-sm text-zinc-400">Ticket Volume</h6>
                        <ApexChart ref="areaRef" class="dark:text-zinc-600" type="area" :options="areaOptions"
                            :series="areaSeries" height="90%" />
                    </div>
                </div>
                <div class="card bg-white dark:bg-zinc-950 card-lg shadow-sm mt-4  h-96">
                    <div class="card-body p-2 md:p-4">
                        <h6 class="text-sm text-zinc-400">Ticket Completed By</h6>
                        <ApexChart ref="pieRef" type="pie" :options="pieOptions" :series="pieSeries" width="100%"
                            height="90%" />
                    </div>
                </div>
                <div class="card bg-white dark:bg-zinc-950 card-lg shadow-sm mt-4 lg:col-span-2 h-96">
                    <div class="card-body p-2 md:p-4">
                        <h6 class="text-sm text-zinc-400">Ticket Completed By</h6>
                        <ApexChart ref="columnRef" class="dark:text-zinc-600" type="bar" :options="columnOptions"
                            :series="columnSeries" height="90%" />
                    </div>
                </div>
                <!-- Adds tickets by category (Top Issues) -->

            </div>
        </main>
    </div>
</template>

<style>
.apexcharts-tooltip-active {
    /* color: red; */
}
</style>