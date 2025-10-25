<script setup lang="ts">
// import { Head, useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue'
import ApexChart from 'vue3-apexcharts'

import {
    ShieldExclamationIcon, CheckCircleIcon, ArrowTrendingUpIcon, BellAlertIcon
} from '@heroicons/vue/24/outline'


onMounted(() => {

})
const columnSeries = [{
    name: 'Net Profit',
    data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
},
];
const columnOptions = {
    chart: {
        type: 'bar',
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



const pieSeries = [44, 55, 13, 33];
const pieOptions = {
    chart: {
        width: 380,
        type: 'donut',
    },
    dataLabels: {
        enabled: true
    },
//       theme: {
//       mode: 'dark', 
//       palette: 'palette1', 
//       monochrome: {
//           enabled: false,
//           color: '#255aee',
//           shadeTo: 'dark',
//           shadeIntensity: 0.65
//       },
//   },
  
    labels: ['John', 'Jim', 'Support', 'Dev'], // <- slice names
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
        type: 'area'
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
        categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    },
};
const areaSeries = [
    { name: 'Unresolved', data: [11, 32, 45, 32, 34, 52, 41] },
    { name: 'Resolved', data: [31, 40, 28, 51, 42, 109, 100] },
];

</script>

<template>

    <Head>
        <title>Helpdesk - Reports</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </Head>
    <div class="min-h-screen bg-neutral-200 dark:bg-zinc-900 p-4 sm:p-8">
        <main class="flex flex-col gap-4 pt-8 mx-auto md:max-w-6xl lg:md-w-7xl">
            <h1 class="text-2xl font-bold leading-tight">Dashboard</h1>
            <h6 class="text-md font-bold text-zinc-500 mt-2 leading-tight antialiased">Reports</h6>
            <div id="filters">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn m-1 bg-base-100 ">Range: 6M</div>
                    <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                        <li><a>Item 1</a></li>
                        <li><a>Item 2</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn m-1 bg-base-100 ">Team: All</div>
                    <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                        <li><a>Item 1</a></li>
                        <li><a>Item 2</a></li>
                    </ul>
                </div>
            </div>
            <div class="grid gap-1 sm:gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 items-center">
                <div class="card bg-base-100 dark:bg-zinc-950 card-lg shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-md text-zinc-500">Tickets Open</h6>
                        <div class="flex flex-row items-center justify-content">
                            <p class="font-bold">205</p>
                            <div class="bg-zinc-200 dark:bg-zinc-800 p-2 rounded-md">
                                <ArrowTrendingUpIcon class="size-6" />
                            </div>
                        </div>
                        <p class="text-sm text-zinc-600">-10 vs resolved</p>
                    </div>
                </div>
                <div class="card bg-base-100 dark:bg-zinc-950 card-lg shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-md text-zinc-500">Tickets Resolved</h6>
                        <div class="flex flex-row items-center justify-content">
                            <p class="font-bold">231</p>
                            <div class="bg-zinc-200 dark:bg-zinc-800 p-2 rounded-md">
                                <CheckCircleIcon class="size-6" />
                            </div>
                        </div>
                        <p class="text-sm text-zinc-600">-10 vs resolved</p>
                    </div>
                </div>
                <div class="card bg-base-100 dark:bg-zinc-950 card-lg shadow-sm mt-4">
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
                <div class="card bg-base-100 dark:bg-zinc-950 card-lg shadow-sm mt-4">
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
                <div class="card bg-base-100 dark:bg-zinc-950 card-lg shadow-sm mt-4 h-96">
                    <div class="card-body p-2 md:p-4">
                        <h6 class="text-sm text-zinc-400">Ticket Volume</h6>
                        <ApexChart class="dark:text-zinc-600" type="area" :options="areaOptions" :series="areaSeries" height="90%"/>
                    </div>
                </div>
                <div class="card bg-base-100 dark:bg-zinc-950 card-lg shadow-sm mt-4  h-96">
                    <div class="card-body p-2 md:p-4">
                        <h6 class="text-sm text-zinc-400">Ticket Completed By</h6>
                        <ApexChart type="pie" :options="pieOptions" :series="pieSeries" width="100%" height="90%" />
                    </div>
                </div>
                <div class="card bg-base-100 dark:bg-zinc-950 card-lg shadow-sm mt-4 lg:col-span-2 h-96">
                    <div class="card-body p-2 md:p-4">
                        <h6 class="text-sm text-zinc-400">Ticket Completed By</h6>
                        <ApexChart class="dark:text-zinc-600" type="bar" :options="columnOptions" :series="columnSeries" height="90%" />
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